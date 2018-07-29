<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/29/18
 * Time: 7:36 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class BrandObject extends AbstractObject
{
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}