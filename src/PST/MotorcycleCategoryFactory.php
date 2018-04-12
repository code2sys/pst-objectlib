<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 4/12/18
 * Time: 10:44 AM
 */


namespace PST;

use \PDO;
use \PDOException;


class MotorcycleCategoryFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\MotorcycleCategoryObject", $table = "motorcycle_category", $id = "id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "name", "date_added"
        );
    }
}