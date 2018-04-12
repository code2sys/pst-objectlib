<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 4/12/18
 * Time: 10:46 AM
 */

namespace PST;

use \PDO;
use \PDOException;

class MotorcycleTypeObject extends AbstractObject {
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}