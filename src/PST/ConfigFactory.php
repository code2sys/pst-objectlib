<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 4/19/18
 * Time: 12:34 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class ConfigFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\ConfigObject", $table = "config", $id = "id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "key", "value"
        );
    }

    public function setKeyValue($key, $value) {
        // This checks whether it exists or not and avoids running up the ID
        $matches = $this->fetch(array("key" => $key)) ;

        if (count($matches) > 0) {
            $obj = $matches[0];
            $obj->set("value", $value);
            $obj->save();
        } else {
            $obj = $this->add(array(
                "key" => $key,
                "value" => $value
            ));
        }

        return $obj;
    }

    public function getKeyValue($key, $default = null) {
        $matches = $this->fetch(array("key" => $key), true) ;
        return count($matches) > 0 ? $matches[0]["value"] : $default;
    }
}