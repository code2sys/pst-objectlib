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
            "user_id", "distributor_id", "pricing_rule", "amount", "pricingtier_id", "automatically_apply"
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

    protected function _getQuery() {
        return "Select * from (Select customerpricing.*, IfNull(distributor.name, 'Default') as distributor_name from customerpricing left join distributor using (distributor_id)) customerpricing ";
    }

    // Either it returns a record with the price field as well as the discount percentage in case it has to be adjusted out front, or it returns false.
    public function pricePartnumber($user_id, $partnumber) {
        // OK, we have to make the price for this..
        // Step 1: Does this customer have anything eligible?
        $partnumberObj = $this->master()->partnumber()->fetch(array("partnumber" => $partnumber));
        if (count($partnumberObj) == 0) {
            return false;
        }
        $partnumberObj = $partnumberObj[0];

        // Now, we should be able to inquire of these.
        $partvariations = $this->master()->partvariation()->fetch(array("partnumber_id" => $partnumberObj->id()));

    
        // Now, we need to get the available rules.
        $match_query = array_map(function($x) {
            return array(
                "user_id" => null,
                "distributor_id" => $x->get("distributor_id"),
                "automatically_apply" => 1
            );
        }, $partvariations);

        array_unshift($match_query, array(
            "user_id" => null,
            "distributor_id" => null,
            "automatically_apply" => 1
        ));

        $always_rules = $this->master()->customerpricing()->fetchList($match_query);

        // Next, we need to get the pricing tiers to which this user belongs
        $customerpricingtiers = $this->master()->customerpricingtier()->fetch(array(
            "user_id" => $user_id
        ));

        // OK, time to find any that are relevant.
        if (count($customerpricingtiers) > 0) {
            $match_query = array();
            foreach ($customerpricingtiers as $cpt) {
                foreach ($partvariations as $pv) {
                    $match_query[] = array(
                        "pricingtier_id" => $cpt->get("pricingtier_id"),
                        "user_id" => null,
                        "distributor_id" => $pv->get("distributor_id")
                    );
                }
                $match_query[] = array(
                    "pricingtier_id" => $cpt->get("pricingtier_id"),
                    "user_id" => null,
                    "distributor_id" => null
                );    
            }
            $cpt_rules = $this->master()->customerpricing()->fetchList($match_query);
        } else {
            $cpt_rules = array();
        }

        // OK, we should have those that apply automatically, and we should have those that match a customer pricing tier we care about
        // Finally, we have to look at rules that appliy to this user
        $match_query = array();
        foreach ($partvariations as $pv) {
            $match_query[] = array(
                "user_id" => $user_id,
                "distributor_id" => $pv->get("distributor_id")
            );
        }
        $match_query[] = array(
            "user_id" => $user_id,
            "distributor_id" => null
        );
        $user_rules = $this->master()->customerpricing()->fetchList($match_query);

        // OK, make a big bundle of rules
        $bundled_rules = array_merge($always_rules, $cpt_rules, $user_rules);

        if (count($bundled_rules) == 0) {
            return FALSE;
        }

        // Well, now, we have to score each one.
        $retail = $partnumber->get("price");
        $price = $retail;
        $rule = null;
        $cost = $partnumber->get("cost");
        
        // Macgyver said this was a safety check.
        // NOTE: Safety check confirming that CUSTOMER PRICE > DEALER*1.10 on ALL ITEMS IN CART; if not then price is marked up to COST*1.09
        foreach ($partvariations as $pv) {
            foreach ($bundled_rules )
        }

        

        return array($price, $rule);
    }
}