<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 4/24/18
 * Time: 5:18 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class MotorcycleSpecGroupObject extends AbstractObject {
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}