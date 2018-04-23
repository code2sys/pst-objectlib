<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 4/23/18
 * Time: 12:25 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class MotorcycleEnquiryObject extends AbstractObject {
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}