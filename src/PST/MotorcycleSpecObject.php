<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 4/24/18
 * Time: 5:17 PM
 */


namespace PST;

use \PDO;
use \PDOException;

class MotorcycleSpecObject extends AbstractObject {
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}