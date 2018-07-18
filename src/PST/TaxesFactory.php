<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 5/31/18
 * Time: 6:51 PM
 */


namespace PST;

use \PDO;
use \PDOException;


class TaxesFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\TaxesObject", $table = "taxes", $id = "id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "country", "state", "mailcode", "active", "percentage", "tax_value"
        );
    }
}