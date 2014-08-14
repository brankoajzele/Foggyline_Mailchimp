Foggyline_Mailchimp
===================

Light (one mailing list subscribe/unsubscribe with web-hooks) Mailchimp extension for Magento

Configuration available under `System > Config > Customers > Newsletter > Mailchimp`.

Mailchimp webhooks are supported, webhook url should be configured on Mailchimp webservice side to point to `{{unsecure_base_url}}/index.php/foggyline_mailchimp/webhook/notify` or `{{secure_base_url}}/index.php/foggyline_mailchimp/webhook/notify`. Be sure to put your own store full url. 

Extension supports simple subscribe / unsubscribe / delete in Magento admin for newsletter subscription entities, by observing global newsletter_subscriber_save_before and newsletter_subscriber_delete_after events.

Please note, if subscription does not exist in Magento, and it somehow found its way into Mailchimp, and mailchimp triggers the webhook, then webhook will do nothing, as there is nothing to update in Magento. This is intentionally done implementation. What this means is that subscritpion initially has to be triggered in Magneto, either via site wide subscription input field/form, or under customers `My Account > Newsletter Subscription` section by checking the General Subscription checkbox.
