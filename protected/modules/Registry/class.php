<?
class Registry {

    public function Get($item, $fields = 'value', $sub_id = 0) {
        if (is_array($item)) {
            $out = [];
            
            foreach (APP::Module('DB')->Select(
                $this->conf['connection'], 
                ['fetchAll', PDO::FETCH_ASSOC], 
                is_array($fields) ? array_merge(['item'], $fields) : ['item', $fields . ' AS value'], 'registry', 
                $sub_id 
                    ? [['item', 'IN', $item], ['sub_id', is_array($sub_id) ? 'IN' : '=', $sub_id, PDO::PARAM_INT]] 
                    : [['item', 'IN', $item]]
            ) as $registry) {
                if (array_key_exists($registry['item'], $out)) {
                    if (is_array($fields)) {
                        $fields_value = [];
                        
                        foreach ($fields as $value) {
                            $fields_value[$value] = $registry[$value];
                        }
                        
                        $out[$registry['item']][] = $fields_value;
                    } else {
                        if (is_array($out[$registry['item']])) {
                            $out[$registry['item']][] = $registry['value'];
                        } else {
                            $out[$registry['item']] = [$out[$registry['item']], $registry['value']];
                        }
                    }
                } else {
                    if (is_array($fields)) {
                        foreach ($fields as $value) {
                            $out[$registry['item']][0][$value] = $registry[$value];
                        }
                    } else {
                        $out[$registry['item']] = $registry['value'];
                    }
                }
            }
            
            return $out;
        } else {
            return APP::Module('DB')->Select(
                $this->conf['connection'], 
                is_array($fields) ? ['fetch', PDO::FETCH_ASSOC] : ['fetchColumn', 0], 
                is_array($fields) ? $fields : ['value'], 'registry', 
                $sub_id 
                    ? [['item', '=', $item, PDO::PARAM_STR], ['sub_id', is_array($sub_id) ? 'IN' : '=', $sub_id]] 
                    : [['item', '=', $item, PDO::PARAM_STR]]
            );
        }
    }

    public function Add($item, $value, $sub_id = 0) {
        return APP::Module('DB')->Insert(
            $this->conf['connection'], 'registry',
            Array(
                'id'            => 'NULL',
                'sub_id'        => [$sub_id, PDO::PARAM_INT],
                'item'          => [$item, PDO::PARAM_STR],
                'value'         => [$value, PDO::PARAM_STR],
                'up_date'       => 'NOW()'
            )
        );
    }
    
    public function Delete($where) {
        return APP::Module('DB')->Delete($this->conf['connection'], 'registry', $where);
    }
    
    public function Update($fields, $where) {
        APP::Module('DB')->Update($this->conf['connection'], 'registry', $fields, $where);
    }
    
}