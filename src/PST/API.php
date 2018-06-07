<?php

namespace PST;

use \PDO;
use \PDOException;

/******************************************************\
 * Include new factories here!
 *
\******************************************************/

class API extends  AbstractSuperFactory {

    protected $_superapi;

    public function __construct($dbh) {
        parent::__construct($dbh);
        $this->known_factories = array(
            "pagevaultimage" => "PageVaultImageFactory",
            "pagecalendarevent" => "PageCalendarEventFactory",
            "vseptprospect" => "VSeptProspectFactory",
            "mdfeed" => "MDFeedFactory",
            "mdrecord" => "MDRecordFactory",
            "mdrecordimage" => "MDRecordImageFactory",
            "motorcycle" => "MotorcycleFactory",
            "motorcycleimage" => "MotorcycleImageFactory",
            "motorcyclecategory" => "MotorcycleCategoryFactory",
            "config" => "ConfigFactory",
            "motorcycleenquiry" => "MotorcycleEnquiryFactory",
            "motorcyclespec" => "MotorcycleSpecFactory",
            "motorcyclespecgroup" => "MotorcycleSpecGroupFactory",
            "customerpricing" => "CustomerPricingFactory",
            "distributor" => "DistributorFactory",
            "customerpricingtier" => "CustomerPricingTierFactory",
            "pricingtier" => "PricingTierFactory",
            "partnumber" => "PartnumberFactory",
            "partvariation" => "PartvariationFactory",
            "lightspeedpart" => "LightspeedPartFactory",
            "partimage" => "PartImageFactory"
        );
    }

    public function partimage() {
        return $this->fetch("partimage");
    }

    public function lightspeedpart() {
        return $this->fetch("lightspeedpart");
    }
    public function pagevaultimage() {
        return $this->fetch("pagevaultimage");
    }
    public function pagecalendarevent() {
        return $this->fetch("pagecalendarevent");
    }


    public function partnumber() {
        return $this->fetch("partnumber");
    }
    public function partvariation() {
        return $this->fetch("partvariation");
    }

    public function customerpricingtier() {
        return $this->fetch("customerpricingtier");
    }
    public function pricingtier() {
        return $this->fetch("pricingtier");
    }


    public function customerpricing() {
        return $this->fetch("customerpricing");
    }
    public function distributor() {
        return $this->fetch("distributor");
    }

    public function motorcyclespec() {
        return $this->fetch("motorcyclespec");
    }
    public function motorcyclespecgroup() {
        return $this->fetch("motorcyclespecgroup");
    }
    public function config() {
        return $this->fetch("config");
    }
    public function motorcycleenquiry() {
        return $this->fetch("motorcycleenquiry");
    }
    public function vseptprospect() {
        return $this->fetch("vseptprospect");
    }
    public function mdfeed() {
        return $this->fetch("mdfeed");
    }
    public function mdrecord() {
        return $this->fetch("mdrecord");
    }
    public function mdrecordimage() {
        return $this->fetch("mdrecordimage");
    }
    public function motorcycle() {
        return $this->fetch("motorcycle");
    }
    public function motorcycleimage() {
        return $this->fetch("motorcycleimage");
    }
    public function motorcyclecategory() {
        return $this->fetch("motorcyclecategory");
    }
}