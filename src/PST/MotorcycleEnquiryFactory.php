<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 4/23/18
 * Time: 12:25 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class MotorcycleEnquiryFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\MotorcycleEnquiryObject", $table = "motorcycle_enquiry", $id = "id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "firstName", "lastName", "email", "phone", "address", "city", "state", "zipcode", "date_of_ride", "make", "model", "year", "miles", "accessories", "questions", "product_id", "motorcycle", "status", "sent_time", "old_date_of_ride", "ip_address"
        );
    }
}