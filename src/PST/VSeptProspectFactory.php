<?php


namespace PST;

use \PDO;
use \PDOException;


class VSeptProspectFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\VSeptProspectObject", $table = "vseptprospect", $id = "vseptprospect_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
"Email", "Name", "Phone", "AltPhone", "SourceDate", "Address1", "Address2", "City", "State", "ZipCode", "VehicleType", "VehicleMake", "VehicleModel", "VehicleYear", "Notes", "ProspectType", "BirthDate", "CAEmployerName", "CAJobTitle", "CAHireDate", "CAMonthlyIncome", "CATimeAtAddress", "CARentOrMortgagePayment", "CARentOrOwn", "PCHId", "vsept_raw_response"
        );
    }
}