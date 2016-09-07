# DB
Represents a connection with database servers:
- Cubrid
- FreeTDS / Microsoft SQL Server / Sybase
- Firebird
- IBM DB2
- IBM Informix Dynamic Server
- MySQL 3.x/4.x/5.x
- Oracle Call Interface
- ODBC v3 (IBM DB2, unixODBC и win32 ODBC)
- PostgreSQL
- SQLite 2/3
- Microsoft SQL Server / SQL Azure
- 4D

and simple ORM.

### Requirements

- [PHP Data Objects](http://php.net/manual/en/book.pdo.php)

### Dependencies
NONE

### Files
```
/protected
├── /modules
│   └── /DB
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /db
        └── errors.php
```

### Methods
```php
// Get PDO object
PDO APP::Module('DB')->Open(string $id)

// Execute INSERT statement
mixed APP::Module('DB')->Insert(string $connection, string $table, array $values)

// Execute DELETE statement
int APP::Module('DB')->Delete(string $connection, string $table[, array $where])

// Execute UPDATE statement
int APP::Module('DB')->Update(string $connection, string $table, array $fields[, array $where])

// Execute SELECT statement
mixed APP::Module('DB')->Select(string $connection, array $results, array $select, string $from[, array $where[, array $join[, array $group[, array $having[, array $order[, array $limit[, string $return]]]]]]])

// Build WHERE statement
string APP::Module('DB')->WhereStatement(array $where)

// Bind WHERE values
void APP::Module('DB')->BindWhere(array $where, PDOStatement $sql)
```

### Examples
```php
// PDO

$subid = 12345;
$firstname = 'firstname';
$lastname = 'lastname';

$sql = APP::Module('DB')->Open('connection')->prepare('INSERT INTO test VALUES (NULL, :subid, :firstname, :lastname, NOW())');

$sql->bindParam(':subid', $subid, PDO::PARAM_INT);
$sql->bindParam(':firstname', $firstname, PDO::PARAM_STR);
$sql->bindParam(':lastname', $lastname, PDO::PARAM_STR);
$sql->execute();

echo 'Last insert id: ' . APP::Module('DB')->Open('connection')->lastinsertid() . "\n";

foreach (APP::Module('DB')->Open('connection')->query('SELECT * FROM test ORDER BY id DESC LIMIT 0, 3', PDO::FETCH_ASSOC) as $value) print_r($value);

$sql = APP::Module('DB')->Open('connection')->prepare('
    SELECT * 
    FROM test 
    WHERE 
        subid = :subid && 
        firstname = :firstname && 
        lastname = :lastname 
    ORDER BY id DESC 
    LIMIT 0, 3
');

$sql->bindParam(':subid', $subid, PDO::PARAM_INT);
$sql->bindParam(':firstname', $firstname, PDO::PARAM_STR);
$sql->bindParam(':lastname', $lastname, PDO::PARAM_STR);
$sql->execute();

print_r($sql->fetchAll(PDO::FETCH_CLASS));

// ORM

APP::Module('DB')->Insert(
    'connection', 'table',
    Array(
        'column1' => 'NULL',
        'column2' => Array(123, PDO::PARAM_INT),
        'column3' => Array('value', PDO::PARAM_STR),
        'column4' => '"value"',
        'column5' => 'NOW()'
    )
);

APP::Module('DB')->Delete(
    'connection', 'table',
    Array(
        Array('column1', '=', 123, PDO::PARAM_INT)
    )
);

APP::Module('DB')->Delete(
    'connection', 'table',
    Array(
        Array('column1', 'IN', Array(1, 2, 3))
    )
);

APP::Module('DB')->Delete(
    'connection', 'table',
    Array(
        Array('column1', 'IN', 'SELECT column FROM table WHERE 1')
    )
);

APP::Module('DB')->Update(
    'connection', 'table',
    Array(
        'column1' => 'value',
        'column2' => 'value'
    ),
    Array(
        Array('column3', 'IN', Array(1, 2, 3)),
        Array('column4', '=', '123', PDO::PARAM_STR)
    )
);

APP::Module('DB')->Select(
    'connection', 
    Array(
        'fetchAll', 
        PDO::FETCH_ASSOC
    ),
    Array(
        'column1', 
        'column2', 
        'column3 AS column4'
    ),
    'table',
    Array(
        Array('column1', 'IN', Array(1, 2, 3)),
        Array('column2', '=', 123, PDO::PARAM_INT)
    )
);
```