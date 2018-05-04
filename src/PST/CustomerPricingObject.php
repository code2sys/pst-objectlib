<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 4/24/18
 * Time: 5:17 PM
 */


namespace PST;

use \PDO;
use \PDOException;

class CustomerPricingObject extends AbstractObject {
    public function __construct($dbh, $id, $data, $factory)
    {
        parent::__construct($dbh, $id, $data, $factory);
    }

    public function scorePV($pv, &$price, $retail, $cost, &$rule) {
        // I ponder if I should be doing anything for MAP
        // And I ponder if I should be doing anything for the partvariation-specific price/cost

        if ($this->get("distributor_id") > 0 && $this->get("distributor_id") != $pv->get("distributor_id")) {
            return FALSE; // nothing to do here. 
        }

        // OK, the distributor still applies to us.
        switch ($this->get("pricing_rule")) {
            case "Cost+":
            $candidate_price = round($cost * floatVal($this->get("amount")), 2);
            break;

            case "Retail-":
            $candidate_price = round($retail - ($retail * floatVal($this->get("amount"))), 2);
            break;

            case "PcntMgn" : 
            $candidate_price = round($cost + floatVal($this->get("amount")) * ($retail - $cost), 2);
            break;

            default:
            return false; // should never happen.
        }

        // Safety bar #1:
        if ($candidate_price > $retail) {
            $candidate_price = $retail;
        }
        if ($candidate_price < $cost) {
            $candidate_price = $cost * 1.09; // MACGYVER!!!
        }

        // Now, we should be comparing these
        // On 05-04-18, Brandt said to FAVOR THE GREATER.
        if ($candidate_price > $price) {
            $price = $candidate_price;
            $rule = $this->id();
        }
    }
}