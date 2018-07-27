<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/18/18
 * Time: 1:45 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class OrderTransactionFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\OrderTransactionObject", $table = "order_transaction", $id = "id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "order_id", "braintree_transaction_id", "amount", "transaction_date", "processor"
        );
    }

}