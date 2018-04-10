<?php

namespace PST;

use \PDO;
use \PDOException;


class MDFeedFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\MDFeedObject", $table = "mdfeed", $id = "mdfeed_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "source_url", "source_type"
        );
    }
}