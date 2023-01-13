<?php
define('tables_dir' , 'tables/');
define('date_format' , 'Y-m-d H:i:s');

function getNow(){
    return date(date_format);
}

if (!function_exists('nsd_table_address')) {
    function nsd_table_address($table): string
    {
        return tables_dir . $table . '.json';
    }
}

if (!function_exists('nsd_table')) {
    function nsd_table($name)
    {
        $data = file_get_contents(nsd_table_address($name));
        if ($data == '') {
            return [];
        }
        return json_decode($data,true);
    }
}

if (!function_exists('nsd_make_table')) {
    function nsd_make_table($name)
    {
        if (!is_dir(tables_dir)){
            mkdir(tables_dir);
        }
        if (file_exists(nsd_table_address($name))) {
            return false;
        }

        return file_put_contents(nsd_table_address($name), '');
    }
}

if (!function_exists('nsd_update_table')) {
    function nsd_update_table($name, $data)
    {
        return file_put_contents(nsd_table_address($name), json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    }
}

if (!function_exists('nsd_insert_data')) {
    function nsd_insert_data($table, $data)
    {
        $rows = nsd_table($table);
        $id = rand(1,999999999);
        $find = nsd_first($table,['id' => $id]);
        while ($find){
            $id = rand(100000,99999999);
            $find = nsd_first($table,['id' => $id]);
        }
        $new_data = [
            'id' =>$id
        ];
        $new_data = array_merge($new_data,$data);
        $new_data['created_at'] = getNow();
        $new_data['changed_at'] = getNow();
        $rows[] = $new_data;
        nsd_update_table($table, $rows);
        return $id;
    }
}

if (!function_exists('nsd_checkConditions')) {
    function nsd_checkConditions($row, $where)
    {
        if ($where == 1) return true;

        $where_keys = array_keys($where);

        if (is_string($where_keys[0])){
            $where2 = [];
            foreach ($where_keys as $where_key){
                $where2[] = [$where_key => $where[$where_key]];
            }
            $where = $where2;
        }

        $count_conditions = count($where);
        $true_conditions = 0;
        foreach ($where as $condition) {
            $arr_key = array_keys($condition)[0];

            if (is_string($arr_key)) {
                if ($row[$arr_key] == $condition[$arr_key]) {
                    ++$true_conditions;
                }
                continue;
            }

            $condition_key = $condition[0];
            $condition_operator = $condition[1];
            $condition_value = $condition[2];

            if (!isset($row[$condition_key])) {
                continue;
            }

            $row_value = $row[$condition_key];

            if ($condition_operator === '=' and $row_value === $condition_value) ++$true_conditions;
            elseif ($condition_operator === '<' and $row_value < $condition_value) ++$true_conditions;
            elseif ($condition_operator === '>' and $row_value > $condition_value) ++$true_conditions;
            elseif ($condition_operator === '<=' and $row_value <= $condition_value) ++$true_conditions;
            elseif ($condition_operator === '>=' and $row_value >= $condition_value) ++$true_conditions;
            elseif ($condition_operator === '!=' and $row_value != $condition_value) ++$true_conditions;
        }
        if ($true_conditions === $count_conditions) {
            return true;
        }
        return false;
    }
}

if (!function_exists('nsd_select_data')) {
    function nsd_select_data($table, $where = 1)
    {
        $rows = nsd_table($table);
        $result = [];

        foreach ($rows as $row) {
            if (nsd_checkConditions($row, $where)) {
                $result[] = $row;
            }
        }

        return $result;
    }
}

if (!function_exists('nsd_update_data')) {
    function nsd_update_data($table, $where = 1, $updates = [])
    {
        $rows = nsd_table($table);

        foreach ($rows as $index => $row) {
            if (nsd_checkConditions($row, $where)) {
                foreach ($updates as $key => $value) {
                    $rows[$index][$key] = $value;
                }
                $rows[$index]['changed_at'] = getNow();
            }
        }
        return nsd_update_table($table, $rows);
    }
}

if (!function_exists('nsd_delete_data')) {
    function nsd_delete_data($table, $where = 1)
    {
        $rows = nsd_table($table);
        $rows2 = [];
        foreach ($rows as $row) {
            if (!nsd_checkConditions($row, $where)) {
                $rows2[] = $row;
            }
        }
        return nsd_update_table($table, $rows2);
    }
}

if (!function_exists('nsd_first')) {
    function nsd_first($table, $where)
    {

        return nsd_select_data($table, $where)[0] ?? null;
    }
}



