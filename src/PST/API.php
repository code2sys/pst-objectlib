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
            "dealertrackfeedlog" => "DealerTrackFeedLogFactory",
            "brand" => "BrandFactory",
            "contact" => "ContactFactory",
            "ordertransaction" => "OrderTransactionFactory",
            "orderproductlightspeedaction" => "OrderProductLightspeedActionFactory",
            "orderproduct" => "OrderProductFactory",
            "orderlightspeedshipment" => "OrderLightspeedShipmentFactory",
            "order" => "OrderFactory",
            "orderresponseissue" => "OrderResponseIssueFactory",
            "orderstatus" => "OrderStatusFactory",
            "partdealervariation" => "PartDealerVariationFactory",
            "partpartnumber" => "PartPartNumberFactory",
            "machinetype" => "MachineTypeFactory",
            "make" => "MakeFactory",
            "model" => "ModelFactory",
            "partnumbermodel" => "PartNumberModelFactory",
            "partnumberpartquestion" => "PartNumberPartQuestionFactory",
            "partquestionanswer" => "PartQuestionAnswerFactory",
            "partquestion" => "PartQuestionFactory",

            "part" => "PartFactory",
            "manufacturer" => "ManufacturerFactory",
            "hlsmxmlfeed" => "HLSMXmlFeedFactory",
            "hlsmxmlfeedrow" => "HLSMXmlFeedRowFactory",
            "taxes" => "TaxesFactory",
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
            "partimage" => "PartImageFactory",
            "denormalizedmotorcycle" => "DenormalizedMotorcycleFactory"
        );
    }

    public function dealertrackfeedlog() {
        return $this->fetch("dealertrackfeedlog");
    }

    public function denormalizedmotorcycle() {
        return $this->fetch("denormalizedmotorcycle");
    }

    public function brand() {
        return $this->fetch("brand");
    }

    public function ordertransaction() {
        return $this->fetch("ordertransaction");
    }

    public function contact() {
        return $this->fetch("contact");
    }

    public function orderproductlightspeedaction() {
        return $this->fetch("orderproductlightspeedaction");
    }

    public function orderproduct() {
        return $this->fetch("orderproduct");
    }

    public function orderlightspeedshipment() {
        return $this->fetch("orderlightspeedshipment");
    }

    public function order() {
        return $this->fetch("order");
    }

    public function orderstatus() {
        return $this->fetch("orderstatus");
    }

    public function orderresponseissue() {
        return $this->fetch("orderresponseissue");
    }

    public function partpartnumber() {
        return $this->fetch("partpartnumber");
    }

    public function partdealervariation() {
        return $this->fetch("partdealervariation");
    }

    public function machinetype() {
        return $this->fetch("machinetype");
    }

    public function make() {
        return $this->fetch("make");
    }

    public function model() {
        return $this->fetch("model");
    }

    public function partnumbermodel() {
        return $this->fetch("partnumbermodel");
    }

    public function partnumberpartquestion() {
        return $this->fetch("partnumberpartquestion");
    }

    public function partquestionanswer() {
        return $this->fetch("partquestionanswer");
    }

    public function partquestion() {
        return $this->fetch("partquestion");
    }

    public function part() {
        return $this->fetch("part");
    }

    public function manufacturer() {
        return $this->fetch("manufacturer");
    }

    public function hlsmxmlfeedrow() {
        return $this->fetch("hlsmxmlfeedrow");
    }

    public function hlsmxmlfeed() {
        return $this->fetch("hlsmxmlfeed");
    }

    public function partimage() {
        return $this->fetch("partimage");
    }

    public function lightspeedpart() {
        return $this->fetch("lightspeedpart");
    }
    public function taxes() {
        return $this->fetch("taxes");
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
