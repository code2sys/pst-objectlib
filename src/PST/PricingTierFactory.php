<?php
namespace PST;

use \PDO;
use \PDOException;


class PricingTierFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\PricingTierObject", $table = "pricingtier", $id = "pricingtier_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "name"
        );
    }
}