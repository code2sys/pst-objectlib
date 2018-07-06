<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/6/18
 * Time: 4:26 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class PartPartNumberFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\PartPartNumberObject", $table = "partpartnumber", $id = "partpartnumber_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "partnumber_id", "part_id", "massupdate", "ext_partpartnumber_id"
        );
    }
}