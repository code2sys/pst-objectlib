<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/17/18
 * Time: 1:47 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class OrderFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\OrderObject", $table = "order", $id = "id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "id", "user_id", "contact_id", 'shipping_id', 'distributor_shipping_id',
            'sales_price', 'shipping', 'tax', 'order_date', 'process_date', 'process_user',
            'batch_number', 'shipped_date', 'weight', 'couponCode', 'Wholesaler_ID',
            'Wholsaler_Order_ID', // Yes that is a typo in the DB
            'Reveived_date', // Yes that is a typo in the DB
            'Retailer_Order_ID',
            'Completed_Date',
            'special_instr',
            'will_call',
            'shipping_type',
            'ship_tracking_code',
            'IP',
            'sendPst',
            'created_by',
            'braintree_transaction_id',
            'product_cost',
            'shipping_cost',
            'source',
            'ebay_order_id',
            'pending_to_lightspeed',
            'ack_pending_by_lightspeed',
            'cancel_to_lightspeed',
            'ack_cancel_by_lightspeed',
            'ack_pending_by_lightspeed_timestamp',
            'ack_cancel_by_lightspeed_timestamp'
        );
    }

    // These should show up in the lightspeed feed...
    public function introspectLightspeed() {
        // You have to introspect those parts..
        $this->master()->orderproduct()->introspectLightspeed();

        // First, are there any of these that are canceled?
        $stmt = $this->dbh->prepare("Update `order` join `order_status` on `order`.id = order_status.order_id set `order`.cancel_to_lightspeed = 1 where order_status.status = 'Refunded' and `order`.cancel_to_lightspeed = 0");
        $stmt->execute();

        // Now, we should then be looking for Approved that is not shipped/complete, is not Partially Shipped, is not Declined, is not Refunded.
        $stmt = $this->dbh->prepare("Update `order` left join order_status A on `order`.id = A.order_id AND A.status = 'Approved' left join order_status B on `order`.id = B.order_id and B.status in ('Refunded', 'Shipped/Complete', 'Declined', 'Returned', 'Partially Shipped') set `order`.pending_to_lightspeed = 1 where `order`.pending_to_lightspeed = 0 and B.order_id is null and A.order_id > 0 and `order`.cancel_to_lightspeed = 0;");
        $stmt->execute();

    }
}