#include <pthread.h>
#include <stdio.h>
#include <malloc.h>
#include <string.h>
#include <curl/curl.h>
#include <mysql.h>
#include <memory.h>
#include <stdlib.h>
#include <stdint.h>
#include <time.h>
#include <jansson.h>
#include <sched.h>
#include <assert.h>

// Common settings
#define CONNECTION_TIMOUT 10L
//#define DEBUG // Comment this line to disable debug messages

// Internal defines
#define MAX_MAIN_QUERY_STR_LEN 400
#define MAIN_ERROR_CODE 1
#define MAX_START_DB_ROW_LEN 500
#define MAX_KEY_LEN 20
#define ERROR_STR_LEN 100
// Macros
//#define FINISH_ITERATION  tasks[my_id].finished = 1;

// Type defenitions

typedef struct
{
    char* id;
    char* log;
    char* sender_name;
    char* sender_email;
    char* recepient;
    char* subject;
    char* html;
    char* plaintext;
    char* retries;
    int present;
    int finished;
}worker_task_t;

typedef struct
{
    int ntasks;
    worker_task_t* queue;
    int finished;
}worker_tasks_set_t;

typedef struct
{
    char* str;
    size_t len;
}string_buffer_t;

// Global variables
int                 conf_threads;
int                 conf_delay;
int                 conf_retries;
int                 conf_pack_size;
const char          *conf_api_url;
const char          *conf_api_key;
const char          *conf_db_host;
const char          *conf_db_database;
const char          *conf_db_user;
const char          *conf_db_password;

MYSQL               *workers_mysql_handles;
worker_task_t        *tasks;
pthread_cond_t      *workers_conds;
pthread_mutex_t     *workers_mutexes;
int                 *ntasks;
//uint16_t            nfinished_tasks;
//pthread_cond_t      finish_cond = PTHREAD_COND_INITIALIZER;
//pthread_mutex_t     finish_mutex = PTHREAD_MUTEX_INITIALIZER;

size_t curl_write_routine(void* ptr, size_t sz, size_t nmemb, string_buffer_t* sb)
{
    size_t new_len = sb->len + sz*nmemb;
    sb->str = realloc(sb->str, new_len + 1);

    if (!sb->str) {
        fprintf(stderr, "Error: realloc() failed.\n");
        exit(MAIN_ERROR_CODE);
    }

    memcpy(sb->str + sb->len, ptr, sz*nmemb);
    sb->str[new_len] = 0;
    sb->len = new_len;

    return sz*nmemb;
}

void init_string_buffer(string_buffer_t* buf)
{
    buf->str = (char*) malloc(1);
    buf->len = 0;

    if (!buf->str) {
        fprintf(stderr, "Error: malloc() failed.\n");
        exit(MAIN_ERROR_CODE);
    }

    buf->str[0] = 0;
}

void free_string_buffer(string_buffer_t* buf)
{
    free(buf->str);
    buf->len = 0;
}

char* perform_post_request(CURL* curl_handle, char* url, char* fields, double* ping)
{
    string_buffer_t         buf;
    CURLcode                res;

    init_string_buffer(&buf);

    curl_easy_setopt(curl_handle, CURLOPT_TIMEOUT, CONNECTION_TIMOUT);
    curl_easy_setopt(curl_handle, CURLOPT_URL, url);
    curl_easy_setopt(curl_handle, CURLOPT_POST, 1L);
    curl_easy_setopt(curl_handle, CURLOPT_POSTFIELDS, fields);
    curl_easy_setopt(curl_handle, CURLOPT_WRITEFUNCTION, curl_write_routine);
    curl_easy_setopt(curl_handle, CURLOPT_WRITEDATA, &buf);

    res = curl_easy_perform(curl_handle);

    curl_easy_getinfo(curl_handle, CURLINFO_TOTAL_TIME, ping);

    if (res != CURLE_OK) {
        *ping = 0;

#ifdef DEBUG
        fprintf(stderr, "! Cannot perform POST request: %s.\n", fields);
#endif

        *ping = 0;
        free_string_buffer(&buf);
        return NULL;
    }

    return buf.str;
}

int set_table_entry_success_state(MYSQL* mysql_handle, char* id, int retries, double ping, char* resp)
{
    char   *query;
    size_t query_len;
    size_t resp_len;
    char   *esc_resp;

    resp_len = strlen(resp);
    esc_resp = (char*)malloc(2*resp_len + 1);
    mysql_real_escape_string(mysql_handle, esc_resp, resp, resp_len);
    query_len = strlen(id) + strlen(esc_resp) + 300;
    query = (char*) malloc(query_len);
    sprintf(query, "UPDATE mail_log SET retries = %d, ping = %lf, result = \"%s\", state = \"success\" WHERE id = %s", retries, ping, esc_resp, id);
    free(esc_resp);

    if (mysql_query(mysql_handle, query)) {
        free(query);
        return -1;
    }

    if (mysql_affected_rows(mysql_handle) != 1) {
        free(query);
        return -1;
    }

    free(query);
    return 0;
}

int remove_table_entry_queue(MYSQL* mysql_handle, char* id)
{
    char   *query;
    size_t query_len;

    query_len = strlen(id) + 300;
    query = (char*) malloc(query_len);
    sprintf(query, "DELETE FROM mail_queue WHERE id = %s", id);

    if (mysql_query(mysql_handle, query)) {
        free(query);
        return -1;
    }

    if (mysql_affected_rows(mysql_handle) != 1) {
        free(query);
        return -1;
    }

    free(query);
    return 0;
}

int update_table_entry_when_failed(MYSQL* mysql_handle, char* id, int retries, double ping, char* resp)
{
    char  *query;
    size_t query_len;
    size_t resp_len;
    char   *esc_resp;
    time_t t;
    struct tm tm;

    t = time(NULL);
    tm = *localtime(&t);

    resp_len = strlen(resp);
    esc_resp = (char*)malloc(2*resp_len + 1);
    mysql_real_escape_string(mysql_handle, esc_resp, resp, resp_len);
    query_len = strlen(id) + strlen(esc_resp) + 400;
    query = (char*) malloc(query_len);

    sprintf(query, "UPDATE mail_queue SET retries = %d, ping = %lf, result = \"%s\", execute = ADDTIME(\"%d-%d-%d %d:%d:%d\", \"0 0:5:0.000000\") WHERE id = %s",
        retries, ping, esc_resp, tm.tm_year + 1900, tm.tm_mon + 1, tm.tm_mday, tm.tm_hour, tm.tm_min, tm.tm_sec, id);

    if (mysql_query(mysql_handle, query)) {
        free(query);
        return -1;
    }

    if (mysql_affected_rows(mysql_handle) != 1) {
        free(query);
        return -1;
    }

    free(query);
    return 0;
}

int set_table_entry_error_state(MYSQL* mysql_handle, char* id, double ping, char* resp)
{
    char  *query;
    char   *esc_resp;
    size_t query_len;
    size_t resp_len;

    if  (resp) {
        resp_len = strlen(resp);
        esc_resp = (char*)malloc(2*resp_len + 1);
        mysql_real_escape_string(mysql_handle, esc_resp, resp, resp_len);
        query_len = strlen(id) + strlen(esc_resp) + 150;
        query = (char*) malloc(query_len);
        sprintf(query, "UPDATE mail_log SET retries=%d,ping=%lf,state=\"error\",result = \"%s\" WHERE id = %s", conf_retries, ping, esc_resp, id);
        free(esc_resp);
    }
    else {
        query_len = strlen(id) + 150;
        query = (char*) malloc(query_len);
        sprintf(query, "UPDATE mail_log SET retries=%d,ping=%lf,state=\"error\",result = \"\" WHERE id = %s", conf_retries, ping, id);
    }

    if (mysql_query(mysql_handle, query)) {
        free(query);
        return -1;
    }

    if (mysql_affected_rows(mysql_handle) != 1) {
        free(query);
        return -1;
    }

    free(query);
    return 0;
}

void free_task(worker_task_t* task)
{
    free(task->id);
    free(task->log);
    free(task->sender_name);
    free(task->sender_email);
    free(task->recepient);
    free(task->subject);
    free(task->html);
    free(task->plaintext);
    free(task->retries);
    task->finished = 1;
}

json_t* load_json_from_str(const char *text)
{
    json_t *root;
    json_error_t error;

    root = json_loads(text, 0, &error);

    if (root) {
        return root;
    } else {
        return (json_t *)0;
    }
}

json_t* load_json_from_file(const char *file)
{
    json_t *root;
    json_error_t error;

    root = json_load_file(file, 0, &error);

    if (root) {
        return root;
    } else {
        return (json_t *)0;
    }
}

int check_response(json_t* resp_root, int* states)
{
    size_t          size;
    const char      *key;
    json_t          *value, *elem;
    int             res;
    int             i;

    res = 0;

    if (json_typeof(resp_root) != JSON_ARRAY) {
        return -1;
    }

    size = json_array_size(resp_root);
    for (i = 0; i < size; i++) {
        elem = json_array_get(resp_root, i);
        if (json_typeof(elem) != JSON_OBJECT) {
            return -1;
        }
        res = 0;
        json_object_foreach(elem, key, value) {
            if (!strcmp(key, "status")) {
                res = 1;
                if (json_typeof(value) == JSON_STRING) {
                    if (!strcmp(json_string_value(value), "success")) {
                        states[i] = 1;
                    }
                    else {
                        states[i] = 0;
                    }
                }
                else {
                    return -1;
                }
            }
        }
        if (!res) {
            return -1;
        }
    }

    return 0;
}

void* thread_routine(void* param)
{
    CURL            *curl_handle;
    worker_task_t   *task;
    worker_task_t   *curr_task;
    char            *post_fields;
    size_t          post_fields_len;
    char            *curl_resp;
    double          ping;
    int             retries;
    int             err;
    int             my_id;
    json_t          *jroot;
    int             i;
    int             j;
    int             jprev;
    int             j1;
    int             my_ntasks;
    int             *resps_states;
    char            *post_fields_part;
    size_t          new_part_len;
    size_t          part_len;
    size_t          new_post_fields_len;
    char           *sender_name;
    char           *sender_email;
    char           *log;
    char           *html;
    char           *recepient;
    char           *plaintext;
    char           *subject;
    char           *curr_resp;

    err = 0;
    my_id = (int)param;

#ifdef DEBUG
    printf("%d thread started.\n", my_id);
#endif

    // Initialize cURL library
    curl_handle = curl_easy_init();
    if (!curl_handle) {
        fprintf(stderr, "Error getting cURL handle.\n");
        exit(MAIN_ERROR_CODE);
    }

    // Initialize MySQL connection
    mysql_thread_init();

    while (1) {
        pthread_mutex_lock(&workers_mutexes[my_id]);
        pthread_cond_wait(&workers_conds[my_id], &workers_mutexes[my_id]);

#ifdef DEBUG
    printf("%d thread started iteration.\n", my_id);
#endif
        my_ntasks = ntasks[my_id];
        post_fields_len = strlen(conf_api_key) + 20;
        part_len = MAX_START_DB_ROW_LEN;
        post_fields = (char*) malloc(post_fields_len);
        post_fields_part = (char*) malloc(part_len);
        sprintf(post_fields, "key=%s", conf_api_key);

        for (i = 0; i < my_ntasks; i++) {
            curr_task = &tasks[my_id*conf_pack_size + i];
            new_part_len = strlen(curr_task->sender_name) + strlen(curr_task->sender_email) + strlen(curr_task->recepient) + strlen(curr_task->subject) + strlen(curr_task->html) + strlen(curr_task->plaintext) + strlen(curr_task->log);

            if (new_part_len > part_len) {
                part_len = new_part_len + 500;
                post_fields_part = (char*) realloc (post_fields_part, part_len);
            }

            post_fields_len += part_len + 10;
            post_fields = (char*) realloc (post_fields, post_fields_len);

            assert(post_fields_part && post_fields);

            sender_name = curl_easy_escape(curl_handle, curr_task->sender_name, strlen(curr_task->sender_name));
            sender_email = curl_easy_escape(curl_handle, curr_task->sender_email, strlen(curr_task->sender_email));
            recepient = curl_easy_escape(curl_handle, curr_task->recepient, strlen(curr_task->recepient));
            subject = curl_easy_escape(curl_handle, curr_task->subject, strlen(curr_task->subject));
            html = curl_easy_escape(curl_handle, curr_task->html, strlen(curr_task->html));
            plaintext = curl_easy_escape(curl_handle, curr_task->plaintext, strlen(curr_task->plaintext));
            log = curl_easy_escape(curl_handle, curr_task->log, strlen(curr_task->log));

            sprintf(post_fields_part, "&tasks[%d][from][name]=%s&tasks[%d][from][email]=%s&tasks[%d][to]=%s&tasks[%d][subject]=%s&tasks[%d][message][html]=%s&tasks[%d][message][text]=%s&tasks[%d][params]={\"id\":%s}",
                i, sender_name,
                i, sender_email,
                i, recepient,
                i, subject,
                i, html,
                i, plaintext,
                i, log
            );

            curl_free(sender_name);
            curl_free(sender_email);
            curl_free(recepient);
            curl_free(subject);
            curl_free(html);
            curl_free(plaintext);
            curl_free(log);

            strcat(post_fields, post_fields_part);
        }

        free(post_fields_part);

#ifdef DEBUG
    printf("post: %s\n", post_fields);
#endif

        curl_resp = perform_post_request(curl_handle, conf_api_url, post_fields, &ping);

#ifdef DEBUG
    printf("resp: %s\n", curl_resp);
#endif

        free(post_fields);

        resps_states = (int*) malloc (sizeof(int) * my_ntasks);

        if (!curl_resp) {
            memset(resps_states, 0, sizeof(int) * my_ntasks);
            err = 1;
        }
        else {
            jroot = load_json_from_str(curl_resp);
            if (!jroot) {
                err = 1;
                memset(resps_states, 0, sizeof(int) * my_ntasks);
            }
            else {
                err = check_response(jroot, resps_states);
            }
        }

        if (err) {
            memset(resps_states, 0, sizeof(int) * my_ntasks);
        }

        jprev = 0;

        for (i = 0; i < my_ntasks; i++) {
            if (!err) {
                for (j = jprev; curl_resp[j] != '{'; j++);
                for (j1 = j; curl_resp[j1] != '}'; j1++);
                jprev = j1;
                curr_resp = (char*) malloc(j1-j+3);
                strncpy(curr_resp, curl_resp + j, j1-j+1);
                curr_resp[j1-j+1] = 0;
            }
            else {
                curr_resp = (char*) malloc(ERROR_STR_LEN);
                strcpy(curr_resp, "{\"status\":\"error\"}");
            }

            curr_task = &tasks[my_id*conf_pack_size + i];
            int retries = atoi(curr_task->retries);
            retries++;

            if (!resps_states[i]) {
                if (retries == conf_retries) {
                    set_table_entry_error_state(&workers_mysql_handles[my_id], curr_task->log, ping, curr_resp);
                    remove_table_entry_queue(&workers_mysql_handles[my_id], curr_task->id);
                }
                else {
                    update_table_entry_when_failed(&workers_mysql_handles[my_id], curr_task->id, retries, ping, curr_resp);
                }
            }
            else {
                set_table_entry_success_state(&workers_mysql_handles[my_id], curr_task->log, retries, ping, curr_resp);
                remove_table_entry_queue(&workers_mysql_handles[my_id], curr_task->id);
            }

            free_task(curr_task);
            free(curr_resp);
        }

        free(resps_states);
        //free_task(task);
        free(curl_resp);
        pthread_mutex_unlock(&workers_mutexes[my_id]);
        //FINISH_ITERATION;
    }

    // This should never happend
    curl_easy_cleanup(curl_handle);
    //mysql_close(&mysql_handle);
}

void alloc_task(int tid, char* id, char* log, char* sender_name, char* sender_email, char* recepient, char* subject, char* html, char* plaintext, char* retries)
{
    tasks[tid].id = (char*) malloc (strlen(id) + 1);
    tasks[tid].log = (char*) malloc (strlen(log) + 1);
    tasks[tid].sender_name = (char*) malloc (strlen(sender_name) + 1);
    tasks[tid].sender_email = (char*) malloc (strlen(sender_email) + 1);
    tasks[tid].recepient = (char*) malloc (strlen(recepient) + 1);
    tasks[tid].subject = (char*) malloc (strlen(subject) + 1);
    tasks[tid].html = (char*) malloc (strlen(html) + 1);
    tasks[tid].plaintext = (char*) malloc (strlen(plaintext) + 1);
    tasks[tid].retries = (char*) malloc (strlen(retries) + 1);
    tasks[tid].finished = 0;

    strcpy(tasks[tid].id, id);
    strcpy(tasks[tid].log, log);
    strcpy(tasks[tid].sender_name, sender_name);
    strcpy(tasks[tid].sender_email, sender_email);
    strcpy(tasks[tid].recepient, recepient);
    strcpy(tasks[tid].subject, subject);
    strcpy(tasks[tid].html, html);
    strcpy(tasks[tid].plaintext, plaintext);
    strcpy(tasks[tid].retries, retries);
}

void wait_workers_completion()
{
    int i;
    for (i = 0; i < conf_threads; i++) {
        while (!tasks[i].finished) {
            sched_yield();
        }
    }
}

int main(int argc, char** argv)
{
    MYSQL       mysql_handle;
    my_bool     mysql_reconnect;
    MYSQL_RES   *mysql_res;
    MYSQL_ROW   mysql_row;
    pthread_t   *work_thrreads;
    int         i;
    int         j;
    char        query[MAX_MAIN_QUERY_STR_LEN];
    CURLcode    curl_res;
    time_t      t;
    struct tm   tm;
    json_t      *conf_root, *conf_api, *conf_db;
    int         tasks_present;

    if (argc < 1) {
        fprintf(stderr, "Usage: ./daemon <path to \"config.json\">\n");
        return 0;
    }

    conf_root = load_json_from_file(argv[1]);

    if (!conf_root) {
        fprintf(stderr, "Error: can\'t parse config file.\n");
        return 0;
    }

    conf_threads = atoi(json_string_value(json_object_get(conf_root, "threads")));
    conf_delay = atoi(json_string_value(json_object_get(conf_root, "delay")));
    conf_retries = atoi(json_string_value(json_object_get(conf_root, "retries")));
    conf_pack_size = atoi(json_string_value(json_object_get(conf_root, "pack_size")));

    conf_api = json_object_get(conf_root, "api");
    conf_db = json_object_get(conf_root, "db");

    conf_api_url = json_string_value(json_object_get(conf_api, "url"));
    conf_api_key = json_string_value(json_object_get(conf_api, "key"));

    conf_db_host = json_string_value(json_object_get(conf_db, "host"));
    conf_db_database = json_string_value(json_object_get(conf_db, "database"));
    conf_db_user = json_string_value(json_object_get(conf_db, "user"));
    conf_db_password = json_string_value(json_object_get(conf_db, "password"));

#ifdef DEBUG
    printf("Threads: %d\n", conf_threads);
    printf("Delay: %d\n", conf_delay);
    printf("Retries: %d\n", conf_retries);
    printf("Tasks per thread: %d\n", conf_pack_size);
    printf("[API] URL: %s\n", conf_api_url);
    printf("[API] Key: %s\n", conf_api_key);
    printf("[DB] Host: %s\n", conf_db_host);
    printf("[DB] Database: %s\n", conf_db_database);
    printf("[DB] User: %s\n", conf_db_user);
    printf("[DB] Password: %s\n", conf_db_password);
#endif

    if (conf_threads < 1) {
        fprintf(stderr, "Error: number of threads should be greater than zero.\n");
        return 0;
    }

    if (conf_delay < 1) {
        fprintf(stderr, "Error: delay duration must be greater than zero.\n");
        return 0;
    }

    if (conf_pack_size < 1) {
        fprintf(stderr, "Error: tasks per thread must be greater than 1.\n");
    }

    // Initialize cURL library
    curl_res = curl_global_init(CURL_GLOBAL_DEFAULT);
    if (curl_res != CURLE_OK) {
        fprintf(stderr, "Error initializing cURL library : %s\n",
                     curl_easy_strerror(curl_res));
        return MAIN_ERROR_CODE;
    }

    // Initialize DB client and connect to MySQL DB
    if (mysql_library_init(0, NULL, NULL)) {
        fprintf(stderr, "could not initialize MySQL library\n");
        return MAIN_ERROR_CODE;
    }

    mysql_init(&mysql_handle);
    mysql_reconnect = 1;

    if (!mysql_real_connect(&mysql_handle, conf_db_host, conf_db_user, conf_db_password, conf_db_database, 0, NULL, 0)) {
        fprintf(stderr, "Error: cannot connect to DB host: %s.\n", conf_db_host);
        return MAIN_ERROR_CODE;
    }

    // Initialize tasks
    tasks = (worker_task_t*) malloc(sizeof(worker_task_t)*conf_threads*conf_pack_size);
    ntasks = (int*) malloc(sizeof(int)*conf_threads);

    // Create workers
    work_thrreads = (pthread_t*) malloc(sizeof(pthread_t)*conf_threads);
    workers_conds = (pthread_cond_t*) malloc(sizeof(pthread_cond_t)*conf_threads);
    workers_mutexes = (pthread_mutex_t*) malloc(sizeof(pthread_mutex_t)*conf_threads);
    workers_mysql_handles = (MYSQL*) malloc(sizeof(MYSQL)*conf_threads);

    for (i = 0; i < conf_threads; i++) {
        pthread_mutex_init(&workers_mutexes[i], NULL); 
        pthread_cond_init(&workers_conds[i], NULL);
        pthread_create(&work_thrreads[i], NULL, thread_routine, (void*)i);

        mysql_init(&workers_mysql_handles[i]);
        mysql_options(&workers_mysql_handles[i], MYSQL_OPT_RECONNECT, &mysql_reconnect);

        if (!mysql_real_connect(&workers_mysql_handles[i], conf_db_host, conf_db_user, conf_db_password, conf_db_database, 0, NULL, 0)) {
            fprintf(stderr, "Error: cannot connect to DB host: %s.\n", conf_db_host);
            return MAIN_ERROR_CODE;
        }
    }

    while (1) {
        // Begin iteration
        //nfinished_tasks = 0;
        t = time(NULL);
        tm = *localtime(&t);

        sprintf(query, "SELECT id, log, sender_name, sender_email, recepient, subject, html, plaintext, retries FROM mail_queue WHERE execute <= \'%d-%d-%d %d:%d:%d\' && token = \'sendthis\' ORDER BY priority DESC LIMIT %d",
                    tm.tm_year + 1900, tm.tm_mon + 1, tm.tm_mday, tm.tm_hour, tm.tm_min, tm.tm_sec, conf_threads*conf_pack_size);

        if (mysql_query(&mysql_handle, query)) {
            fprintf(stderr, "Error: cannot query.\n");
            mysql_close(&mysql_handle);
            return MAIN_ERROR_CODE;
        }

        if (!(mysql_res = mysql_store_result(&mysql_handle))) {
            fprintf(stderr, "Error: mysql_store_result() failed.\n");
            mysql_close(&mysql_handle);
            return MAIN_ERROR_CODE;
        }

        i = 0;
        j = 0;
        tasks_present = 0;

        while ((mysql_row = mysql_fetch_row(mysql_res))) {
            alloc_task(i*conf_pack_size + j, mysql_row[0], mysql_row[1], mysql_row[2], mysql_row[3], mysql_row[4], mysql_row[5], mysql_row[6], mysql_row[7], mysql_row[8]);
            tasks_present = 1;

            if (j == conf_pack_size-1) {
                j = 0;
                ntasks[i] = conf_pack_size;
                pthread_mutex_lock(&workers_mutexes[i]);
                pthread_cond_signal(&workers_conds[i]);
                pthread_mutex_unlock(&workers_mutexes[i]);
                i++;
            }
            else {
                j++;
            }
        }

        if (!tasks_present) {
            goto end_iter;
        }

        if (j != 0) {
                ntasks[i] = j;
                pthread_mutex_lock(&workers_mutexes[i]);
                pthread_cond_signal(&workers_conds[i]);
                pthread_mutex_unlock(&workers_mutexes[i]);
        }

        if (!mysql_eof(mysql_res)) {
            fprintf(stderr, "Error: mysql_eof() failed.\n");
            mysql_close(&mysql_handle);
            return MAIN_ERROR_CODE;
        }

        mysql_free_result(mysql_res);

        // Wait until all workeer complete their tasks
        wait_workers_completion();
end_iter:
        sleep(conf_delay);
        //pthread_mutex_unlock(&finish_mutex);
    }

    // Should never happend

    free(work_thrreads);
    mysql_close(&mysql_handle);

    return 0;
}
