<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 3/6/19
 * Time: 4:09 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class CategoryFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\CategoryObject", $table = "category", $id = "category_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "parent_category_id", "name", "long_name", "active", "featured", "mx", "meta_tag", "keywords", "uniqid", "updated", "mark_up", "google_category_num", "notice", "revisionset_id", "title", "massupdate", "ext_category_id", "protected", "ebay_category_num", "image", "fitment_based"
        );
    }

}
