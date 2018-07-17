<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/17/18
 * Time: 3:02 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class OrderProductObject extends AbstractObject
{
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}