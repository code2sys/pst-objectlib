<?php

namespace PST;

use \PDO;
use \PDOException;


class DistributorFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\DistributorObject", $table = "distributor", $id = "distributor_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "name", "active", "contact1", "contact2", "phone1", "phone2", "website", "notes", "min_nonzero_inventory", "days_no_update_means_zero", "customer_distributor", "dealer_number", "username", "password", "account_number"
        );
    }
}