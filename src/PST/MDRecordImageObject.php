<?php
namespace PST;

use \PDO;
use \PDOException;

class MDRecordImageObject extends AbstractObject {
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}