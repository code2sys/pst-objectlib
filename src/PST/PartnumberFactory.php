<?php
namespace PST;

use \PDO;
use \PDOException;


class PartnumberFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\PartnumberObject", $table = "partnumber", $id = "partnumber_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "partnumber", "price", "cost", "markup", "sale", "weight", "inventory", "estimated_availability", "gender", "taxable", "age", "manufacturer_id", "uniqid", "updated", "universalfit", "width", "height", "length", "revisionset_id", "exclude_market_place", "closeout_market_place", "promotion_id", "xmlfeed", "massupdate", "dealer_sale", "bulk_insert_round", "ext_partnumber_id", "protect"
        );
    }

    // hoisted from account_m
    public function getDealerPriceByPartNumber( $partNumber ) {
        $stmt = $this->dbh->prepare("Select partnumber.sale, partdealervariation.quantity_available, partdealervariation.stock_code, partnumber.dealer_sale as sale, partdealervariation.quantity_available as dealer_quantity_available from partnumber join partdealervariation using (partnumber_id) where partnumber.partnumber = ?");
        $stmt->bindValue(1, $partNumber);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}