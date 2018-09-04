<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 9/4/18
 * Time: 6:20 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class DenormalizedMotorcycleObject extends AbstractObject
{
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}