<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 11/13/18
 * Time: 3:37 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class PageObject extends AbstractObject
{
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}