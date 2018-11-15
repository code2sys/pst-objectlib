<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 11/13/18
 * Time: 3:37 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class PageFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\PageObject", $table = "pages", $id = "id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "label", "title", "active", "tag", "keywords", "metatags", "css", "javascript", "delete", "widgets", "icon", "location", "external_link", "external_url", "type", "original_filename", "attachment_filename", "attachment_mime_type"< "page_custom_js", "restricted_mode"
        );
    }


    // We're going to need to fetch or create a page by a specific tag...

}