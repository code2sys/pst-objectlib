<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/29/18
 * Time: 7:36 PM
 */


namespace PST;

use \PDO;
use \PDOException;

class BrandFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\BrandObject", $table = "brand", $id = "brand_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "parent_brand_id", "name", "long_name", "slug", "title", "active", "featured", "mx", "meta_tag", "keywords", "image", "mark_up", "map_percent", "exclude_market_price", "closeout_market_price", "promotion_data", "promo_video", "notice", "size_chart_status", "sizechart_url"
        );
    }

}
