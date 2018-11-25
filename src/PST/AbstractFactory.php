<?php

namespace PST;

use \PDO;
use \PDOException;

abstract class AbstractFactory
{

    protected function queryWithValues($query, $query_values) {
        $stmt = $this->dbh->prepare($query);
        for ($i = 0; $i < count($query_values); $i++) {
            $stmt->bindValue(1 + $i, $query_values[$i]);
        }
        $stmt->execute();
        return $stmt;
    }

    protected function expandKVPToQuery($kvpArray, $first = false, &$query, &$query_values) {
        foreach ($kvpArray as $k => $v) {
            if (!$first) {
                $query .= " WHERE $k = ? ";
                $first = true;
            } else {
                $query .= " AND $k = ? ";
            }
            $query_values[] = $v;
        }

    }

    public function IdentifyFactory() {
        return get_class($this);
    }

    protected function _simpleQuery() {
        return "Select * from `" . $this->table . "`";
    }

    public function simpleQuery($kvpArray, $fetch_as_arr = false, $trailer = "") {
        return $this->_subFetch($this->_simpleQuery(), $kvpArray, $fetch_as_arr, array(), 0, $trailer);
    }

    protected function _simpleCountQuery() {
        return "Select count(*) as cnt from `" . $this->table . "`";
    }

    public function simpleCount($kvpArray = array(), $trailer = "") {
        $results = $this->_subFetch($this->_simpleCountQuery(), $kvpArray, true, array(), 0, $trailer);
        if (count($results) > 0) {
            return $results[0]["cnt"];
        } else {
            return 0;
        }
    }

    protected function placeholders($text, $count = 0, $separator = ","){
        $result = array();
        if($count > 0){
            for($x=0; $x<$count; $x++){
                $result[] = $text;
            }
        }

        return implode($separator, $result);
    }

    public function bulkInsert($columns, $rows, $id = 0) {
        $question_marks = "(" . $this->placeholders("?", count($columns)) . ")";

        $query = "Insert ignore into `" . $this->table . "` (" . implode($columns, ", ") . ") values " . $this->placeholders($question_marks, count($rows));

        $values = array();
        foreach ($rows as $row) {
            foreach ($columns as $col) {
                $values[] = $row[$col];
            }
        }

        $stmt = $this->dbh->prepare($query);
        $stmt->execute($values);
    }

    /*
     * AbstractSubjectFactory
     */
    public function subjectType() {
        return $this->table;
    }

    public function getTable() {
        return $this->table;
    }

    public function setTable($table) {
        $this->table = $table;
    }

    public function getPDO() {
        return $this->dbh;
    }

    /*
     * Core
     */
    protected $dbh;
    protected $objclass;
    protected $table;
    protected $table_id;
    protected $master_factory;
    protected $_datacols;
    protected $_formattedColumns;
    protected $merge_observers;

    protected function _cleanArray($kvpArray) {
        if (count($this->_datacols) == 0) {
            return $kvpArray;
        }

        $clean_array = array();

        foreach (array_keys($kvpArray) as $key) {
            if (in_array($key, $this->_datacols)) {
                $clean_array[$key] = $kvpArray[$key];
            }
        }

        if (is_array($this->_formattedColumns)) {
            foreach ($this->_formattedColumns as $key => $function) {
                if (array_key_exists($key, $clean_array)) {
                    $clean_array[$key] = call_user_func($function, $clean_array[$key]);
                }
            }
        }

        return $clean_array;
    }

    public function master() {
        return $this->master_factory;
    }

    protected function _getQuery() {
        return "Select * from `" . $this->table . "`";
    }

    public function __construct($dbh, $master_factory, $objclass, $table = "", $table_id = "") {
        $this->dbh = $dbh;
        $this->table = $table;
        $this->table_id = $table_id;
        $this->objclass = $objclass;
        $this->master_factory = $master_factory;
        $this->_formattedColumns = array();
    }

    public function get($id, $obj = true) {
        $stmt = $this->dbh->prepare(sprintf($this->_getQuery() . " where `%s`.%s = %d limit 1", $this->table, $this->table_id, $id));
        $stmt->execute();
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($rec) {
            if (!$obj) {
                return $rec;
            }

            $obj = $this->objclass;
            return new $obj($this->dbh, $id, $rec, $this); // make it a real object, which means data plus DBH
        } else {
            return null; // it doesn't exist...
        }
    }

    /**
     * Removes a particular item by its serial number ID.
     *
     * @param $id
     */
    public function remove($id) {
        $stmt = $this->dbh->prepare(sprintf("Delete from `%s` where %s = %d limit 1", $this->table, $this->table_id, $id));
        $stmt->execute();
    }

    public function removeWhere($kvpArray) {
        $matches = $this->fetch($kvpArray);
        foreach ($matches as $m) {
            $m->remove();
        }
    }

    public function add($kvpArray) {

        $kvpArray = $this->_cleanArray($kvpArray);
        $keys = "";
        $value_string = "";
        $values = array();
        $duplicate_string = $this->table_id . " = LAST_INSERT_ID(" . $this->table_id . ")";

        foreach ($kvpArray as $k => $v) {
            $keys .= ($keys != "" ? ", " : "") . "`" . $k . "`";
            $value_string .= ($value_string != "" ? ", " : "") . "?";
            $duplicate_string .= ($duplicate_string != "" ? ", " : "") . " `$k` = values(`$k`) ";
            $values[] = $v;
        }

        $stmt = $this->dbh->prepare("Insert into `" . $this->table . "` (" . $keys . ") VALUES ( " . $value_string . ") ON DUPLICATE KEY UPDATE $duplicate_string");
        $stmt->execute($values);
        $id = $this->dbh->lastInsertId();

        if ($id == 0) {
            $stmt = $this->dbh->prepare("Select last_insert_id()");
            $stmt->execute();
            $rec = $stmt->fetch(PDO::FETCH_ASSOC);
            $id = $rec["last_insert_id()"];
        }


        // return that object...
        return $this->get($id);
    }

    public function updateWhere($set_kvpArray, $where_kvpArray) {
        $set_kvpArray = $this->_cleanArray($set_kvpArray);
        $where_kvpArray = $this->_cleanArray($where_kvpArray);

        if (count($set_kvpArray) == 0) {
            return;
        }

        $value_string = "";
        $values = array();
        $this->_generateUpdateValuesValueString($set_kvpArray, $value_string, $values);

        $where_string = "";
        $where_values = array();
        $this->_generateWhereValueString($where_kvpArray, $where_string, $where_values);



        // if we are still here...
        $query = "Update `" . $this->table . "` SET $value_string " . ($where_string != "" ? " WHERE " : "") . $where_string;
        $stmt = $this->dbh->prepare($query);
        $k = 1;
        foreach ($values as $v) {
            $stmt->bindValue($k, $v);
            $k++;
        }
        foreach ($where_values as $v) {
            $stmt->bindValue($k, $v);
            $k++;
        }
        $stmt->execute();
    }

    protected function _generateWhereValueString($kvpArray, &$where_string, &$where_values) {
        $kvpArray = $this->_cleanArray($kvpArray);
        $where_string = "";
        $where_values = array();

        foreach ($kvpArray as $k => $v) {
            $where_string .= ($where_string != "" ? " AND " : "") . " `$k` = ? ";
            $where_values[] = $v;
        }

    }

    protected function _generateUpdateValuesValueString($kvpArray, &$value_string, &$values) {
        $kvpArray = $this->_cleanArray($kvpArray);
        $value_string = "";
        $values = array();

        foreach ($kvpArray as $k => $v) {
            $value_string .= ($value_string != "" ? ", " : "") . " `$k` = ? ";
            $values[] = $v;
        }
    }

    public function update($id, $kvpArray) {
        if (count($kvpArray) == 0) {
            return $this->get($id); // nothing to do!
        }

        $value_string = "";
        $values = array();
        $this->_generateUpdateValuesValueString($kvpArray, $value_string, $values);

        if (count($values) > 0) {
            $values[] = $id;

            $stmt = $this->dbh->prepare("Update `" . $this->table . "` SET " . $value_string . " WHERE " . $this->table_id . " = ? limit 1");
            $stmt->execute($values);
        }

        // return that object...
        return $this->get($id);
    }

    protected function _subFetch($query, $kvpArray, $data_array, $values = array(), $limit = 0, $trailer = "") {
        // We used to clean on a fetch...why did we do this thing?

        $where = false;
        if (count($values) > 0) {
            $where = true;
        }

        foreach ($kvpArray as $k => $v) {
            if (!$where) {
                $query .= " WHERE ";
                $where = true;
            } else {
                $query .= " AND ";
            }
            if ($v === null || $v === FALSE) {
                $query .= " `$k` is null ";
            } else {
                $query .= " `$k` = ? ";
                $values[] = $v;
            }
        }

        if ($limit > 0) {
            $query .= sprintf(" LIMIT %d ", $limit);
        }

        if ($trailer != "") {
            $query .= $trailer;
        }

        $stmt = $this->dbh->prepare($query);
        $stmt->execute($values);

        if ($data_array) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $results = array();
            $class = $this->objclass;
            foreach ($list as $rec) {
                $results[] = new $class($this->dbh, $rec[ $this->table_id ], $rec, $this);
            }

            return $results;
        }
    }

    public function ordinalfetch($kvpArray = array(), $data_array = false, $limit = 0) {
        $query = $this->_getQuery();
        return $this->_subFetch($query, $kvpArray, $data_array, array(), $limit, " ORDER BY ordinal");
    }

    public function sortFetch($kvpArray = array(), $data_array = false, $limit = 0, $sort_string = "") {
        $query = $this->_getQuery();
        return $this->_subFetch($query, $kvpArray, $data_array, array(), $limit, $sort_string);
    }


    public function fetch($kvpArray = array(), $data_array = false, $limit = 0) {

        $query = $this->_getQuery();
        return $this->_subFetch($query, $kvpArray, $data_array, array(), $limit);
    }

    public function fetchCount($kvpArray = array()) {
        $query = "Select count(*) x from `" . $this->table . "`";
        $result = $this->_subFetch($query, $kvpArray, true);
        return count($result) ? $result[0]['x'] : 0;
    }

    public function fetchList($kvpArrayArray, $data_arrays = false) {
        $results = array();

        foreach ($kvpArrayArray as $kvpArray) {
            $results = array_merge($results, $this->fetch($kvpArray, $data_arrays));
        }

        return $results;
    }

    /**
     * Simplify saving a modified object by going back to its factory and saving it.
     *
     * @see update
     *
     * @param $obj
     * @return mixed
     */
    public function save($obj) {
        $id = $obj->id();
        return $this->update($id, $obj->to_array());
    }

    // Useful for the uploader
    protected function isNegativeExpression($string) {
        $string = strtolower(trim($string));
        return $string == "0" || $string == "no" || $string == "n";
    }

    protected function isPositiveExpression($string) {
        $string = trim($string);
        return preg_match("/\S/", $string) ? !$this->isNegativeExpression($string) : false;
    }

    public function linearizeRecord($data, $cols = array()) {
        if (count($cols) == 0) {
            if (count($this->_datacols) > 0) {
                $cols = $this->_datacols;
            } else {
                $cols = array_keys($data);
            }
        }

        $string = "";
        usort($cols, "strnatcasecmp");
        foreach ($cols as $col) {
            $string .= "(" . $col . ":" . (array_key_exists($col, $data) ? $data[$col] : "") . ");";
        }
        return strtolower($string);
    }

    // Need an action!
    public function bulkUpdate($idArray, $kvpArray) {
        if (count($kvpArray) == 0) {
            return;
        }

        if (count($idArray) == 0) {
            return;
        }

        $kvpArray = $this->_cleanArray($kvpArray);
        $value_string = "";
        $values = array();

        foreach ($kvpArray as $k => $v) {
            $value_string .= ($value_string != "" ? ", " : "") . " $k = ? ";
            $values[] = $v;
        }

        if (count($values) > 0) {
            $stmt = $this->dbh->prepare("Update `" . $this->table . "` SET " . $value_string . " WHERE " . $this->table_id . " in (" . implode(",", array_map("intVal", $idArray)) . ")" );
            $stmt->execute($values);
        }

    }

    public function deleteMatchingNewer($kvpArray, $timestamp) {
        $values = array($timestamp);
        $stmt = "Delete from `" . $this->table . "` where created > ? ";
        foreach ($kvpArray as $k => $v) {
            $stmt .= " AND $k = ? ";
            $values[] = $v;
        }

        $stmt = $this->dbh->prepare($stmt);
        for ($i = 0; $i < count($values); $i++) {
            $stmt->bindValue(1 + $i, $values[$i]);
        }
        $stmt->execute();
    }

    /**
     * @param $xml_string
     * @param $url
     */
    public function postXMLToURL($xml_string, $url, $headers = array(), $return_raw_curl_object = false, $return_raw_curl_result = false, $return_body_only = false) {
        // We are going to
        $ch = curl_init();
        $headers = [];

        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, true );
        $headers["Content-Type"] = "text/xml";
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml_string );

        //https://stackoverflow.com/questions/9183178/can-php-curl-retrieve-response-headers-and-body-in-a-single-request#9183272
        // this function is called by curl for each header received
        if ($return_body_only) {
            curl_setopt($ch, CURLOPT_HEADER, false);
        } else {

            curl_setopt($ch, CURLOPT_HEADERFUNCTION,
                function ($curl, $header) use (&$headers) {
                    $len = strlen($header);
                    $header = explode(':', $header, 2);
                    if (count($header) < 2) // ignore invalid headers
                        return $len;

                    $name = strtolower(trim($header[0]));
                    if (!array_key_exists($name, $headers))
                        $headers[$name] = [trim($header[1])];
                    else
                        $headers[$name][] = trim($header[1]);

                    return $len;
                }
            );
        }

        $result = curl_exec($ch);
        curl_close($ch);

        if(curl_error($ch))
        {
            throw new \Exception(curl_error($ch));
        } else {
            if ($return_raw_curl_object) {
                return $ch;
            } else if ($return_raw_curl_result) {
                return $result;
            } else if ($return_body_only) {
                return $result;
            } else {
                // we have to return a structure...
                return array(
                    "headers" => $headers,
                    "result" => $result
                );
            }
        }

    }

    // JLB 06-07-18
    // Transplanted from JLB-AV to manage ordinals better

    public function hasOrdinal() {
        return in_array("Ordinal", $this->_datacols);
    }

    public function getMaxOrdinal($kvpArray) {
        $results = $this->_subFetch("Select max(ordinal) as cnt from `" . $this->table . "`", $kvpArray);
        return count($results) > 0 ? $results[0]["cnt"] : 0;
    }

}
