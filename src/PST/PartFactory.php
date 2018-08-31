<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/6/18
 * Time: 3:28 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class PartFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\PartObject", $table = "part", $id = "part_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "name", "description", "created", "image", "featured", "mx", "uniqid", "updated", "manufacturer_id", "gender", "taxable", "age", "note", "revisionset_id", "promo_video", "featured_brand", "massupdate", "protect", "bulk_insert_round", "ext_part_id", "ordering", "retail_price", "call_for_price", "universal_fitment", "lightspeed", "hlsm", "invisible", "protected_because_invisible"
        );
    }

    /*
     * This was removed from models/account_m. What we do is we are trying to get enough to know the part numbers...but we probably need to insert dealer stock into it as part of issue #129
     */
    public function getStockByPartId($part_id) {
        $stmt = $this->dbh->prepare("Select part.*, partnumber.*, partvariation.*, partdealervariation.quantity_available as dealer_quantity_available from part join partpartnumber using (part_id) join partnumber using (partnumber_id) join partvariation using (partnumber_id) left join partdealervariation using (partvariation_id) where part.part_id = ?");
        $stmt->bindValue(1, $part_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}