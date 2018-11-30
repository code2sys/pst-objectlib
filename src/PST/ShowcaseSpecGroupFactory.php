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


class ShowcaseSpecGroupFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\ShowcaseSpecGroupObject", $table = "showcasespecgroup", $id = "showcasespecgroup_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "name", "ordinal", "deleted", "crs_attributegroup_number", "showcasetrim_id"
        );
    }
}