<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/6/18
 * Time: 3:28 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class PartFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\PartObject", $table = "part", $id = "part_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "name", "description", "created", "image", "featured", "mx", "uniqid", "updated", "manufacturer_id", "gender", "taxable", "age", "note", "revisionset_id", "promo_video", "featured_brand", "massupdate", "protect", "bulk_insert_round", "ext_part_id", "ordering", "retail_price", "call_for_price", "universal_fitment", "lightspeed", "hlsm", "invisible", "protected_because_invisible"
        );
    }
}