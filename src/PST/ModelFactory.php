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


class ModelFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\ModelObject", $table = "model", $id = "model_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "name", "label", "make_id", "massupdate", "ext_model_id"
        );
    }

}
