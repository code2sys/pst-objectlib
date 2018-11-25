<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 11/25/18
 * Time: 7:07 AM
 */

namespace PST;

use \PDO;
use \PDOException;

class PageSectionObject extends AbstractObject
{
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}