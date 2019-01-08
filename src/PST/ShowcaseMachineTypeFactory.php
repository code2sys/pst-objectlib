<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 11/17/18
 * Time: 5:30 AM
 */

namespace PST;

use \PDO;
use \PDOException;


class ShowcaseMachineTypeFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\ShowcaseMachineTypeObject", $table = "showcasemachinetype", $id = "showcasemachinetype_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "showcasemake_id", "updated", "crs_machinetype", "deleted", "page_id", "thumbnail_photo", "title", "url_title", "full_url", "short_title", "display_title", "customer_set_title"
        );
    }

    public function add($kvpArray)
    {
        $obj = parent::add($kvpArray); // TODO: Change the autogenerated stub
        $obj->fixURLTitle();
        return $obj;
    }
}