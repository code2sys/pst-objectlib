<?php

namespace PST;

use \PDO;
use \PDOException;

/******************************************************\
 * Include new factories here!
 *
\******************************************************/

class API extends  AbstractSuperFactory {

    protected $_superapi;

    public function __construct($dbh) {
        parent::__construct($dbh);
        $this->known_factories = array(
            "vseptprospect" => "VSeptProspectFactory",
            "mdfeed" => "MDFeedFactory",
            "mdrecord" => "MDRecordFactory",
            "mdrecordimage" => "MDRecordImageFactory"
        );
    }

    public function vseptprospect() {
        return $this->fetch("vseptprospect");
    }
    public function mdfeed() {
        return $this->fetch("mdfeed");
    }
    public function mdrecord() {
        return $this->fetch("mdrecord");
    }
    public function mdrecordimage() {
        return $this->fetch("mdrecordimage");
    }
}