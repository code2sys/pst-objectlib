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


class ShowcasePhotoFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\ShowcasePhotoObject", $table = "showcasephoto", $id = "showcasephoto_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "showcasemodel_id", "crs_photomap_id", "exclude"
        );
    }
}