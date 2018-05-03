<?php

namespace PST;

use \PDO;
use \PDOException;


class CustomerPricingFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\CustomerPricingObject", $table = "customerpricing", $id = "customerpricing_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "user_id", "distributor_id", "pricing_rule", "amount"
        );
    }

    public function fetchFrontEnd($user_id = null) {
        $stmt = $this->dbh->prepare("Select customerpricing.*, IfNull(distributor.name, 'Default') as distributor_name from customerpricing left join distributor using (distributor_id) where customerpricing.user_id " . (is_null($user_id) ? " is null " : " = ? "));
        if (!is_null($user_id)) {
            $stmt->bindValue(1, $user_id);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}