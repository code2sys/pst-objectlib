<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 4/12/18
 * Time: 10:44 AM
 */

namespace PST;

use \PDO;
use \PDOException;

class MotorcycleCategoryObject extends AbstractObject {
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}