<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 11/17/18
 * Time: 5:33 AM
 */

namespace PST;

use \PDO;
use \PDOException;


class ShowcaseTrimFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\ShowcaseTrimObject", $table = "showcasetrim", $id = "showcasetrim_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "showcasemodel_id", "title", "description", "crs_trim_id", "page_id", "deleted", "thumbnail_photo", "updated", "url_title"
        );
    }
}