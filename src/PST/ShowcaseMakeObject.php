<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 10/18/18
 * Time: 2:22 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class ShowcaseMakeObject extends ShowcaseAbstractObject
{
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }

    public function disable() {

    }

}