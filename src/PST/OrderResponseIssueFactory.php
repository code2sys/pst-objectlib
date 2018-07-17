<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/17/18
 * Time: 1:48 PM
 */


namespace PST;

use \PDO;
use \PDOException;


class OrderResponseIssueFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\OrderResponseIssueObject", $table = "order_responseissue", $id = "order_responseissue_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "order_id", "code", "message", "responseStatus"
        );
    }
}