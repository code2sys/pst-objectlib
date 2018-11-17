<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 11/17/18
 * Time: 5:33 AM
 */

namespace PST;

use \PDO;
use \PDOException;

class ShowcaseTrimObject extends ShowcaseAbstractObject
{
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }

    public function disable() {

    }
}