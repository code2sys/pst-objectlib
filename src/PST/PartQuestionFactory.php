<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/6/18
 * Time: 3:48 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class PartQuestionFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\PartQuestionObject", $table = "partquestion", $id = "partquestion_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "question", "part_id", "productquestion", "productquestion_id", "massupdate", "bulk_insert_round", "ext_partquestion_id"
        );
    }

}
