<?php

/**
 * Foggyline
 *
 * @category    Foggyline
 * @package     Foggyline_Mailchimp
 * @copyright   Copyright (c) Foggyline <ajzele@gmail.com, branko.ajzele@foggyline.net>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Foggyline_Mailchimp_Helper_Data extends Mage_Core_Helper_Abstract {

    const CONFIG_MC_ACTIVE = 'newsletter/foggyline_mailchimp/active';
    const CONFIG_MC_APIKEY = 'newsletter/foggyline_mailchimp/apikey';
    const CONFIG_MC_LISTID = 'newsletter/foggyline_mailchimp/listid';
    const CONFIG_MC_DOUBLEOPTIN = 'newsletter/foggyline_mailchimp/doubleoptin';
    const CONFIG_MC_MSG = 'newsletter/foggyline_mailchimp/msg';

    public function isMCEnabled($store = null) {
        return (bool) Mage::getStoreConfig(self::CONFIG_MC_ACTIVE, $store);
    }

    public function getMCApiKey($store = null) {
        return Mage::getStoreConfig(self::CONFIG_MC_APIKEY, $store);
    }

    public function getMCListId($store = null) {
        return Mage::getStoreConfig(self::CONFIG_MC_LISTID, $store);
    }

    public function getMCDoubleoptin($store = null) {
        return (bool) Mage::getStoreConfig(self::CONFIG_MC_DOUBLEOPTIN, $store);
    }

    public function getMCMsg($store = null) {
        return Mage::getStoreConfig(self::CONFIG_MC_MSG, $store);
    }

}
