<?php

namespace PST;

use \PDO;
use \PDOException;


class MDRecordImageFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\MDRecordImageObject", $table = "mdrecordimage", $id = "mdrecordimage_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "mdrecord_id", "uniqid", "last_seen", "active", "url"
        );
    }
}