<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 11/23/18
 * Time: 1:51 PM
 */
namespace PST;

use \PDO;
use \PDOException;


class QueuedEmailFactory extends AbstractFactory
{
    public function __construct($dbh, $master_factory, $obj = "PST\\QueuedEmailObject", $table = "queued_email", $id = "queuedEmailId")
    {
        parent::__construct($dbh, $master_factory, $obj, $table, $id);
        $this->_datacols = array(
            "userRefId", "toEmailAddress", "ccEmailAddress", "bccEmailAddress", "replyToEmailAddress", "replyToName", "fromEmailAddress", "fromName", "subject", "message", "alt_message", "queuedTime", "sendDateTime", "readyForProcess", "processedTime", "processSuccess", "debugString", "recCreated", "recUpdated", "recMarkedForDelete"
        );
    }
}