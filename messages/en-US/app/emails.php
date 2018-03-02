<?php
/**
 * Message translations.
 *
 * This file is automatically generated by 'yii message/extract' command.
 * It contains the localizable messages extracted from source code.
 * You may modify this file by translating the extracted messages.
 *
 * Each array element represents the translation (value) of a message (key).
 * If the value is empty, the message is considered as not translated.
 * Messages that no longer need translation will have their translations
 * enclosed between a pair of '@@' marks.
 *
 * Message string can be used with plural forms format. Check i18n section
 * of the guide for details.
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
return [
	'DEVISER_NEW_ORDER_SUBJECT' => 'YOU HAVE A NEW SALE',
	'DEVISER_NEW_ORDER_HEADER' => 'You have a new sale',
	'DEVISER_NEW_ORDER_HELLO' => 'Hello {{deviser_name}}',
	'DEVISER_NEW_ORDER_TEXT' => '{{client_name}} bought the following products:',
	'DEVISER_NEW_ORDER_TEXT_2' => 'Please log into your Todevise profile and let us know that you are aware of the sale (even if you are not ready to send it yet, we need to know that you saw the notification).',
	'DEVISER_NEW_ORDER_BUTTON_TEXT' => 'GO TO MY PROFILE',

	'DEVISER_NEW_ORDER_REMINDER_24_SUBJECT' => 'REMINDER: YOU HAVE A NEW SALE',
	'DEVISER_NEW_ORDER_REMINDER_24_HEADER' => 'REMINDER: YOU HAVE A NEW SALE',
	'DEVISER_NEW_ORDER_REMINDER_24_HELLO' => 'Hello {{deviser_name}}',
	'DEVISER_NEW_ORDER_REMINDER_24_TEXT' => '{{client_name}} bought the following products:',
	'DEVISER_NEW_ORDER_REMINDER_24_TEXT_2' => 'Please let us know that you saw this sale & fill in the shipping information inside your profile when you shipped the package. To do so, go to SETTINGS - MY ORDERS.',
	'DEVISER_NEW_ORDER_REMINDER_24_BUTTON_TEXT' => 'GO TO MY PROFILE',

	'DEVISER_NEW_ORDER_REMINDER_48_SUBJECT' => 'REMINDER: ONE OF YOUR NEW SALES WASN’T ATTENDED',
	'DEVISER_NEW_ORDER_REMINDER_48_HEADER' => 'REMINDER: ONE OF YOUR NEW SALES WASN’T ATTENDED',
	'DEVISER_NEW_ORDER_REMINDER_48_HELLO' => 'Hello {{deviser_name}}',
	'DEVISER_NEW_ORDER_REMINDER_48_TEXT' => '{{client_name}} bought the following products:',
	'DEVISER_NEW_ORDER_REMINDER_48_TEXT_2' => 'You have an unattended sale. Please sign into your Todevise profile, let us know that you saw the sale and fill in the shipping information when you send the package.',
	'DEVISER_NEW_ORDER_REMINDER_48_BUTTON_TEXT' => 'GO TO MY PROFILE',

	'DEVISER_NO_SHIPPED_24_SUBJECT' => 'DID YOU SHIP THE PACKAGE?',
	'DEVISER_NO_SHIPPED_24_HEADER' => 'DID YOU SHIP THE PACKAGE?',
	'DEVISER_NO_SHIPPED_24_HELLO' => 'Hello {{deviser_name}}',
	'DEVISER_NO_SHIPPED_24_TEXT' => '{{client_name}} bought the following products:',
	'DEVISER_NO_SHIPPED_24_TEXT_2' => 'Please let us know that you saw this sale. Were you able to ship the package? If so, please fill in the shipping information by going to SETTINGS - MY ORDERS, and clicking the PACKAGE WAS SHIPPED button.',
	'DEVISER_NO_SHIPPED_24_BUTTON_TEXT' => 'GO TO MY PROFILE',

	'DEVISER_NO_SHIPPED_48_SUBJECT' => 'PLEASE FILL IN THE SHIPPING INFORMATION FOR ORDER #{{order_number}}',
	'DEVISER_NO_SHIPPED_48_HEADER' => 'PLEASE FILL IN THE SHIPPING INFORMATION FOR ORDER #{{order_number}}',
	'DEVISER_NO_SHIPPED_48_HELLO' => 'Hello {{deviser_name}}',
	'DEVISER_NO_SHIPPED_48_TEXT' => '{{client_name}} bought the following products:',
	'DEVISER_NO_SHIPPED_48_TEXT_2' => 'Please fill in the shipping information for the order #(número pedido). The customer will be informed that the package was shipped only if you fill in this information.',
	'DEVISER_NO_SHIPPED_48_BUTTON_TEXT' => 'GO TO MY PROFILE',

	'DEVISER_NO_SHIPPED_72_SUBJECT' => 'IMPORTANT! SHIPPING INFORMATION MISSING FOR ORDER #{{order_number}}',
	'DEVISER_NO_SHIPPED_72_HEADER' => 'IMPORTANT! SHIPPING INFORMATION MISSING FOR ORDER #{{order_number}}',
	'DEVISER_NO_SHIPPED_72_HELLO' => 'Hello {{deviser_name}}',
	'DEVISER_NO_SHIPPED_72_TEXT' => '{{client_name}} bought the following products:',
	'DEVISER_NO_SHIPPED_72_TEXT_2' => 'At Todevise we always put our customers first, and we need to keep them updated on their orders. The shipping information for your order #(número pedido) is missing - please fill it in and click the PACKAGE WAS SHIPPED button as soon as possible.',
	'DEVISER_NO_SHIPPED_72_BUTTON_TEXT' => 'GO TO MY PROFILE',

	'CLIENT_NEW_ORDER_SUBJECT' => 'Congratulations, your purchase has been completed successfully!',
	'CLIENT_NEW_ORDER_HEADER' => 'Congratulations, your purchase has been completed successfully!',
	'CLIENT_NEW_ORDER_HELLO' => 'Hello {{client_name}}',
	'CLIENT_NEW_ORDER_TEXT' => 'You purchased the following products from Todevise:',
	'CLIENT_NEW_ORDER_TEXT_2' => 'Total price w/shipping: {order_total}€',
	'CLIENT_NEW_ORDER_TEXT_3' => 'This is not an invoice. The invoice(s) for your order will be sent by the deviser(s), and you can download them from your Todevise profile by going to SETTINGS - MY ORDERS.<br />We will send you an email when the product(s) will be shipped.',
	'CLIENT_NEW_ORDER_BUTTON_TEXT' => 'GO TO MY PROFILE',

	'CLIENT_NEW_ORDER_SHIPPED_SUBJECT' => 'Your products have been shipped!',
	'CLIENT_NEW_ORDER_SHIPPED_HEADER' => 'Your products have been shipped!',
	'CLIENT_NEW_ORDER_SHIPPED_HELLO' => 'Hello {{client_name}}',
	'CLIENT_NEW_ORDER_SHIPPED_TEXT' => 'The following products from {{deviser_name}} were shipped:',
	'CLIENT_NEW_ORDER_SHIPPED_TEXT_2' => '<p>The shipping information is the following:</p><p> - Shipping company: {{company}}</p><p> - Tracking number: {{tracking_number}}</p><p> - Tracking link: {{tracking_link}}</p>',
	'CLIENT_NEW_ORDER_SHIPPED_BUTTON_TEXT' => 'GO TO MY PROFILE',

	'TODEVISE_ORDER_72_SUBJECT' => 'Order not attended after 72 hours!',
	'TODEVISE_ORDER_72_HEADER' => 'Order not attended after 72 hours!',
	'TODEVISE_ORDER_72_HELLO' => 'Hello Todevise',
	'TODEVISE_ORDER_72_TEXT' => 'The deviser {{deviser_name}} did not click the button I’M AWARE for the order #{{order_number}}, with this products:',
	'TODEVISE_ORDER_72_TEXT_2' => '',
	'TODEVISE_ORDER_72_BUTTON_TEXT' => '',

	'TODEVISE_ORDER_96_SUBJECT' => 'Order no shipped after 96 hours!',
	'TODEVISE_ORDER_96_HEADER' => 'Order no shipped after 96 hours!',
	'TODEVISE_ORDER_96_HELLO' => 'Hello Todevise',
	'TODEVISE_ORDER_96_TEXT' => 'The deviser {{deviser_name}} did not fill in the shipping information for the order #{{order_number}}.',
	'TODEVISE_ORDER_96_TEXT_2' => '',
	'TODEVISE_ORDER_96_BUTTON_TEXT' => '',

	'UNREAD_CHAT_SUBJECT' => 'You have unread messages',
	'UNREAD_CHAT_HEADER' => 'You have unread messages',
	'UNREAD_CHAT_HELLO' => 'Hello {{receiver_name}}',
	'UNREAD_CHAT_TEXT' => 'You have unread messages from {{sender_name}} since {{message_date}}',
	'UNREAD_CHAT_TEXT_2' => '',
	'UNREAD_CHAT_BUTTON_TEXT' => 'VIEW MESSAGES',
];
