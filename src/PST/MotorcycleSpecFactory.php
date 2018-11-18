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


class MotorcycleSpecFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\MotorcycleSpecObject", $table = "motorcyclespec", $id = "motorcyclespec_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "version_number", "value", "feature_name", "attribute_name", "type", "external_package_id", "motorcycle_id", "final_value", "override", "source", "hidden", "crs_attribute_id", "ordinal", "motorcyclespecgroup_id"
        );
    }

    public function getForMotorcycle($motorcycle_id, $data_arrays = false) {
        return $this->fetch(array("motorcycle_id" => $motorcycle_id), $data_arrays);
    }

    public function suppressSpecificNotAvailable($motorcycle_id) {
        $stmt = $this->dbh->prepare("update motorcyclespec set hidden = 1 where source = 'PST' and final_value = 'Not Available' and motorcycle_id = ?");
        $stmt->bindValue(1, $motorcycle_id);
        $stmt->execute();
    }

    public function suppressAllNotAvailable() {
        $stmt = $this->dbh->prepare("update motorcyclespec set hidden = 1 where source = 'PST' and final_value = 'Not Available';");
        $stmt->execute();
    }
}