<?php

/**
 * Foggyline
 *
 * @category    Foggyline
 * @package     Foggyline_Mailchimp
 * @copyright   Copyright (c) Foggyline <ajzele@gmail.com, branko.ajzele@foggyline.net>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Foggyline_Mailchimp_WebhookController extends Mage_Core_Controller_Front_Action {

    public function notifyAction() {

        if (Mage::helper('foggyline_mailchimp')->isMCEnabled() == false) {
            return;
        }

        $params = $this->getRequest()->getParams();

        $resource = Mage::getSingleton('core/resource');
        $conn = $resource->getConnection('core_write');
        $subscriberTable = $resource->getTableName('newsletter_subscriber');

        /**
         * Below is intentionally done implementation.
         * If subscription does not exist in Magento, and it somehow
         * found its way into Mailchimp, then webhook will do nothing,
         * as there is nothing to update in Magento.
         */
        //subscribe action
        if ($params['type'] == 'subscribe') {
            try {
                /* Direct queries to avoid circled event loops (after save -> webhooks -> webhooks -> after save) */
                $fields = array('subscriber_status' => Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED);
                $where = $conn->quoteInto('subscriber_email =?', $params['data']['email']);
                $conn->update($subscriberTable, $fields, $where);
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }

        //unsubscribe action
        if ($params['type'] == 'unsubscribe') {
            try {
                /* Direct queries to avoid circled event loops (after save -> webhooks -> webhooks -> after save) */
                $fields = array('subscriber_status' => Mage_Newsletter_Model_Subscriber::STATUS_UNSUBSCRIBED);
                $where = $conn->quoteInto('subscriber_email =?', $params['data']['email']);
                $conn->update($subscriberTable, $fields, $where);
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }

}
