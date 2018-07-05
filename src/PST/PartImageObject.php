<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 6/7/18
 * Time: 3:44 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class PartImageObject extends AbstractObject
{
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}