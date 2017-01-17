<?
class DB {

    private $con;

    public function Init() {
        foreach ($this->conf['connections'] as $id => $con) $this->Connect($id, $con);
    }

    private function Connect($id, $con) {
        $this->con[$id] = new PDO($con['type'] . ':host=' . $con['host'] . ';dbname=' . $con['database'], $con['username'], $con['password']);
        $this->con[$id]->exec('SET NAMES ' . $con['charset']);
    }

    public function Open($id) {
        if (!array_key_exists($id, (array) $this->con)) {
            throw new Exception('Could not connect to database #' . $id);
        }

        return $this->con[$id];
    }
    
    
    public function WhereStatement($where) {
        $list = [];

        if ($where) {
            foreach ((array) $where as $val) {
                switch ($val[1]) {
                    case 'BETWEEN':     $list[] = $val[0] . ' BETWEEN ' . $val[2]; break;
                    case 'LIKE':        $list[] = $val[0] . ' LIKE "' . $val[2] . '"'; break;
                    case 'NOT LIKE':    $list[] = $val[0] . ' NOT LIKE "' . $val[2] . '"'; break;
                    case 'REGEXP':      $list[] = $val[0] . ' REGEXP "' . $val[2] . '"'; break;
                    case 'NOT REGEXP':  $list[] = $val[0] . ' NOT REGEXP "' . $val[2] . '"'; break;
                    case 'IS NOT':      $list[] = $val[0] . ' IS NOT ' . $val[2]; break;
                    case 'IN':
                    case 'NOT IN':      $list[] = is_array($val[2]) ? $val[0] . ' ' . $val[1] . ' ("' . implode('","', $val[2]) . '")' : $val[0] . ' ' . $val[1] . ' (' . $val[2] . ')'; break;
                    default:            $list[] = $val[0] . ' ' . $val[1] . ' :' . str_replace(['.','(',')',',', '-', ' ', '"'], '', $val[0]); break;
                }
            }
        }

        $statement = count($list) ? implode(' && ', $list) : '1';
        
        return 'WHERE ' . $statement;
    }
    
    public function BindWhere($where, $sql) {
        if ($where) {
            foreach ((array) $where as &$val) {
                switch ($val[1]) {
                    case 'BETWEEN':
                    case 'LIKE':
                    case 'NOT LIKE':
                    case 'REGEXP':
                    case 'NOT REGEXP':
                    case 'IS NOT':
                    case 'IN':
                    case 'NOT IN': break;
                    default: $sql->bindParam(':' . str_replace(['.','(',')',',','-',' ','"'], '', $val[0]), $val[2], $val[3]); break;
                }
            }
        }
    }
    
    
    public function Insert($connection, $table, $values) {
        $val = [];
        $bind = [];
        
        foreach ((array) $values as $key => $value) {
            if (is_array($value)) {
                if (empty($value[0]) && (!is_numeric($value[0]))) {
                    $val[] = 'NULL';
                } else {
                    $val[] = ':' . str_replace('.', '_', $key);
                    array_push($bind, $key);
                }
            } else {
                $val[] = (empty($value) && (!is_numeric($value))) ? 'NULL' : $value;
            }
        }

        $sql = $this->Open($connection)->prepare('INSERT INTO ' . $table . ' VALUES (' . implode(',', $val) . ')');

        foreach ((array) $values as $key => $value) {
            if (array_search($key, $bind) !== false) {
                $sql->bindParam(':' .str_replace('.', '_', $key), $value[0], $value[1]);
            }
        }
        
        $sql->execute();
        
        return $this->Open($connection)->lastinsertid();
    }
    
    public function Delete($connection, $table, $where = false) {
        $sql = $this->Open($connection)->prepare('DELETE FROM ' . $table . ' ' . $this->WhereStatement($where));
        $this->BindWhere($where, $sql);
        $sql->execute();

        return $sql->rowCount();
    }

    public function Update($connection, $table, $fields, $where = false) {
        $set = 'SET ' . implode(', ', array_map(function($item) { return $item . ' = :' . $item; }, array_keys((array) $fields)));
        $sql = $this->Open($connection)->prepare('UPDATE ' . $table . ' ' . $set . ' ' . $this->WhereStatement($where));
        
        foreach ((array) $fields as $key => &$value) {
            if ($value) {
                $sql->bindParam(':' . $key, $value, PDO::PARAM_STR);
            } else {
                $null = null;
                $sql->bindParam(':' . $key, $null, PDO::PARAM_NULL);
            }
        }
        
        $this->BindWhere($where, $sql);
        $sql->execute();

        return $sql->rowCount();
    }
    
    public function Select($connection, $results, $select, $from, $where = false, $join = false, $group = false, $having = false, $order = false, $limit = false, $return = 'results') {
        $operators = ['SELECT ' . implode(',', $select) . ' FROM ' . $from];

        // JOIN
        // [
        //     'join/db.tbl' => [
        //         ['db.tbl.fld','=','db.tbl.fld'],
        //         ['db.tbl.fld','=','"call"']
        //     ]
        // ]
        if ($join) {
            foreach ((array) $join as $join_settings => $join_where) {
                $join_data = explode('/', $join_settings);
                $join_where_list = [];

                foreach ($join_where as $join_where_item) {
                    $join_where_list[] = implode(' ', $join_where_item);
                }

                $join_as = isset($join_data[2]) ? ' AS ' . $join_data[2] . ' ' : ' ';

                $operators[] = $join_data[0] . ' ' . $join_data[1] . $join_as . 'ON ' . implode(' && ', $join_where_list);
            }
        }

        // WHERE
        $operators[] = $this->WhereStatement($where);

        // GROUP
        // ['fld1', 'fld2']
        if ($group) {
            $operators[] = 'GROUP BY ' . implode(',', $group);
        }

        // HAVING
        // [
        //     ['COUNT(db.tbl.fld)','!=','0'],
        //     ['COUNT(db.tbl.fld)','=','"str"']
        // ]
        if ($having) {
            $having_list = [];

            foreach ((array) $having as $having_item) {
                $having_list[] = implode(' ', $having_item);
            }

            $operators[] = 'HAVING ' . implode(' && ', $having_list);
        }

        // ORDER
        // ['fld', 'DESC']
        if ($order) {
            $operators[] = 'ORDER BY ' . implode(' ', $order);
        }

        // LIMIT
        // [0, 100]
        if ($limit) {
            $operators[] = 'LIMIT ' . implode(',', $limit);
        }
        
        //print_r($operators);

        $sql = $this->Open($connection)->prepare(implode(' ', $operators));

        // BIND VALUES
        $this->BindWhere($where, $sql);

        // RETURN
        switch ($return) {
            case 'results':
                $sql->execute();
                return $sql->{$results[0]}($results[1]);
            case 'sql':
                return $sql;
        }

    }

}