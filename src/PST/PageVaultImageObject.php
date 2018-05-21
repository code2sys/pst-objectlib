<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 5/20/18
 * Time: 6:58 PM
 */


namespace PST;

use \PDO;
use \PDOException;

class PageVaultImageObject extends AbstractObject {
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }
}