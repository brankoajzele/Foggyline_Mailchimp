<?php

/**
 * Foggyline
 *
 * @category    Foggyline
 * @package     Foggyline_Mailchimp
 * @copyright   Copyright (c) Foggyline <ajzele@gmail.com, branko.ajzele@foggyline.net>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Foggyline_Mailchimp_Model_Observer {

    private $_helper = null;

    public function __construct() {
        $this->_helper = Mage::helper('foggyline_mailchimp');
    }

    public function subscriberStatusChange($observer) {

        if ($this->_helper->isMCEnabled() == false) {
            return;
        }

        $subscriber = $observer->getEvent()->getSubscriber();

        require_once Mage::getBaseDir('app')
                . '/code/community/Foggyline/Mailchimp/lib/Mailchimp.php';

        $MC = new Mailchimp($this->_helper->getMCApiKey());
        $list = $this->_helper->getMCListId();

        //Get current status
        $status = $subscriber->getSubscriberStatus();
        $email = $subscriber->getSubscriberEmail();

        //Get language, 2 letter code, like "en"
        $merge_vars = array();
        $merge_vars['MC_LANGUAGE'] = substr(Mage::app()->getLocale()->getLocaleCode(), 0, 2);

        //Is there a customer info? If so, read firstname/lastname
        $customer = Mage::getModel('customer/customer')
                ->load($subscriber->getCustomerId());

        if ($customer && $customer->getId()) {
            $merge_vars['FNAME'] = $customer->getFirstname();
            $merge_vars['LNAME'] = $customer->getLastname();
        }

        //Subscribe
        if (($status == Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED) || ($status == Mage_Newsletter_Model_Subscriber::STATUS_UNCONFIRMED)) {
            try {
                //If double-optin, set status to UNCONFIRMED
                if ($this->_helper->getMCDoubleoptin()) {
                    $doubleoptin = true;
                    $subscriber->setSubscriberStatus(Mage_Newsletter_Model_Subscriber::STATUS_UNCONFIRMED);
                    Mage::getSingleton('core/session')->addSuccess(Mage::helper('core')->__($this->_helper->getMCMsg(), $email));
                } else {
                    $doubleoptin = false;
                }
                /** @see Mailchimp_Lists::subscribe */
                $MC->lists->subscribe($list, array('email' => $email), $merge_vars, 'html', $doubleoptin, false, true, true);
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }

        //Unsubscribe
        if (($status == Mage_Newsletter_Model_Subscriber::STATUS_UNSUBSCRIBED) || ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE)) {
            try {
                /** @see Mailchimp_Lists::unsubscribe */
                $MC->lists->unsubscribe($list, array('email' => $email));
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }

    public function subscriberDelete($observer) {
        if ($this->_helper->isMCEnabled() == false) {
            return;
        }

        try {
            require_once Mage::getBaseDir('app')
                    . '/code/community/Foggyline/Mailchimp/lib/Mailchimp.php';

            $subscriber = $observer->getEvent()->getSubscriber();
            $MC = new Mailchimp($this->_helper->getMCApiKey());
            $list = $this->_helper->getMCListId();

            /** @see Mailchimp_Lists::unsubscribe */
            $MC->lists->unsubscribe($list, array('email' => $subscriber->getSubscriberEmail()));
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

}
