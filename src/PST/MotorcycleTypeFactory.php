<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 4/12/18
 * Time: 10:46 AM
 */


namespace PST;

use \PDO;
use \PDOException;


class MotorcycleTypeFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\MotorcycleTypeObject", $table = "motorcycle_type", $id = "id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "name", "crs_type", "offroad"
        );
    }
}