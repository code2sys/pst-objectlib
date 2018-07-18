<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/17/18
 * Time: 1:47 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class OrderStatusFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\OrderStatusObject", $table = "order_status", $id = "id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "order_id", "status", "datetime", "userId", "notes"
        );
    }
}