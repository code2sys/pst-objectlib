<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 4/12/18
 * Time: 10:35 AM
 */


namespace PST;

use \PDO;
use \PDOException;


class MotorcycleFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\MotorcycleObject", $table = "motorcycle", $id = "id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "title", "description", "featured", "status", "condition", "sku", "vin_number",
            "mileage", "engine_type", "transmission", "retail_price", "sale_price", "data",
            "margin", "profit", "category", "vehicle_type", "year", "make", "model",
            "engine_hours", "color", "craigslist_feed_status", "cycletrader_feed_status",
            "call_on_price", "vault", "destination_charge", "crs_trim_id", "crs_machinetype",
            "crs_model_id", "crs_make_id", "crs_year", "uniqid", "source", "crs_version_number",
            "url_title", "lightspeed_dealerID", "lightspeed", "lightspeed_location",
            "lightspeed_flag", "lightspeed_timestamp", "stock_status", "deleted",
            "customer_set_price", "customer_set_description", "customer_deleted",
            "lightspeed_deleted", "mdfeed", "mdfeed_deleted", "location_description", "mdrecord_recordid",
            "mdfeed_flag", "customer_set_location_description"
        );
    }
}