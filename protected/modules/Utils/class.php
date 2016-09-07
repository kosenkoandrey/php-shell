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

}