<?php
/**
 * User: robert
 * Date: 11/14/18
 * Time: 6:20 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class ExternalMajorUnitImageObject extends AbstractObject
{
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}