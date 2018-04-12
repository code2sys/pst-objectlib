<?php

namespace PST;

use \PDO;
use \PDOException;


/*

channel -> item -> [#] =   <item>
    <description>Used 2007 KTM SX 85  owned by our Decatur store and located in DECATUR. Give our sales team a call today - or fill out the contact form below.</description>
    <dsrp>1499.00</dsrp>
    <g:STOCKNO>KTM1111C</g:STOCKNO>
    <g:color>ORANGE</g:color>
    <g:condition>Used</g:condition>
    <g:make>KTM</g:make>
    <g:mileage>0</g:mileage>
    <g:model>85SX SX 85</g:model>
    <g:owner>Decatur</g:owner>
    <g:price_type>negotiable</g:price_type>
    <g:store>Decatur</g:store>
    <g:vehicle_type>MOTORCYCLE</g:vehicle_type>
    <g:vin>11111111111111111</g:vin>
    <g:year>2007</g:year>
    <image1>http://www.motorcycledealer.com/images/majorunits/2174236521/ktm1111c_4.JPG</image1>
    <image2>http://www.motorcycledealer.com/images/majorunits/2174236521/ktm1111c_5.JPG</image2>
    <image3>http://www.motorcycledealer.com/images/majorunits/2174236521/ktm1111c_1.JPG</image3>
    <image4>http://www.motorcycledealer.com/images/majorunits/2174236521/ktm1111c_3.JPG</image4>
    <link>https://www.motorcycledealer.com/vehicle/MOTORCYCLE/2007/KTM/85SX-SX-85/11111111111111111.html</link>
    <location_city>Decatur</location_city>
    <location_state>IL</location_state>
    <location_street>2635 N 22nd St</location_street>
    <location_street2></location_street2>
    <location_tel>217-423-6521</location_tel>
    <location_zip>62526</location_zip>
    <msrp>1799.00</msrp>
    <recordid>2174236521-11111111111111111</recordid>
    <style>COMPETITION</style>
    <title>2007 KTM 85SX SX 85 - ORANGE Used</title>
  </item>


 */

class MDRecordFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\MDRecordObject", $table = "mdrecord", $id = "mdrecord_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "uniqid", "mdfeed_id", "description", "dsrp", "STOCKNO", "color", "sale_condition", "make", "model", "mileage", "owner", "price_type",
            "store", "vehicle_type", "vin", "year", "link",
            "location_city", "location_state", "location_street", "location_street2", "location_tel", "location_zip",
            "msrp", "recordid", "style", "title", "last_seen", "active"
        );
    }
}