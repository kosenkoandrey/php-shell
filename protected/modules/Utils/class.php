<?
class Utils {

    function SizeConvert($bytes) {
        $b = floatval($bytes);

        foreach ([
            ['TB',  pow(1024, 4)],
            ['GB',  pow(1024, 3)],
            ['MB',  pow(1024, 2)],
            ['KB',  1024],
            ['B',   1]
        ] as $val) {
            if ($b >= $val[1]) {
                $res = $b / $val[1];
                $res = str_replace('.', ',' , strval(round($res, 2))).' '.$val[0];
                break;
            }
        }
        
        return $res;
    }
    
    function IsAssocArray($array) {
        return array_keys($array) !== range(0, count($array) - 1);
    }
    
    function Curl($settings, $return = 'result') {
        $curl = curl_init();
        
        foreach ($settings as $key => $value) {
            switch ($key) {
                case 'url': curl_setopt($curl, CURLOPT_URL, $value); break;
                case 'return_transfer': curl_setopt($curl, CURLOPT_RETURNTRANSFER, $value); break;
                case 'header': curl_setopt($curl, CURLOPT_HEADER, $value); break;
                case 'follow_location': curl_setopt($curl, CURLOPT_FOLLOWLOCATION, $value); break;
                case 'timeout': curl_setopt($curl, CURLOPT_TIMEOUT, $value); break;
                case 'fail_on_error': curl_setopt($curl, CURLOPT_FAILONERROR, $value); break;
                case 'auto_referer': curl_setopt($curl, CURLOPT_AUTOREFERER, $value); break;
                case 'ssl_verify_peer': curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $value); break;
                case 'ssl_verify_host': curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $value); break;
                case 'cookie_session': curl_setopt($curl, CURLOPT_COOKIESESSION, $value); break;
                case 'useragent': curl_setopt($curl, CURLOPT_USERAGENT, $value); break;
                case 'http_header': curl_setopt($curl, CURLOPT_HTTPHEADER, $value); break;
                case 'cookie_file': curl_setopt($curl, CURLOPT_COOKIEFILE, $value); break;
                case 'cookie_jar': curl_setopt($curl, CURLOPT_COOKIEJAR, $value); break;
                case 'autoreferer': curl_setopt($curl, CURLOPT_AUTOREFERER, $value); break;
                case 'fresh_connect': curl_setopt($curl, CURLOPT_FRESH_CONNECT, $value); break;
                case 'http_proxy_tunnel': curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, $value); break;
                case 'http_version': curl_setopt($curl, CURLOPT_HTTP_VERSION, $value); break;
                case 'cookie': curl_setopt($curl, CURLOPT_COOKIE, $value); break;
                case 'custom_request': curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $value); break;
                case 'post': 
                    curl_setopt($curl, CURLOPT_POST, true); 
                    curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($value) ? http_build_query($value) : $value);
                    break;
            }
        }
        
        switch ($return) {
            case 'result':
                $out = curl_exec($curl);
                curl_close($curl);
                return $out;
            case 'resource':
                return $curl;
        }
    }
    
    function ClientIP() {
        $client  = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : false;
        $forward = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : false;

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

}