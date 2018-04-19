<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 4/19/18
 * Time: 12:34 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class ConfigObject extends AbstractObject
{
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}