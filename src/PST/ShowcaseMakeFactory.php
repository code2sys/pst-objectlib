<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 10/18/18
 * Time: 2:22 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class ShowcaseMakeFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\ShowcaseMakeObject", $table = "showcasemake", $id = "showcasemake_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "make", "crs_make_id", "description", "include_model_pattern", "exclude_model_pattern", "thumbnail_photo", "title", "updated", "page_id", "deleted", "url_title", "full_url", "short_title"
        );
    }
}