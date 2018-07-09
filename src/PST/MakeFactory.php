<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/6/18
 * Time: 3:49 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class MakeFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\MakeObject", $table = "make", $id = "make_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "label", "name", "machinetype_id", "massupdate"
        );
    }

}
