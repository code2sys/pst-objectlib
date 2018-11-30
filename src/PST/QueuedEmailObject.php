<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 11/23/18
 * Time: 1:51 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class QueuedEmailObject extends AbstractObject {
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}