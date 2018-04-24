<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 4/24/18
 * Time: 5:17 PM
 */


namespace PST;

use \PDO;
use \PDOException;


class MotorcycleSpecGroupFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\MotorcycleSpecGroupObject", $table = "motorcyclespecgroup", $id = "motorcyclespecgroup_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "name", "ordinal", "hidden", "source", "crs_attributegroup_number", "motorcycle_id"
        );
    }
}