<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 11/28/18
 * Time: 12:26 PM
 */

namespace PST;

use \PDO;
use \PDOException;

class FinanceApplicationFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\FinanceApplicationObject", $table = "finance_applications", $id = "id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "id", "initial","type","condition","year","make","model","down_payment","first_name","last_name","driver_licence","email","contact_info","physical_address","housing_info","banking_info","previous_add","employer_info","reference","application_date","application_status","joint","co_initial","co_first_name","co_last_name","co_driver_licence","co_email","co_contact_info","co_physical_address","co_housing_info","co_banking_info","co_previous_add","co_employer_info","prior_employer_info","co_prior_employer_info","driver_licence_expiration","co_driver_licence_expiration","driver_licence_state","co_driver_licence_state","ip_address"
        );
    }
}
