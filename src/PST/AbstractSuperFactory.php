<?php


namespace PST;

use \PDO;
use \PDOException;

/******************************************************\
 * Include new factories here!
 *
\******************************************************/


class AbstractSuperFactory {

    protected $dbh;
    protected $factory_map;
    protected $known_factories;
    protected $hostname;

    public function getHostname() {
        return $this->hostname;
    }

    public function setHostname($hostname) {
        $this->hostname = $hostname;
    }

    // for long time waiting and let CI refresh
    public function resetDBH($dbh) {
        $this->dbh = $dbh;
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->factory_map = array();
    }

    public function getDBH() {
        return $this->dbh;
    }

    // Don't do this too often.
    public function initializeAllSubFactories() {
        foreach ($this->known_factories as $key => $factory) {
            $this->fetch($key); // that should cause it all to initialize.
        }
    }

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->factory_map = array();
        $this->known_factories = array();
    }

    public function fetch($factoryname) {
        if (array_key_exists($factoryname, $this->factory_map)) {
            return $this->factory_map[$factoryname];
        }

        $full_factory = array_key_exists($factoryname, $this->known_factories) ? $this->known_factories[$factoryname] : "";

        if ($full_factory != "") {
            $class = "PST\\$full_factory";
            $this->factory_map[$factoryname] = new $class($this->dbh, $this);
            return $this->factory_map[$factoryname];
        } else {
            error_log("ERROR: Call for factory $factoryname unrecognized.");
            return null;
        }

        return $this->$factoryname;
    }
}