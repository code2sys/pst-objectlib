<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/18/18
 * Time: 1:52 PM
 */
namespace PST;

use \PDO;
use \PDOException;

class ContactObject extends AbstractObject {
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}