<?php
define("CREATE_TYPE", 100);
define("INSERT_TYPE", 200);
define("PRINT_VALUE", 1);

class DatabaseWork
{
    /**
     * Universal function adding a new record to the database.
     *
     * @param  string $model 
     * Table model to add 
     * @param  array $data
     * Data to add
     * @param array $type
     * [optional]
     * 
     * Valid arguments are as follows:
     * Bitmask consisting of CREATE_TYPE, INSERT_TYPE, PRINT_VALUE.
     * @return boolean/integer
     */

    function orderCreate($model, $data, $type = CREATE_TYPE)
    {
        if (is_string($model)) {
            $appTableName = "App\Models\\" . ucfirst($model);
            $result = new $appTableName();
        } else $result = $model;

        if ($type > 200) throw new Exception("When adding the wrong function type is specified");
        if ($type == 200) {
            //insert type
            $result = $result->insert($data);
        } elseif ($type < 200) {
            //create type
            $result = $result->create($data);
        }

        if ($type == 101) {
            if (!isset($result->id)) return false;
            return $result->id;
        } else {
            if (!empty($result)) return true;
            return false;
        }
    }

    /**
     * Universal function change a record to the database.
     *
     * @param  string $model 
     * Table model to change 
     * @param  array $data
     * Data to change
     * @param int $id
     * Record ID, or other identifier
     * @param array $condition
     * The null element of the array is the main identifier 
     * for the where condition, the first element of 
     * the array indicates the name of the field for multiple selection
     * @param array $ids
     * Array of identifiers for multiple records
     *  
     * @return boolean
     */

    function orderChange($model, $data, $id, $condition = ['id', 'user_id'], $ids = [])
    {
        if (is_string($model)) {
            $appTableName = "App\Models\\" . ucfirst($model);
            $result = new $appTableName();
        } else $result = $model;

        is_array($id) ? $result = $result->whereIn($condition[0], $id) : $result = $result->where($condition[0], $id);
        if (count($ids) > 0) $result = $result->whereIn($condition[1], $ids);
        $result = $result->update($data);

        if ($result) return true;
        return false;
    }

    /**
     * Universal function delete a record to the database.
     *
     * @param  string $model 
     * Table model to change 
     * @param  int/array $id
     * Record ID, or other identifier
     * @param array $condition
     * The null element of the array is the main identifier 
     * for the where condition, the first element of 
     * the array indicates the name of the field for multiple selection
     * @param array $ids
     * Array of identifiers for multiple records
     * 
     * @return boolean
     */

    function orderDelete($model, $id, $condition = ['id', 'user_id'], $ids = [])
    {
        if (is_string($model)) {
            $appTableName = "App\Models\\" . ucfirst($model);
            $result = new $appTableName();
        } else $result = $model;

        is_array($id) ? $result = $result->whereIn($condition[0], $id) : $result = $result->where($condition[0], $id);
        if (count($ids) > 0) $result = $result->whereIn($condition[1], $ids);
        $result = $result->delete();
        if ($result) return true;
        return false;
    }
}
