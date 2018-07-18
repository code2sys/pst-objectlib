<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 5/31/18
 * Time: 6:52 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class TaxesObject extends AbstractObject
{
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}