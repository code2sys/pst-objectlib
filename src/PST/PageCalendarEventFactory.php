<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 5/20/18
 * Time: 6:59 PM
 */


namespace PST;

use \PDO;
use \PDOException;


class PageCalendarEventFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\PageCalendarEventObject", $table = "page_calendar_event", $id = "page_calendar_event_id")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "start", "end", "title", "description", "url", "address1", "address2", "state", "zip", "city"
        );
    }
}