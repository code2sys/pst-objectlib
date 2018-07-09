<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/6/18
 * Time: 3:50 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class PartNumberModelFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\PartNumberModelObject", $table = "partnumbermodel", $id = "partnumbermodel_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "partnumber_id", "model_id", "year", "massupdate"
        );
    }

}
