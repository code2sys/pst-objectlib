<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 7/18/18
 * Time: 1:52 PM
 */

namespace PST;

use \PDO;
use \PDOException;


class ContactFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\ContactObject", $table = "contact", $id = "id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            'first_name',
            'last_name',
            'street_address',
            'address_2',
            'city',
            'state',
            'zip',
            'country',
            'email',
            'phone',
            'fax',
            'company',
            'sales_email',
            'merchant_id',
            'public_key',
            'private_key',
            'environment',
            'service_email',
            'finance_email',
            'google_trust',
            'google_conversion_id',
            'fb_remarketing_pixel',
            'analytics_id',
            'google_conversion_label',
            'google_site_verification',
            'bing_site_verification',
            'additional_tracking_code',
            'additional_footer_code',
            'ebay_app_id',
            'ebay_cert_id',
            'ebay_dev_id',
            'ebay_user_token',
            'ebay_environment',
            'ebay_paypal_email',
            'partsfinder_link',
            'lightspeed_username',
            'lightspeed_password',
            'out_of_stock_active',
            'stock_status_mode',
            'free_form_hours',
            'free_form_hour_blob',
            'monday_hours',
            'tuesday_hours',
            'wednesday_hours',
            'thursday_hours',
            'friday_hours',
            'saturday_hours',
            'sunday_hours',
            'hours_note',
            'lightspeed_active_load',
            'lightspeed_cycletrader_load',
            'lightspeed_override_parts_pricing',
            'mdfeed_active_load', "merchant_type", "stripe_api_key", "stripe_secret_api_key", "trafficLogProApiKey", "trafficLogProDealerCode", "lightspeed_default_destination_charge"
        );
    }

}