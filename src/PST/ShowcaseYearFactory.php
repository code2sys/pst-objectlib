<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 10/18/18
 * Time: 2:23 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class ShowcaseYearFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\ShowcaseYearObject", $table = "showcaseyear", $id = "showcaseyear_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "showcasemake_id", "year"
        );
    }
}