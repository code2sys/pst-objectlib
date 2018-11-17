<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 10/18/18
 * Time: 2:23 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class ShowcaseSpecFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\ShowcaseSpecObject", $table = "showcasespec", $id = "showcasespec_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "version_number", "value", "feature_name", "attribute_name", "type", "external_package_id", "showcasetrim_id", "final_value", "override", "deleted", "crs_attribute_id", "ordinal", "shwocasespecgroup_id"
        );
    }
}