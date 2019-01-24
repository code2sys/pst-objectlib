<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 4/12/18
 * Time: 10:42 AM
 */


namespace PST;

use \PDO;
use \PDOException;


class MotorcycleImageFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\MotorcycleImageObject", $table = "motorcycleimage", $id = "id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "motorcycle_id", "image_name", "date_added", "description", "priority_number",
            "external", "version_number", "source", "disable", "crs_thumbnail",
            "extra_data", "customer_deleted"
        );
    }
}