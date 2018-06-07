<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 6/7/18
 * Time: 3:44 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class PartImageFactory extends ManagedOrdinalFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\PartImageObject", $table = "partimage", $id = "partimage_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "part_id", "original_filename", "path", "description", "thumbnail", "spritesheet", "spriteurl", "spritex", "spritey", "spritewidth", "spriteheight", "massupdate", "mx", "external_url", "ordinal"
        );
    }
}
