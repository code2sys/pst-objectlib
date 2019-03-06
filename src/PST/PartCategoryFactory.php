<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 3/6/19
 * Time: 4:22 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class PartCategoryFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\PartCategoryObject", $table = "partcategory", $id = "partcategory_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "part_id", "category_id", "uniqid", "updated", "massupdate"
        );
    }

}
