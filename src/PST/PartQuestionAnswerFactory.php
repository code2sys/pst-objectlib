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


class PartQuestionAnswerFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\PartQuestionAnswerObject", $table = "partquestionanswer", $id = "partquestionanswer_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "partquestion_id", "answer", "massupdate"
        );
    }

}
