<?php
namespace PST;

use \PDO;
use \PDOException;


class CustomerPricingTierFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\CustomerPricingTierObject", $table = "customerpricingtier", $id = "customerpricingtier_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "pricingtier_id", "user_id"
        );
    }
}