<?php

namespace PST;

use \PDO;
use \PDOException;


class CustomerPricingFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\CustomerPricingObject", $table = "customerpricing", $id = "customerpricing_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "user_id", "distributor_id", "pricing_rule", "amount"
        );
    }
}