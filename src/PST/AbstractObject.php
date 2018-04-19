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

    /**
     * Generate a well-formed XML representation of this object
     *
     * @param $wrapper_tag string the outermost XML tag, or uses the table name
     * @param $exclude_fields array a list of fields to not include in the XML
     * @param $extra_data array if you want extra data layered in
     * @param $force_uppercase bool Whether to require all tags to be uppercase
     * @param $header - whether to include the XML header or not...
     * @return XML string
     */
    public function to_XML($wrapper_tag = "", $exclude_fields = array(), $extra_data = array(), $force_uppercase = false, $header = true) {
        if ($wrapper_tag == "") {
            $wrapper_tag = $this->factory()->getTable();
        }

        $string = $header ? '<?xml version="1.0" encoding="utf-8"?>' : "";
        $string .= '<' . ($force_uppercase ? strtoupper($wrapper_tag) : $wrapper_tag) . '>';

        $data = $this->to_array();

        // these are overwrites or additional data pieces...
        if (count($extra_data) > 0) {
            foreach ($extra_data as $k => $s) {
                $data[$k] = $s;
            }
        }

        foreach ($data as $k => $s) {
            if (!in_array($k, $exclude_fields)) {
                $string .= '<' . ($force_uppercase ? strtoupper($k) : $k) . '>';
                // https://stackoverflow.com/questions/3957360/generating-xml-document-in-php-escape-characters#3957519
                $s = html_entity_decode($s, ENT_QUOTES, 'UTF-8');
                $s = htmlspecialchars($s, ENT_QUOTES, 'UTF-8', false);
                $string .= $s;
                $string .= '</' . ($force_uppercase ? strtoupper($k) : $k) . '>';
            }
        }


        $string .= '</' . ($force_uppercase ? strtoupper($wrapper_tag) : $wrapper_tag) . '>';
        return $string;
    }

}