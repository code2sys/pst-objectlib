<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/6/18
 * Time: 3:29 PM
 */


namespace PST;

use \PDO;
use \PDOException;

class PartObject extends AbstractObject
{
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}