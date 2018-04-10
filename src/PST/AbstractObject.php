<?php

namespace PST;
use \PDO;
use \PDOException;

class AbstractObject {

    protected $dbh;
    protected $data;
    protected $_id;
    protected $_factory;

    public function __construct($dbh, $id, $data, $factory) {
        $this->dbh = $dbh;
        $this->data = $data;
        $this->id($id);
        $this->factory($factory);
    }

    public function master($m = false) {
        // convenience function
        return $this->factory()->master($m);
    }

    public function factory($factory = false) {
        if ($factory !== FALSE) {
            $this->_factory = $factory;
        }

        return $this->_factory;
    }

    public function id($idval = false) {
        if ($idval !== FALSE) {
            $this->_id = $idval;
        }
        return $this->_id;
    }

    public function get($key) {
        return array_key_exists($key, $this->data) ? $this->data[$key] : false;
    }

    public function set($key, $value) {
        $this->data[$key] = $value;
    }

    /**
     * For convenience, you can save the object; it will go to the factory.
     *
     * @return mixed
     */
    public function save() {
        return $this->factory()->save($this); // bounce this save call up to the factory for convenience.
    }

    /**
     * For convenience, you can also remove the object; it will go to the factory.  It's not clear what you'd
     * do after you did that, of course.
     */
    public function remove() {
        return $this->factory()->remove($this->id());
    }

    /**
     * Generate an array for this thing...
     */
    public function to_array() {
        return $this->data;
    }
}