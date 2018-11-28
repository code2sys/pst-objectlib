<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 11/28/18
 * Time: 12:26 PM
 */


namespace PST;

use \PDO;
use \PDOException;

class FinanceApplicationObject extends AbstractObject
{
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}