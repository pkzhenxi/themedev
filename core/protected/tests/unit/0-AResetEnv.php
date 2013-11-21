<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * User: kris
	 * Date: 2012-08-09
	 * Time: 10:34 AM
	 * To change this template use File | Settings | File Templates.
	 */

	require_once "../bootstrap.php";
	require_once "PHPUnit/Autoload.php";

class ResetEnvironment extends PHPUnit_Framework_TestCase
{
	public function testResetTestingEnvironment()
	{

		error_log("Removing previous history for testing!");

		_dbx('truncate table `xlsws_configuration');
		_dbx("INSERT INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`)
VALUES
	(1, 'Ignore line breaks in long description', 'HTML_DESCRIPTION', '0', 'If you are utilizing HTML primarily within your web long descriptions, you may want this option on', 8, 8, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(2, 'Authorized IPs For LightSpeed uploading (USE WITH CAUTION)', 'LSAUTH_IPS', '', 'List of IP Addresses (comma separated) which are allowed to upload products and download orders. NOTE: DO NOT USE THIS OPTION IF YOU DO NOT HAVE A STATIC IP ADDRESS', 16, 4, '2013-05-21 11:00:15', '2013-05-21 11:00:15', '', 0, 1, NULL),
	(3, 'Disable Cart', 'DISABLE_CART', '', 'If selected, products will only be shown but not sold', 4, 4, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(4, 'Default Language', 'LANG_CODE', 'en', '', 15, 1, '2013-05-21 11:00:15', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(5, 'Default Currency', 'CURRENCY_DEFAULT', 'USD', '', 15, 7, '2013-05-21 11:00:15', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(6, 'Languages', 'LANGUAGES', 'fr', '', 15, 2, '2013-05-21 11:00:15', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(7, 'SMTP Server', 'EMAIL_SMTP_SERVER', 'smtp.gmail.com', 'SMTP Server to send emails', 5, 11, '2013-10-14 13:16:42', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(8, 'Minimum Password Length', 'MIN_PASSWORD_LEN', '6', 'Minimum password length', 3, 5, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'INT', 0, 1, NULL),
	(9, 'Store Email', 'EMAIL_FROM', 'kris@xsilva.com', 'From which address emails will be sent', 2, 3, '2013-05-21 11:03:03', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(10, 'Store Name', 'STORE_NAME', 'LightSpeed Web Store', ' ', 2, 1, '2013-05-21 11:03:03', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(11, 'BCC Address', 'EMAIL_BCC', '', 'Enter an email address here if you would like to get BCCed on all emails sent by the webstore.', 5, 2, '2013-05-21 11:00:15', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(12, 'Email Signature', 'EMAIL_SIGNATURE', 'Thank you, LightSpeed Web Store', 'Email signature for all outgoing emails', 5, 10, '2013-05-21 11:00:15', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(13, 'Enable Wish List', 'ENABLE_WISH_LIST', '1', '', 7, 1, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(14, 'Display My Repairs (SROs) under My Account', 'ENABLE_SRO', '0', 'If your store uses SROs for repairs and uploads them to Web Store, turn this option on to allow customers to view pending repairs.', 6, 4, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(15, 'Date Format', 'DATE_FORMAT', 'm/d/Y', 'The date format to be used in store. Please see http://www.php.net/date for more information', 15, 3, '2013-05-21 11:00:15', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(16, 'Show Families on Product Menu?', 'ENABLE_FAMILIES', '1', '', 19, 13, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'ENABLE_FAMILIES', 0, 1, NULL),
	(17, 'Products Per Page', 'PRODUCTS_PER_PAGE', '12', 'Number of products per page to display in product listing or search', 8, 3, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'INT', 0, 1, NULL),
	(18, 'Products Sorting', 'PRODUCT_SORT_FIELD', '-modified', 'By which field products will sorted in result', 8, 4, '2013-05-21 11:02:49', '2013-05-21 11:00:15', 'PRODUCT_SORT', 0, 1, NULL),
	(19, 'Order From', 'ORDER_FROM', '', 'Order email address from which order notification is sent. This email address also gets the notification of the order', 5, 1, '2013-05-21 11:00:15', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(20, 'Require account creation', 'REQUIRE_ACCOUNT', '0', 'Force customers to sign up with an account before shopping? Note this some customers will abandon a forced-signup process. Customer cards are created in LightSpeed based on all orders, not dependent on customer registrations.', 3, 2, '2013-11-05 10:25:39', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(21, 'Low Inventory Threshold', 'INVENTORY_LOW_THRESHOLD', '3', 'If inventory of a product is below this quantity, Low inventory threshold title will be displayed in place of inventory value.', 11, 8, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'INT', 0, 1, NULL),
	(22, 'Available Inventory Message', 'INVENTORY_AVAILABLE', '{qty} Available', 'This text will be shown when product is available for shipping. This value will only be shown if you choose Display Inventory Level in place of actual inventory value', 11, 6, '2013-05-21 11:00:15', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(23, 'Zero or Negative Inventory Message', 'INVENTORY_ZERO_NEG_TITLE', 'This item is not currently available', 'This text will be shown in place of showing 0 or negative inventory when you choose Display Inventory Level', 11, 5, '2013-05-21 11:00:15', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(24, 'Display Empty Categories?', 'DISPLAY_EMPTY_CATEGORY', '1', 'Show categories that have no child category or images?', 8, 12, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(25, 'Display Inventory on Product Details', 'INVENTORY_DISPLAY', '1', 'Show the number of items in inventory?', 11, 1, '2013-11-05 10:27:50', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(26, 'Low Inventory Message', 'INVENTORY_LOW_TITLE', 'Hurry, only {qty} left in stock!', 'If inventory of a product is below the low threshold, this text will be shown.', 11, 7, '2013-05-21 11:00:15', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(27, 'Inventory should include Virtual Warehouses', 'INVENTORY_FIELD_TOTAL', '0', 'If selected yes, the inventory figure shown will be that of  available, reserved and inventory in warehouses. If no, only that of available in store will be shown', 11, 3, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(28, 'Non-inventoried Item Display Message', 'INVENTORY_NON_TITLE', 'Available on request', 'Title to be shown for products that are not normally stocked', 11, 9, '2013-05-21 11:00:15', '2013-05-21 11:00:15', '', 0, 1, NULL),
	(29, 'Only Ship To Defined Destinations', 'SHIP_RESTRICT_DESTINATION', '1', 'If selected yes, web shopper can only choose addresses in defined Destinations. See Destinations for more information', 25, 1, '2013-11-05 10:29:29', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(30, 'Product Grid image width', 'LISTING_IMAGE_WIDTH', '180', 'Product Listing Image Width. Comes in search or category listing page', 29, 1, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'INT', 0, 1, NULL),
	(31, 'Product Grid image height', 'LISTING_IMAGE_HEIGHT', '190', 'Product Listing Image Height. Comes in search or category listing page', 29, 2, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'INT', 0, 1, NULL),
	(32, 'Product Detail Image Width', 'DETAIL_IMAGE_WIDTH', '256', 'Product Detail Page Image Width. When the product is being viewed in the product detail page.', 29, 5, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'INT', 0, 1, NULL),
	(33, 'Product Detail Image Width', 'DETAIL_IMAGE_HEIGHT', '256', 'Product Detail Page Image Height. When the product is being viewed in the product detail page.', 29, 6, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'INT', 0, 1, NULL),
	(34, 'Product Size Label', 'PRODUCT_SIZE_LABEL', 'Size', 'Rename Size Option of LightSpeed to this', 8, 2, '2013-05-21 11:00:15', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(35, 'Product {color} Label', 'PRODUCT_COLOR_LABEL', 'Color', 'Rename {color} Option of LightSpeed to this', 8, 1, '2013-05-21 11:00:15', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(36, 'Shopping Cart image width', 'MINI_IMAGE_WIDTH', '30', 'Mini Cart Image Width. For images in the mini cart for every page.', 29, 3, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'INT', 0, 1, NULL),
	(37, 'Shopping Cart image height', 'MINI_IMAGE_HEIGHT', '30', 'Mini Cart Image Height. For images in the mini cart for every page.', 29, 4, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'INT', 0, 1, NULL),
	(38, 'Tax Inclusive Pricing', 'TAX_INCLUSIVE_PRICING', '0', 'If selected yes, all prices will be shown tax inclusive in webstore.', 15, 6, '2013-11-05 10:26:34', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(39, 'Browser Encoding', 'ENCODING', 'UTF-8', 'What character encoding would you like to use for your visitors?  UTF-8 should be normal for all users.', 15, 10, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'ENCODING', 0, 1, NULL),
	(40, 'Web Store Time Zone', 'TIMEZONE', 'America/New_York', 'The timezone in which your Web Store should display and store time.', 15, 4, '2013-05-21 11:03:00', '2013-05-21 11:00:15', 'TIMEZONE', 0, 1, NULL),
	(41, 'Enable SSL', 'ENABLE_SSL', '', 'You must have SSL/https enabled on your site to use SSL.', 16, 2, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(42, 'Number Of Hours Before Purchase Status Is Reset', 'RESET_GIFT_REGISTRY_PURCHASE_STATUS', '6', 'A visitor may add an item to cart from gift registry but may never order it. The option will reset the status to available for purchase after the specified number of hours since it was added to cart.', 7, 3, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'INT', 0, 1, NULL),
	(43, 'Currency Printing Format', 'CURRENCY_FORMAT', '%n', 'Currency will be printed in this format. Please see http://www.php.net/money_format for more details.', 15, 8, '2013-05-21 11:00:15', '2013-05-21 11:00:15', '', 0, 1, NULL),
	(44, 'Locale', 'LOCALE', 'en_US', 'Locale for your web store. See http://www.php.net/money_format for more information', 15, 1, '2013-05-21 11:00:15', '2013-05-21 11:00:15', '', 0, 1, NULL),
	(45, 'Store Phone', 'STORE_PHONE', '555-555-1212', 'Phone number displayed in email footer.', 2, 2, '2013-05-21 11:03:03', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(46, 'Default Country', 'DEFAULT_COUNTRY', '224', 'Default country for shipping or customer registration', 15, 2, '2013-11-05 10:28:01', '2013-05-21 11:00:15', 'COUNTRY', 0, 1, NULL),
	(47, 'Site Theme', 'THEME', 'brooklyn', 'The default template from templates directory to be used for Web Store', 0, 2, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'THEME', 0, 0, NULL),
	(48, 'Quote Expiry Days', 'QUOTE_EXPIRY', '30', 'Number of days before discount in quote will expire.', 4, 5, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'INT', 0, 1, NULL),
	(49, 'Cart Expiry Days', 'CART_LIFE', '30', 'Number of days before ordered/process carts are deleted from the system', 4, 6, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'INT', 0, 1, NULL),
	(50, 'Weight Unit', 'WEIGHT_UNIT', 'lb', 'What is the weight unit used in Web Store?', 25, 3, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'WEIGHT', 0, 1, NULL),
	(51, 'When a product is Out of Stock', 'INVENTORY_OUT_ALLOW_ADD', '0', 'How should system treat products currently out of stock. Note: Turn OFF the checkbox for -Only Upload Products with Available Inventory- in Tools->eCommerce.', 11, 10, '2013-11-05 10:29:52', '2013-05-21 11:00:15', 'INVENTORY_OUT_ALLOW_ADD', 0, 1, NULL),
	(52, 'Dimension Unit', 'DIMENSION_UNIT', 'in', 'What is the dimension unit used in Web Store?', 25, 4, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'DIMENSION', 0, 1, NULL),
	(53, 'LightSpeed Secure Key', 'LSKEY', '8d943379b9ef52397390a456a55cb39c', 'The secure key or password for administrative access to your lightspeed web store', 0, 1, '2013-05-21 11:03:00', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(54, 'Enter relative URL', 'HEADER_IMAGE', '/images/header/defaultheader.png', 'This path should start with /images', 27, 1, '2013-05-21 11:02:49', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(55, 'Place Web Store in Maintenance Mode', 'STORE_OFFLINE', '0', 'If selected, store will be offline.', 2, 16, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(56, 'SMTP Server Port', 'EMAIL_SMTP_PORT', '465', 'SMTP Server Port', 5, 12, '2013-10-14 13:16:42', '2013-05-21 11:00:15', 'INT', 0, 1, NULL),
	(57, 'SMTP Server Username', 'EMAIL_SMTP_USERNAME', 'kris.white@lightspeedretail.com', 'If your SMTP server requires a username, please enter it here', 5, 13, '2013-10-14 13:16:42', '2013-05-21 11:00:15', '', 0, 1, NULL),
	(58, 'SMTP Server Password', 'EMAIL_SMTP_PASSWORD', '/dRfaHVqKH2vpiR+Szle3k9oP1ekyhSpWRucknWvlJTWHb6xRtTCJ41u6VmkGeLS12ZeAuSRVBmA96qBKse0GALkxoVPb6G0NCWWIh+vf1Q=', 'If your SMTP server requires a password, please enter it here.', 5, 14, '2013-10-14 13:16:43', '2013-05-21 11:00:15', 'PASSWORD', 0, 1, NULL),
	(59, 'Number of decimal places used in tax calculation', 'TAX_DECIMAL', '2', 'Please specify the number of decimal places to be used in tax calculation. This should be the same as the number of decimal places your currency format is shown as. ', 0, 9, '2013-05-21 11:00:15', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(60, 'Allow Qty-purchase in fraction', 'QTY_FRACTION_PURCHASE', '0', 'If enabled, customers will be able to purchase items in fractions. E.g. 0.5 of an item can ordered by a customer.', 0, 10, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(61, 'Show products in Sitemap', 'SITEMAP_SHOW_PRODUCTS', '0', 'Enable this option if you want to show products in your sitemap page. If you have a very large product database, we recommend you turn off this option', 8, 14, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(62, 'Next Order Id', 'NEXT_ORDER_ID', 'WO-412006', 'What is the next order id webstore will use? This value will incremented at every order submission.', 15, 11, '2013-11-05 10:29:18', '2013-05-21 11:00:15', 'PINT', 0, 0, NULL),
	(63, 'Add taxes for shipping fees', 'SHIPPING_TAXABLE', '0', 'Enable this option if you want taxes to be calculated for shipping fees and applied to the total.', 25, 5, '2013-11-05 10:27:45', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(64, 'In Product Grid, when child product prices vary', 'MATRIX_PRICE', '3', 'How should system treat child products when different child products have different prices.', 8, 8, '2013-11-05 10:27:50', '2013-05-21 11:00:15', 'MATRIX_PRICE', 0, 1, NULL),
	(65, 'Show child products in search results', 'CHILD_SEARCH', '0', 'If you want child products from a size color matrix to show up in search results, enable this option', 8, 10, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(66, 'Security mode for outbound SMTP', 'EMAIL_SMTP_SECURITY_MODE', '0', 'Automatic based on SMTP Port, or force security.', 5, 15, '2013-10-14 13:16:43', '2013-05-21 11:00:15', 'EMAIL_SMTP_SECURITY_MODE', 0, 1, NULL),
	(67, 'Maximum Products in Slider', 'MAX_PRODUCTS_IN_SLIDER', '64', 'For a custom page, max products in slider', 8, 16, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'INT', 0, 1, NULL),
	(68, 'Database Schema Version', 'DATABASE_SCHEMA_VERSION', '300', 'Used for tracking schema changes', 0, 0, '2013-05-21 11:00:15', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(69, 'Update {color} options', 'ENABLE_COLOR_FILTER', '1', 'Enable this option to have the {color} drop-down menu populated on each size change.', 8, 7, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(71, 'Featured Keyword', 'FEATURED_KEYWORD', 'featured', 'If this keyword is one of your product keywords, the product will be featured on the Web Store homepage.', 8, 13, '2013-05-21 11:00:15', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(72, 'Debug Payment Methods', 'DEBUG_PAYMENTS', '1', 'If selected, WS log all activity for credit card processing and other payment methods.', 1, 18, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(73, 'Debug Shipping Methods', 'DEBUG_SHIPPING', '1', 'If selected, WS log all activity for shipping methods.', 1, 19, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(74, 'Reset Without Flush', 'DEBUG_RESET', '0', 'If selected, WS will not perform a flush on content tables when doing a Reset Store Products.', 1, 20, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(75, 'Show Families Menu label', 'ENABLE_FAMILIES_MENU_LABEL', 'By Manufacturer', '', 19, 14, '2013-05-21 11:00:15', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(76, 'Enabled Slashed \"Original\" Prices', 'ENABLE_SLASHED_PRICES', '2', 'If selected, will display original price slashed out and Web Price as a Sale Price.', 19, 17, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'ENABLE_SLASHED_PRICES', 0, 1, NULL),
	(77, 'ReCaptcha Public Key', 'RECAPTCHA_PUBLIC_KEY', '6LfxAtASAAAAADyBjHu6_cfVdMYLVBzgEnbTSbWi', 'Sign up for an account at http://www.google.com/recaptcha', 18, 2, '2013-05-21 11:00:15', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(78, 'ReCaptcha Private Key', 'RECAPTCHA_PRIVATE_KEY', '6LfxAtASAAAAACkJllJojWMmxvQZf2Mtt3IAMnF0', 'Sign up for an account at http://www.google.com/recaptcha', 18, 3, '2013-05-21 11:00:15', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(79, 'Captcha Style', 'CAPTCHA_STYLE', '0', 'Sign up for an account at http://www.google.com/recaptcha', 18, 1, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'CAPTCHA_STYLE', 0, 1, NULL),
	(80, 'Use Captcha on Checkout', 'CAPTCHA_CHECKOUT', '0', '', 18, 4, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'CAPTCHA_CHECKOUT', 0, 1, NULL),
	(81, 'Use Captcha on Contact Us', 'CAPTCHA_CONTACTUS', '0', '', 18, 5, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'CAPTCHA_CONTACTUS', 0, 1, NULL),
	(82, 'Use Captcha on Registration', 'CAPTCHA_REGISTRATION', '0', '', 18, 6, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'CAPTCHA_REGISTRATION', 0, 1, NULL),
	(83, 'Force AUTH PLAIN Authentication', 'EMAIL_SMTP_AUTH_PLAIN', '0', 'Force plain text password in rare circumstances', 5, 16, '2013-10-14 13:16:43', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(84, 'Deduct Pending Orders from Available Inventory', 'INVENTORY_RESERVED', '1', 'This option will calculate Qty Available minus Pending Orders. Turning on Upload Orders in LightSpeed Tools->eCommerce->Documents is required to make this feature work properly.', 11, 4, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(85, 'LightSpeed Hosting', 'LIGHTSPEED_HOSTING', '0', 'Flag which indicates site is hosted by LightSpeed', 0, 0, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(86, 'Require login to view prices', 'PRICE_REQUIRE_LOGIN', '0', 'System will not display prices to anyone not logged in.', 3, 3, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(87, 'Last timestamp uploader ran', 'UPLOADER_TIMESTAMP', '0', 'Internal', 0, 0, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'NULL', 0, 1, NULL),
	(88, 'Google Analytics Code (format: UA-00000000-0)', 'GOOGLE_ANALYTICS', '', 'Google Analytics code for tracking', 20, 1, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'NULL', 0, 1, NULL),
	(89, 'Store Tagline', 'STORE_TAGLINE', 'Amazing products available to order online!', 'Used as default for Title bar for home page', 2, 4, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'NULL', 0, 1, NULL),
	(90, 'Log Rotate Days', 'LOG_ROTATE_DAYS', '30', 'How many days System Log should be retained.', 1, 30, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'INT', 0, 1, NULL),
	(91, 'ReCaptcha Theme', 'CAPTCHA_THEME', 'white', '', 18, 4, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'CAPTCHA_THEME', 0, 1, NULL),
	(92, 'Send Receipts to Customers', 'EMAIL_SEND_CUSTOMER', '1', '', 24, 1, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(93, 'Send Order Alerts to Store', 'EMAIL_SEND_STORE', '1', 'Email store on every order', 24, 2, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(94, 'Customer Email Subject Line', 'EMAIL_SUBJECT_CUSTOMER', '{storename} Order Notification {orderid}', 'Configure Email Subject line with variables for Customer Email', 24, 10, '2013-05-21 11:01:02', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(95, 'Owner Email Subject Line', 'EMAIL_SUBJECT_OWNER', '{storename} Order Notification {orderid}', 'Configure Email Subject line with variables for Owner email', 24, 11, '2013-05-21 11:01:02', '2013-05-21 11:00:15', NULL, 0, 1, NULL),
	(96, 'Show Product Code on Product Details', 'SHOW_TEMPLATE_CODE', '1', 'Determines if the Product Code should be visible', 19, 28, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(97, 'Show Sharing Buttons on Product Details', 'SHOW_SHARING', '1', 'Show Sharing buttons such as Facebook and Pinterest', 19, 29, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(98, 'Use Product Codes in Product URLs', 'SEO_URL_CODES', '0', 'If your Product Codes are important (such as model numbers), this will include them when making SEO formatted URLs. If you generate your own Product Codes that are only internal, you can leave this off.', 21, 1, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'BOOL', 0, 1, NULL),
	(99, 'Google AdWords ID (format: 000000000)', 'GOOGLE_ADWORDS', '', 'Google AdWords Conversion ID (found in line \'var google_conversion_id\' when viewing code from Google AdWords setup)', 20, 2, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'NULL', 0, 1, NULL),
	(100, 'Google Site Verify ID (format: _PRasdu8f9a8F9A etc)', 'GOOGLE_VERIFY', '', 'Google Verify Code (found in google-site-verification meta header)', 20, 3, '2013-05-21 11:00:15', '2013-05-21 11:00:15', 'NULL', 0, 1, NULL),
	(101, 'Product Title format', 'SEO_PRODUCT_TITLE', '{description} : {storename}', 'Which elements appear in the Title', 22, 2, '2013-05-21 11:01:02', '2013-05-21 11:00:15', 'NULL', 0, 1, NULL),
	(102, 'Product Meta Description format', 'SEO_PRODUCT_DESCRIPTION', '{longdescription}', 'Which elements appear in the Meta Description', 22, 3, '2013-05-21 11:01:02', '2013-05-21 11:00:15', 'NULL', 0, 1, NULL),
	(103, 'Category pages Title format', 'SEO_CATEGORY_TITLE', '{name} : {storename}', 'Which elements appear in the title of a category page', 23, 1, '2013-05-21 11:01:02', '2013-05-21 11:00:16', 'NULL', 0, 1, NULL),
	(104, 'Custom pages Title format', 'SEO_CUSTOMPAGE_TITLE', '{name} : {storename}', 'Which elements appear in the title of a custom page', 23, 2, '2013-05-21 11:01:02', '2013-05-21 11:00:16', 'NULL', 0, 1, NULL),
	(105, 'Category Page Image Width', 'CATEGORY_IMAGE_WIDTH', '180', 'if using a Category Page image', 29, 7, '2013-05-21 11:00:16', '2013-05-21 11:00:16', 'INT', 0, 1, NULL),
	(106, 'Category Page Image Width', 'CATEGORY_IMAGE_HEIGHT', '180', 'if using a Category Page image', 29, 8, '2013-05-21 11:00:16', '2013-05-21 11:00:16', 'INT', 0, 1, NULL),
	(107, 'Preview Thumbnail (Product Detail Page) Width', 'PREVIEW_IMAGE_WIDTH', '60', 'Preview Thumbnail image', 29, 9, '2013-11-05 10:27:50', '2013-05-21 11:00:16', 'INT', 0, 1, NULL),
	(108, 'Preview Thumbnail (Product Detail Page) Height', 'PREVIEW_IMAGE_HEIGHT', '60', 'Preview Thumbnail image', 29, 10, '2013-11-05 10:27:50', '2013-05-21 11:00:16', 'INT', 0, 1, NULL),
	(109, 'Slider Image Width', 'SLIDER_IMAGE_WIDTH', '90', 'Slider on custom pages', 29, 11, '2013-05-21 11:00:16', '2013-05-21 11:00:16', 'INT', 0, 1, NULL),
	(110, 'Slider Image Height', 'SLIDER_IMAGE_HEIGHT', '90', 'Slider on custom pages', 29, 12, '2013-05-21 11:00:16', '2013-05-21 11:00:16', 'INT', 0, 1, NULL),
	(111, 'Image Format', 'IMAGE_FORMAT', 'jpg', 'Use .jpg or .png format for images. JPG files are smaller but slightly lower quality. PNG is higher quality and supports transparency, but has a larger file size.', 17, 18, '2013-05-21 11:00:17', '2013-05-21 11:00:17', 'IMAGE_FORMAT', 0, 1, NULL),
	(112, 'Display Image on Category Page (when set)', 'ENABLE_CATEGORY_IMAGE', '0', 'Requires a defined Category image under SEO settings', 17, 13, '2013-05-21 11:00:17', '2013-05-21 11:00:17', 'BOOL', 0, 1, NULL),
	(113, 'Require Billing and Shipping Address to Match', 'SHIP_SAME_BILLSHIP', '0', 'Locks the Shipping and Billing are same checkbox to not allow separate shipping address.', 25, 2, '2013-05-21 11:00:17', '2013-05-21 11:00:17', 'BOOL', 0, 1, NULL),
	(114, 'Debug SOAP Calls', 'DEBUG_LS_SOAP_CALL', '0', 'Debug', 1, 17, '2013-05-21 11:00:17', '2013-05-21 11:00:17', 'BOOL', 0, 1, NULL),
	(115, 'Store Address', 'STORE_ADDRESS1', '123 Main St.', 'Address line 1', 2, 5, '2013-05-21 11:03:03', '2013-05-21 11:00:17', 'NULL', 0, 1, NULL),
	(116, 'Store City, State, Postal', 'STORE_ADDRESS2', 'Anytown, NY 12345', 'Address line 2', 2, 6, '2013-05-21 11:03:03', '2013-05-21 11:00:17', 'NULL', 0, 1, NULL),
	(117, 'Store Operating Hours', 'STORE_HOURS', 'MON-FRI: 9AM-9PM SAT: 11AM-6PM SUN: CLOSED', 'Store hours. Use &lt;br&gt; tag to create two lines if desired.', 2, 7, '2013-05-21 11:03:03', '2013-05-21 11:00:17', 'NULL', 0, 1, NULL),
	(118, 'Theme {color} scheme', 'CHILD_THEME', 'light', 'If supported, changable colo(u)rs for template files.', 0, 3, '2013-05-21 11:00:17', '2013-05-21 11:00:17', 'CHILD_THEME', 0, 0, NULL),
	(119, 'Product Codes are Manufacturer Part Numbers in Google Shopping', 'GOOGLE_MPN', '0', 'If your Product Codes are Manufacturer Part Numbers, turn this on to apply this to Google Shopping feed.', 20, 4, '2013-05-21 11:00:17', '2013-05-21 11:00:17', 'BOOL', 0, 1, NULL),
	(120, 'Wishlist Email Subject Line', 'EMAIL_SUBJECT_WISHLIST', '{storename} Wishlist for {customername}', 'Configure Email Subject line with variables for Customer Email', 24, 10, '2013-05-21 11:01:02', '2013-05-21 11:00:17', NULL, 0, 1, NULL),
	(121, 'Facebook App ID', 'FACEBOOK_APPID', '', 'Create Facebook AppID', 26, 1, '2013-05-21 11:00:17', '2013-05-21 11:00:17', NULL, 0, 1, NULL),
	(122, 'Share Cart Email Subject Line', 'EMAIL_SUBJECT_CART', '{storename} Cart for {customername}', 'Configure Email Subject line with variables for Customer Email', 24, 10, '2013-05-21 11:01:02', '2013-05-21 11:00:17', NULL, 0, 1, NULL),
	(123, 'System Logging', 'DEBUG_LOGGING', 'info', ' ', 1, 21, '2013-11-05 10:25:39', '2013-05-21 11:00:17', 'LOGGING', 0, 1, NULL),
	(124, 'Delivery Speed format', 'SHIPPING_FORMAT', '{label} ({price})', 'Formatting for Delivery Speed. The variables {label} and {price} can be used.', 25, 5, '2013-05-21 11:00:17', '2013-05-21 11:00:17', NULL, 0, 1, NULL),
	(128, 'Photo Processor', 'CEventPhoto', 'wsphoto', 'Component that handles photos', 28, 1, '2013-05-21 11:00:52', NULL, 'CEventPhoto', 0, 1, NULL),
	(129, 'Menu Processor', 'PROCESSOR_MENU', 'wsmenu', 'Component that handles menu display', 28, 2, '2013-05-21 11:00:52', NULL, 'PROCESSOR_MENU', 0, 1, NULL),
	(130, 'Language Menu shows', 'PROCESSOR_LANGMENU', 'wslanglinks', 'Component that handles language menu display', 15, 2, '2013-05-21 11:00:52', NULL, 'PROCESSOR_LANGMENU', 0, 1, NULL),
	(132, 'Products Per Row', 'PRODUCTS_PER_ROW', '3', 'Products per row on grid. (Note this number must be divisible evenly into 12. That\'s why \'5\' is missing.)', 8, 3, '2013-05-21 11:00:53', NULL, 'PRODUCTS_PER_ROW', 0, 1, NULL),
	(133, 'Home page', 'HOME_PAGE', '*products', 'Home page viewers should first see', 19, 12, '2013-05-21 11:00:56', NULL, 'HOME_PAGE', 0, 1, NULL),
	(134, 'Use Short Description', 'USE_SHORT_DESC', '1', 'Home page viewers should first see', 19, 13, '2013-05-21 11:00:56', NULL, 'BOOL', 0, 1, NULL),
	(135, 'Facebook Secret Key', 'FACEBOOK_SECRET', '', 'Secret Key found with your App ID', 26, 2, '2013-05-21 11:00:56', NULL, NULL, 0, 0, NULL),
	(136, 'Show Facebook Comments on Product details', 'FACEBOOK_COMMENTS', '0', '', 26, 3, '2013-05-21 11:00:56', NULL, 'BOOL', 0, 0, NULL),
	(137, 'Show Post to Wall after checkout', 'FACEBOOK_CHECKOUT', '0', '', 26, 4, '2013-05-21 11:00:56', NULL, 'BOOL', 0, 0, NULL),
	(138, 'Share to Wall Caption', 'FACEBOOK_WALL_CAPTION', 'I found some great deals at {storename}!', '', 26, 5, '2013-05-21 11:00:56', NULL, NULL, 0, 0, NULL),
	(139, 'Share to Wall Button', 'FACEBOOK_WALL_PUBLISH', 'Post to your wall', '', 26, 7, '2013-05-21 11:00:56', NULL, NULL, 0, 0, NULL),
	(140, 'Jpg Image Quality (1 to 100)', 'IMAGE_QUALITY', '75', 'Compression for JPG images', 17, 15, '2013-05-21 11:00:57', NULL, NULL, 0, 1, 1),
	(141, 'Jpg Sharpen (1 to 50)', 'IMAGE_SHARPEN', '25', 'Sharpening for JPG images', 17, 16, '2013-05-21 11:00:57', NULL, NULL, 0, 1, 1),
	(142, 'Image Background {color} Fill', 'IMAGE_BACKGROUND', '#FFFFFF', 'Optional image background {color} (#HEX)', 17, 20, '2013-05-21 11:00:57', NULL, NULL, 1, 1, 0),
	(143, 'Installed', 'INSTALLED', '1', ' ', 0, 0, '2013-05-21 11:03:07', NULL, 'BOOL', 0, 1, NULL),
	(144, 'Use Categories in Product URLs', 'SEO_URL_CATEGORIES', '0', 'This will include the Category path when creating the SEO formatted URLs.', 21, 2, '2013-11-05 10:25:39', NULL, 'BOOL', 0, 1, NULL),
	(145, 'Use Quantity Entry Blank', 'SHOW_QTY_ENTRY', '0', 'If enabled, show freeform qty entry for Add To Cart', 19, 20, '2013-05-21 11:00:57', NULL, 'BOOL', 1, 1, NULL),
	(146, 'After adding item to cart', 'AFTER_ADD_CART', '0', 'What should site do after shopper adds item to cart', 4, 5, '2009-04-06 10:34:34', '2009-04-06 10:34:34', 'AFTER_ADD_CART', 0, 1, NULL),
	(147, 'Template Viewset', 'VIEWSET', 'cities', 'The master design set for themes.', 0, 1, '2013-05-21 11:02:49', NULL, 'VIEWSET', 0, 0, 1),
	(148, 'Enable Language Menu', 'LANG_MENU', '1', 'Show language switch menu on website.', 15, 1, '2013-11-05 10:27:47', NULL, 'BOOL', 0, 0, 1),
	(149, 'Add missing translations while navigating', 'LANG_MISSING', '0', 'For creating new translations. Do NOT leave this option on, it will slow your server down.', 15, 3, '2013-05-21 11:02:49', NULL, 'BOOL', 0, 0, 1),
	(150, 'Moderate Customer Registration', 'MODERATE_REGISTRATION', '0', 'If enabled, customer registrations will need to be moderated before they are approved.', 3, 1, '2013-05-21 11:02:49', NULL, 'BOOL', 0, 0, 1),
	(151, 'Language Options', 'LANG_OPTIONS', 'en:English,fr:français', '', 0, 0, '2013-05-21 11:02:49', NULL, NULL, 0, 0, 1);
");


		error_log("Updating to latest db schema");
		$sanity=0;
		do {
			$url = 'http://'.$_SERVER['testini']['SERVER_NAME'].'/index-test.php/admin/upgrade/databaseinstall';
			$response = file_get_contents($url);
			$sanity++;
			error_log($response);
		} while (stripos($response,'Applying latest database changes..."')===false && $sanity<500);


		_dbx('SET FOREIGN_KEY_CHECKS=0;
			TRUNCATE TABLE `xlsws_cart_messages`;
			TRUNCATE TABLE `xlsws_cart_item`;
			TRUNCATE TABLE `xlsws_cart`;
			TRUNCATE TABLE `xlsws_cart_shipping`;
			TRUNCATE TABLE `xlsws_cart_payment`;
			TRUNCATE TABLE `xlsws_customer`;
			TRUNCATE TABLE `xlsws_customer_address`;
			TRUNCATE TABLE `xlsws_sessions`;
			TRUNCATE TABLE `xlsws_destination`;
			TRUNCATE TABLE `xlsws_stringsource`;
			TRUNCATE TABLE `xlsws_stringtranslate`;
			TRUNCATE TABLE `xlsws_log`;
			TRUNCATE TABLE `xlsws_modules`;
			TRUNCATE TABLE `xlsws_category_integration`;
			SET FOREIGN_KEY_CHECKS=1;
		');

		_dbx("INSERT INTO `xlsws_modules` (`id`, `active`, `module`, `category`, `version`, `name`, `sort_order`, `configuration`, `modified`, `created`)
VALUES
	(42, 1, 'wsborderlookup', 'sidebar', NULL, NULL, 2, NULL, '2013-04-16 15:16:48', NULL),
	(49, 1, 'cashondelivery', 'payment', 1, 'Cash on Delivery', 14, 'a:1:{s:5:\"label\";s:16:\"Cash On Delivery\";}', '2013-04-16 15:38:26', NULL),
	(53, 1, 'wsbwishlist', 'sidebar', NULL, NULL, 3, NULL, '2013-04-16 15:16:48', NULL),
	(57, 1, 'iups', 'shipping', NULL, NULL, 13, 'a:14:{s:5:\"label\";s:4:\"IUPS\";s:8:\"username\";s:10:\"benappelle\";s:8:\"password\";s:9:\"sfarim420\";s:9:\"accesskey\";s:16:\"9C8F7D78B4A0B910\";s:14:\"originpostcode\";s:7:\"k0k 2t0\";s:13:\"origincountry\";s:2:\"CA\";s:11:\"originstate\";s:2:\"ON\";s:14:\"regionservices\";s:14:\"ups_service_ca\";s:8:\"ratecode\";s:2:\"01\";s:22:\"customerclassification\";s:2:\"04\";s:7:\"package\";s:2:\"CP\";s:15:\"restrictcountry\";N;s:7:\"product\";s:8:\"SHIPPING\";s:6:\"markup\";s:1:\"3\";}', '2013-04-16 15:16:48', NULL),
	(61, 1, 'axia', 'payment', NULL, NULL, 15, 'a:6:{s:5:\"label\";s:36:\"Credit card (Visa, Mastercard, Amex)\";s:10:\"source_key\";s:32:\"tBLbnzONj82GH1kWcBCqfu7b6DZoksqT\";s:14:\"source_key_pin\";s:0:\"\";s:4:\"live\";s:4:\"test\";s:15:\"restrictcountry\";s:4:\"null\";s:17:\"ls_payment_method\";s:11:\"Credit Card\";}', '2013-11-05 09:48:56', NULL),
	(64, 1, 'paypal', 'payment', 1, 'PayPal', 9, 'a:4:{s:5:\"label\";s:6:\"PayPal\";s:5:\"login\";s:36:\"kris.w_1331482444_biz@eightounce.com\";s:4:\"live\";s:4:\"test\";s:17:\"ls_payment_method\";s:11:\"Credit Card\";}', '2013-11-05 09:48:56', NULL),
	(65, 1, 'storepickup', 'shipping', NULL, NULL, 21, 'a:4:{s:5:\"label\";s:12:\"Store Pickup\";s:3:\"msg\";s:71:\"Please quote order ID %s with photo ID at the reception for collection.\";s:7:\"product\";s:8:\"SHIPPING\";s:6:\"markup\";s:1:\"0\";}', '2013-04-16 15:16:48', NULL),
	(66, 1, 'authorizedotnetaim', 'payment', 1, 'Authorize.Net', 16, 'a:7:{s:5:\"label\";s:34:\"Authorize.Net Advanced Integration\";s:5:\"login\";s:9:\"6Cy3vg3DT\";s:9:\"trans_key\";s:16:\"3msw2846jXT6pVEu\";s:4:\"live\";s:4:\"test\";s:3:\"ccv\";i:1;s:11:\"specialcode\";s:0:\"\";s:17:\"ls_payment_method\";s:11:\"Credit Card\";}', '2013-04-16 15:38:26', NULL),
	(67, 1, 'beanstreamaim', 'payment', 1, 'Beanstream (US/CAN)', 17, 'a:4:{s:5:\"label\";s:31:\"Beanstream Advanced Integration\";s:5:\"login\";s:9:\"263770000\";s:15:\"restrictcountry\";s:4:\"null\";s:17:\"ls_payment_method\";s:11:\"Credit Card\";}', '2013-11-05 09:48:56', NULL),
	(68, 1, 'beanstreamsim', 'payment', 1, 'Beanstream (US/CAN)', 18, 'a:4:{s:5:\"label\";s:14:\"Beanstream SIM\";s:5:\"login\";s:9:\"198870000\";s:7:\"md5hash\";s:0:\"\";s:17:\"ls_payment_method\";s:11:\"Credit Card\";}', '2013-04-16 15:38:26', NULL),
	(71, 1, 'tieredshipping', 'shipping', NULL, NULL, 16, 'a:4:{s:5:\"label\";s:19:\"Tier Based Shipping\";s:9:\"tierbased\";s:5:\"price\";s:15:\"restrictcountry\";N;s:7:\"product\";s:8:\"SHIPPING\";}', '2013-04-16 15:16:48', NULL),
	(73, 1, 'authorizedotnetsim', 'payment', 1, 'Authorize.Net', 19, 'a:6:{s:5:\"label\";s:12:\"Auth.net SIM\";s:5:\"login\";s:9:\"6Cy3vg3DT\";s:9:\"trans_key\";s:16:\"3msw2846jXT6pVEu\";s:7:\"md5hash\";s:7:\"hashish\";s:4:\"live\";s:4:\"test\";s:17:\"ls_payment_method\";s:15:\"Web Credit Card\";}', '2013-04-16 15:38:26', NULL),
	(74, 1, 'wsbsidebar', 'sidebar', NULL, NULL, 4, NULL, '2013-04-16 15:16:48', NULL),
	(76, 1, 'fedex', 'shipping', NULL, NULL, 5, 'a:17:{s:5:\"label\";s:5:\"FedEx\";s:9:\"accnumber\";s:9:\"294946276\";s:11:\"meternumber\";s:9:\"102942395\";s:12:\"securitycode\";s:25:\"st0xxm7g6jxGh2czWs3TIWOmF\";s:7:\"authkey\";s:16:\"BzUmPf8YjAvWasAN\";s:10:\"originadde\";s:15:\"1409 Mullins Dr\";s:10:\"origincity\";s:5:\"Plano\";s:14:\"originpostcode\";s:5:\"75025\";s:13:\"origincountry\";s:3:\"224\";s:11:\"originstate\";s:2:\"56\";s:9:\"packaging\";s:14:\"YOUR_PACKAGING\";s:8:\"ratetype\";s:10:\"RATED_LIST\";s:7:\"customs\";s:12:\"CLEARANCEFEE\";s:13:\"offerservices\";a:6:{i:0;s:15:\"FIRST_OVERNIGHT\";i:1;s:18:\"STANDARD_OVERNIGHT\";i:2;s:18:\"PRIORITY_OVERNIGHT\";i:3;s:22:\"INTERNATIONAL_PRIORITY\";i:4;s:21:\"INTERNATIONAL_ECONOMY\";i:5;s:12:\"FEDEX_GROUND\";}s:15:\"restrictcountry\";s:4:\"null\";s:6:\"markup\";s:1:\"3\";s:7:\"product\";s:8:\"SHIPPING\";}', '2013-11-05 09:48:55', NULL),
	(80, 1, 'worldpaysim', 'payment', 1, 'Worldpay', 20, 'a:4:{s:5:\"label\";s:8:\"WorldPay\";s:5:\"login\";s:6:\"123123\";s:4:\"live\";s:4:\"test\";s:17:\"ls_payment_method\";s:15:\"Web Credit Card\";}', '2013-04-16 15:38:26', NULL),
	(84, 1, 'merchantware', 'payment', NULL, NULL, 21, 'a:5:{s:5:\"label\";s:12:\"Merchantware\";s:4:\"name\";s:6:\"Xsilva\";s:7:\"site_id\";s:8:\"6VBYB5BC\";s:9:\"trans_key\";s:29:\"DW8YD-9C77X-AZP81-AN9M8-AXGX3\";s:17:\"ls_payment_method\";s:11:\"Credit Card\";}', '2013-11-05 09:48:56', NULL),
	(86, 1, 'phoneorder', 'payment', 1, 'Phone Order', 22, 'a:3:{s:5:\"label\";s:11:\"Phone Order\";s:5:\"phone\";s:47:\"Please call us on ith your credit card details.\";s:17:\"ls_payment_method\";s:11:\"Credit Card\";}', '2013-04-16 15:38:26', NULL),
	(93, 1, 'canadapost', 'shipping', NULL, NULL, 23, 'a:7:{s:5:\"label\";s:11:\"Canada Post\";s:14:\"originpostcode\";s:7:\"V5T 3E2\";s:3:\"cpc\";s:17:\"CPC_DUNBAR_CYCLES\";s:13:\"offerservices\";a:8:{i:0;s:7:\"Regular\";i:1;s:10:\"Xpresspost\";i:2;s:16:\"Priority Courier\";i:3;s:9:\"Expedited\";i:4;s:14:\"Xpresspost USA\";i:5;s:21:\"Expedited US Business\";i:6;s:17:\"Small Packets Air\";i:7;s:21:\"Small Packets Surface\";}s:15:\"restrictcountry\";s:4:\"null\";s:6:\"markup\";s:1:\"3\";s:7:\"product\";s:8:\"SHIPPING\";}', '2013-11-05 09:48:55', NULL),
	(94, 1, 'paypalpro', 'payment', 1, 'PayPal Pro', 25, 'a:9:{s:5:\"label\";s:10:\"Paypal Pro\";s:12:\"api_username\";s:1:\"k\";s:12:\"api_password\";s:1:\"k\";s:13:\"api_signature\";s:1:\"k\";s:4:\"live\";s:4:\"test\";s:15:\"api_username_sb\";s:41:\"kris.w_1331482444_biz_api1.eightounce.com\";s:15:\"api_password_sb\";s:10:\"1331482483\";s:16:\"api_signature_sb\";s:56:\"An5ns1Kso7MWUdW4ErQKJJJ4qi4-AwneZA03eTr3ififGIk-YlERzbtu\";s:17:\"ls_payment_method\";s:15:\"Web Credit Card\";}', '2013-04-16 15:38:26', NULL),
	(95, 1, 'freeshipping', 'shipping', NULL, NULL, 9, 'a:8:{s:5:\"label\";s:13:\"Free shipping\";s:4:\"rate\";s:2:\"15\";s:9:\"startdate\";s:0:\"\";s:7:\"enddate\";s:0:\"\";s:9:\"promocode\";s:0:\"\";s:13:\"qty_remaining\";s:0:\"\";s:15:\"restrictcountry\";N;s:7:\"product\";s:8:\"SHIPPING\";}', '2013-04-16 15:16:48', NULL),
	(97, 1, 'purchaseorder', 'payment', 1, 'Purchase Order', 24, 'a:2:{s:5:\"label\";s:19:\"Pay with Membership\";s:17:\"ls_payment_method\";s:14:\"Purchase Order\";}', '2013-04-16 15:38:26', NULL),
	(102, 1, 'ups', 'shipping', NULL, NULL, 25, 'a:16:{s:5:\"label\";s:3:\"UPS\";s:4:\"mode\";s:3:\"UPS\";s:13:\"origincountry\";s:3:\"224\";s:11:\"originstate\";s:2:\"56\";s:8:\"username\";s:0:\"\";s:8:\"password\";s:0:\"\";s:9:\"accesskey\";s:0:\"\";s:22:\"customerclassification\";s:2:\"04\";s:14:\"originpostcode\";s:5:\"78759\";s:8:\"ratecode\";s:20:\"Regular Daily Pickup\";s:7:\"package\";s:2:\"CP\";s:14:\"regionservices\";N;s:13:\"offerservices\";a:3:{i:0;s:2:\"03\";i:1;s:2:\"11\";i:2;s:2:\"12\";}s:15:\"restrictcountry\";s:4:\"null\";s:6:\"markup\";s:1:\"3\";s:7:\"product\";s:8:\"SHIPPING\";}', '2013-11-05 09:48:55', NULL),
	(104, 1, 'usps', 'shipping', NULL, NULL, 27, 'a:7:{s:5:\"label\";s:4:\"USPS\";s:14:\"originpostcode\";s:5:\"11222\";s:8:\"username\";s:12:\"786ALTER3964\";s:13:\"offerservices\";a:5:{i:0;s:12:\"Express Mail\";i:1;s:13:\"Priority Mail\";i:2;s:13:\"Standard Post\";i:3;s:26:\"Express Mail International\";i:4;s:27:\"Priority Mail International\";}s:15:\"restrictcountry\";s:4:\"null\";s:6:\"markup\";s:1:\"3\";s:7:\"product\";s:8:\"SHIPPING\";}', '2013-11-05 09:48:55', NULL),
	(121, 0, 'brooklyn', 'template', 3, 'Brooklyn', NULL, 'a:15:{s:17:\"PRODUCTS_PER_PAGE\";s:2:\"12\";s:19:\"LISTING_IMAGE_WIDTH\";s:3:\"180\";s:20:\"LISTING_IMAGE_HEIGHT\";s:3:\"190\";s:18:\"DETAIL_IMAGE_WIDTH\";s:3:\"256\";s:19:\"DETAIL_IMAGE_HEIGHT\";s:3:\"256\";s:16:\"MINI_IMAGE_WIDTH\";s:2:\"30\";s:17:\"MINI_IMAGE_HEIGHT\";s:2:\"30\";s:20:\"CATEGORY_IMAGE_WIDTH\";s:3:\"180\";s:21:\"CATEGORY_IMAGE_HEIGHT\";s:3:\"180\";s:19:\"PREVIEW_IMAGE_WIDTH\";s:2:\"30\";s:20:\"PREVIEW_IMAGE_HEIGHT\";s:2:\"30\";s:18:\"SLIDER_IMAGE_WIDTH\";s:2:\"90\";s:19:\"SLIDER_IMAGE_HEIGHT\";s:2:\"90\";s:11:\"CHILD_THEME\";s:5:\"light\";s:16:\"IMAGE_BACKGROUND\";s:7:\"#FFFFFF\";}', '2013-11-05 09:48:59', NULL),
	(122, 1, 'flatrate', 'shipping', NULL, NULL, 28, 'a:5:{s:5:\"label\";s:18:\"Flat rate shipping\";s:3:\"per\";s:4:\"item\";s:4:\"rate\";i:1;s:15:\"restrictcountry\";N;s:7:\"product\";s:8:\"SHIPPING\";}', '2013-11-05 09:48:51', NULL),
	(123, 1, 'australiapost', 'shipping', NULL, NULL, 29, 'a:7:{s:5:\"label\";s:14:\"Australia Post\";s:7:\"api_key\";s:36:\"8d23792c-a296-4aaf-ac82-85a234844907\";s:14:\"originpostcode\";s:4:\"4000\";s:13:\"offerservices\";a:12:{i:0;s:18:\"AUS_PARCEL_REGULAR\";i:1;s:30:\"AUS_PARCEL_REGULAR_SATCHEL_3KG\";i:2;s:18:\"AUS_PARCEL_EXPRESS\";i:3;s:30:\"AUS_PARCEL_EXPRESS_SATCHEL_3KG\";i:4;s:25:\"INTL_SERVICE_ECI_PLATINUM\";i:5;s:18:\"INTL_SERVICE_ECI_M\";i:6;s:18:\"INTL_SERVICE_ECI_D\";i:7;s:16:\"INTL_SERVICE_EPI\";i:8;s:16:\"INTL_SERVICE_PTI\";i:9;s:16:\"INTL_SERVICE_RPI\";i:10;s:21:\"INTL_SERVICE_AIR_MAIL\";i:11;s:21:\"INTL_SERVICE_SEA_MAIL\";}s:15:\"restrictcountry\";s:4:\"null\";s:6:\"markup\";s:1:\"4\";s:7:\"product\";s:8:\"SHIPPING\";}', '2013-11-05 09:48:55', NULL),
	(124, 0, 'destinationshipping', 'shipping', NULL, NULL, 30, 'a:5:{s:5:\"label\";s:20:\"Destination Shipping\";s:3:\"per\";s:4:\"item\";s:13:\"offerservices\";s:16:\"what destination\";s:15:\"restrictcountry\";s:4:\"null\";s:7:\"product\";s:8:\"SHIPPING\";}', '2013-11-05 09:50:27', NULL),
	(126, 1, 'wsphoto', 'CEventPhoto', 1, 'Web Store Internal', 1, NULL, '2013-04-16 15:16:42', NULL),
	(127, 1, 'wsmailchimp', 'CEventCustomer', 1, 'MailChimp', 1, 'a:2:{s:7:\"api_key\";s:36:\"7ace7f2ad23a4a0f748dc95e945a103e-us5\";s:4:\"list\";s:9:\"Web Store\";}', '2013-04-23 14:18:52', NULL),
	(129, 0, 'cheque', 'payment', 1, 'Check', NULL, 'a:3:{s:5:\"label\";s:6:\"Cheque\";s:15:\"restrictcountry\";N;s:17:\"ls_payment_method\";s:15:\"Web Credit Card\";}', '2013-04-16 15:38:26', '2013-04-16 15:23:19'),
	(130, 0, 'ewayaim', 'payment', 1, 'eWAY CVN Australia', NULL, 'a:4:{s:5:\"label\";s:4:\"eWay\";s:5:\"login\";s:8:\"87654321\";s:4:\"live\";s:4:\"test\";s:17:\"ls_payment_method\";s:15:\"Web Credit Card\";}', '2013-11-05 09:48:56', '2013-04-16 15:23:19'),
	(131, 0, 'moneris', 'payment', 1, 'Moneris', NULL, 'a:9:{s:5:\"label\";s:7:\"Moneris\";s:8:\"store_id\";s:7:\"moneris\";s:9:\"api_token\";s:6:\"hurgle\";s:4:\"live\";s:4:\"test\";s:3:\"ccv\";s:1:\"1\";s:3:\"avs\";s:1:\"1\";s:11:\"specialcode\";N;s:15:\"restrictcountry\";s:4:\"null\";s:17:\"ls_payment_method\";s:15:\"Web Credit Card\";}', '2013-11-05 09:48:56', '2013-04-16 15:23:19'),
	(132, 1, 'wsamazon', 'CEventProduct,CEventPhoto,CEventOrder', 1, 'Amazon MWS', 1, 'a:6:{s:18:\"AMAZON_MERCHANT_ID\";s:13:\"ABZUSRJL7VB69\";s:24:\"AMAZON_MWS_ACCESS_KEY_ID\";s:20:\"AKIAJMEUPZC75EQ7ERYQ\";s:21:\"AMAZON_MARKETPLACE_ID\";s:13:\"ATVPDKIKX0DER\";s:28:\"AMAZON_MWS_SECRET_ACCESS_KEY\";s:40:\"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\";s:7:\"product\";s:8:\"SHIPPING\";s:17:\"ls_payment_method\";s:15:\"Web Credit Card\";}', '2013-04-22 13:36:13', NULL),
	(133, 0, 'brooklyn2', 'theme', 0, 'brooklyn2', NULL, '', '2013-11-05 09:48:59', '2013-11-05 09:48:59'),
	(134, 0, 'freearch', 'theme', 1, 'Freearch', NULL, '', '2013-11-05 09:48:59', '2013-11-05 09:48:59'),
	(135, 0, 'hebocopy', 'theme', 0, 'hebocopy', NULL, '', '2013-11-05 09:48:59', '2013-11-05 09:48:59'),
	(136, 0, 'wscloud', 'CEventOrder,CEventPhoto', 1, 'Cloud', 1, 'a:1:{s:9:\"topic_arn\";s:49:\"arn:aws:sns:us-west-2:927364065875:webstore_order\";}', '2013-10-24 14:37:07', NULL);

");

		Modules::model()->deleteAll('module = "wscloud"');
		_dbx("INSERT INTO `xlsws_modules` (`id`, `active`, `module`, `category`, `version`, `name`, `sort_order`, `configuration`, `modified`, `created`)
VALUES
	(NULL, 0, 'wscloud', 'CEventOrder,CEventPhoto', 1, 'Cloud', 1, 'a:1:{s:9:\"topic_arn\";s:49:\"arn:aws:sns:us-west-2:927364065875:webstore_order\";}', '2013-10-24 14:37:07', NULL);
");

		_dbx('update xlsws_modules set configuration=\'a:15:{s:17:"PRODUCTS_PER_PAGE";s:2:"12";s:19:"LISTING_IMAGE_WIDTH";s:3:"180";s:20:"LISTING_IMAGE_HEIGHT";s:3:"190";s:18:"DETAIL_IMAGE_WIDTH";s:3:"256";s:19:"DETAIL_IMAGE_HEIGHT";s:3:"256";s:16:"MINI_IMAGE_WIDTH";s:2:"30";s:17:"MINI_IMAGE_HEIGHT";s:2:"30";s:20:"CATEGORY_IMAGE_WIDTH";s:3:"180";s:21:"CATEGORY_IMAGE_HEIGHT";s:3:"180";s:19:"PREVIEW_IMAGE_WIDTH";s:2:"30";s:20:"PREVIEW_IMAGE_HEIGHT";s:2:"30";s:18:"SLIDER_IMAGE_WIDTH";s:2:"90";s:19:"SLIDER_IMAGE_HEIGHT";s:2:"90";s:11:"CHILD_THEME";s:5:"light";s:16:"IMAGE_BACKGROUND";s:7:"#FFFFFF";}\' where module=\'brooklyn\'');

		SroRepair::model()->deleteAll();
		SroItem::model()->deleteAll();
		Sro::model()->deleteAll();
		Cart::model()->updateAll(array('document_id'=>null));
		DocumentItem::model()->deleteAll();
		Document::model()->deleteAll();

			_dbx("SET FOREIGN_KEY_CHECKS=0;INSERT INTO `xlsws_destination` (`id`, `country`, `state`, `zipcode1`, `zipcode2`, `taxcode`, `base_charge`, `ship_free`, `ship_rate`, `modified`)
VALUES
	(16, 226, 56, '', '', 104, NULL, NULL, NULL, '2012-09-19 11:04:40'),
	(21, null, null, '', '', 0, NULL, NULL, NULL, '2012-09-20 06:14:43');
");
		$objCart = Cart::model()->findAll();
		$this->assertEquals(0,count($objCart));

		$objProducts = Product::model()->findAll();
		foreach($objProducts as $oProd) {
			$oProd->inventory_reserved=$oProd->CalculateReservedInventory();
			//Since $objProduct->Inventory isn't the real inventory column, it's a calculation,
			//just pass it to the Avail so we have it for queries elsewhere
			$oProd->inventory_avail=$oProd->Inventory;
			$oProd->save();
			}
		_xls_set_conf('SHIPPING_TAXABLE',0);
		_xls_set_conf('DEBUG_LOGGING','info');
		_xls_set_conf('REQUIRE_ACCOUNT',0);

		$objProduct = Product::model()->findByPk(17);
		if ($objProduct instanceof Product) {
			$objProduct->web=1;
			$objProduct->save(); //make this item available
		}
		_xls_set_conf('NEXT_ORDER_ID',30000);
		_xls_set_conf('INVENTORY_OUT_ALLOW_ADD',Product::InventoryAllowBackorders);
		_xls_set_conf('SEO_URL_CATEGORIES',0);


		_dbx("update xlsws_custom_page set page='<p>Page coming soon...</p>' where page_key in ('top','new','promo','about','privacy','tc','welcome')");


		Yii::app()->db->createCommand("delete from ".PromoCode::model()->tableName()." where id>3;alter table ".PromoCode::model()->tableName()." auto_increment=1;")->execute();
		Yii::app()->db->createCommand("delete from ".CategoryIntegration::model()->tableName().";alter table ".CategoryIntegration::model()->tableName()." auto_increment=1;")->execute();


		_dbx("delete from xlsws_promo_code");
		_dbx("INSERT INTO `xlsws_promo_code` (`id`, `enabled`, `exception`, `code`, `type`, `amount`, `valid_from`, `qty_remaining`, `valid_until`, `lscodes`, `threshold`, `module`)
VALUES
	(1, 1, 0, 'fifty', 1, 50, NULL, NULL, '2013-05-29', 'class:Beverages', 0, NULL),
	(2, 0, 0, 'a', 1, 0, NULL, NULL, NULL, 'shipping:,category:Beverages', 15, 'freeshipping');
");

		$obj = PromoCode::LoadByCode('a');
		if ($obj) {
			$obj->enabled=0;
			$obj->save();
		}

		$obj = PromoCode::LoadByCode('fifty');
		if ($obj) {
			$obj->valid_until = date("Y-m-d", strtotime("+1 month"));
			$obj->lscodes = 'class:Beverages';
			$obj->save();
		}


		//Create some promo codes for testing later
		$obj = new PromoCode();
		$obj->enabled=1;
		$obj->code="threedollars";
		$obj->type=PromoCode::Currency;
		$obj->amount = 3;
		$obj->valid_from = "2010-12-12";
		$obj->valid_until = date("Y-m-d", strtotime("+1 month"));
		if (!$obj->save())
			print_r($obj->getErrors());


		$obj = new PromoCode();
		$obj->enabled=1;
		$obj->exception=1;
		$obj->code="notbeverages";
		$obj->type=PromoCode::Currency;
		$obj->amount = 5;
		$obj->qty_remaining=1;
		$obj->lscodes = 'class:Beverages';
		if (!$obj->save())
			print_r($obj->getErrors());

		$obj = new PromoCode();
		$obj->enabled=1;
		$obj->code="expiredtest";
		$obj->type=PromoCode::Currency;
		$obj->amount = 5;
		$obj->valid_from = date("Y-m-d",strtotime("-1 month"));
		$obj->valid_until = date("Y-m-d",strtotime("-1 day"));

		if (!$obj->save())
			print_r($obj->getErrors());

		$obj = new PromoCode();
		$obj->enabled=1;
		$obj->code="notyet";
		$obj->type=PromoCode::Currency;
		$obj->amount = 5;
		$obj->valid_from = date("Y-m-d",strtotime("+1 month"));
		$obj->valid_until = date("Y-m-d",strtotime("+2 months"));

		if (!$obj->save())
			print_r($obj->getErrors());

		$obj = new PromoCode();
		$obj->enabled=1;
		$obj->code="everything";
		$obj->type=PromoCode::Percent;
		$obj->amount = 10;
		$obj->valid_from = date("Y-m-d",strtotime("-1 month"));
		$obj->valid_until = date("Y-m-d",strtotime("+1 month"));
		$obj->threshold = 15;
		$obj->lscodes = 'category:Beverages,category:Clothing,family:Sony,class:Sandwiches,7Up';


		if (!$obj->save())
			print_r($obj->getErrors());

		$obj = new PromoCode();
		$obj->enabled=1;
		$obj->code="house";
		$obj->type=PromoCode::Percent;
		$obj->amount = 10;
		$obj->valid_from = date("Y-m-d",strtotime("-1 month"));
		$obj->valid_until = date("Y-m-d",strtotime("+1 month"));
		$obj->threshold = 15;
		$obj->lscodes = 'Family:House Brand';


		if (!$obj->save())
			print_r($obj->getErrors());



		//Prep some categories for google
		$objCategory = Category::LoadByRequestUrl("beverages");
		$objI = new CategoryIntegration();
		$objI->category_id = $objCategory->id;
		$objI->module="google";
		$objI->foreign_id=1545;
		$objI->save();


		$objCategory = Category::LoadByRequestUrl("clothing");
		$objI = new CategoryIntegration();
		$objI->category_id = $objCategory->id;
		$objI->module="google";
		$objI->foreign_id=73;
		$objI->extra = 'Unisex,Adult';
		$objI->save();

		_dbx("INSERT INTO `xlsws_category_integration` (`category_id`, `module`, `foreign_id`, `extra`)
VALUES
	(28, 'amazon', 25203, NULL),
	(30, 'amazon', 592, NULL),
	(14, 'amazon', 10143, NULL),
	(12, 'amazon', 10261, NULL),
	(35, 'amazon', 14173, NULL),
	(20, 'amazon', 10270, NULL),
	(19, 'amazon', 10261, NULL),
	(11, 'amazon', 9642, 'Beverages');
");

		$objProduct = Product::LoadByCode('7Up');
		if ($objProduct instanceof Product)
		{
			$objProduct->sell = 1.69;
			$objProduct->sell_web = 1.69;
			$objProduct->save();
		}


		//Recalculate all the inventory
		_dbx("update xlsws_product set inventory_reserved=0, inventory_avail=0 where web=0");
		while (Product::RecalculateInventory()>0)
		{}



		_dbx("INSERT INTO `xlsws_stringsource` (`id`, `category`, `message`)
VALUES
	(1, 'unittest', 'Sample Text'),
	(2, 'CheckoutForm', 'First Name'),
	(3, 'CheckoutForm', 'Last Name'),
	(6, 'CheckoutForm', 'Email Address'),
	(7, 'checkout', 'Shopping Cart'),
	(8, 'cart', 'Qty'),
	(9, 'cart', 'SubTotal'),
	(10, 'cart', 'Checkout'),
	(11, 'cart', 'Edit Cart'),
	(12, 'global', 'Order Lookup'),
	(13, 'global', 'Wish Lists'),
	(14, 'global', 'View all my wish lists'),
	(15, 'global', 'Create a Wish List'),
	(16, 'global', 'Search for a wish list'),
	(17, 'global', 'Logout'),
	(18, 'tabs', 'Products'),
	(19, 'global', 'SEARCH'),
	(20, 'global', 'About Us'),
	(21, 'global', 'Terms and Conditions'),
	(22, 'global', 'Privacy Policy'),
	(23, 'global', 'Sitemap'),
	(24, 'global', 'Copyright'),
	(25, 'global', 'All Rights Reserved'),
	(26, 'global', '{name} : {storename}'),
	(27, 'global', 'Login'),
	(28, 'global', 'Register'),
	(29, 'CheckoutForm', 'Password'),
	(30, 'global', 'Forgot Password?'),
	(31, 'global', 'Contact Us'),
	(32, 'global', 'Fields with {*} are required.'),
	(33, 'email', 'Contact Us:'),
	(34, 'email', 'Message sent. Thank you for contacting us. We will respond to you as soon as possible.'),
	(35, 'global', 'Error'),
	(36, 'global', 'Wish List Search'),
	(37, 'wishlist', 'Click on the wish list name to view.'),
	(38, 'global', 'Name'),
	(39, 'global', 'Contains'),
	(40, 'global', 'Description'),
	(41, 'global', '{items} item|{items} items'),
	(42, 'global', 'Search for a wish list by email address'),
	(43, 'category', 'Beverages'),
	(44, 'category', 'Non-Carbonated'),
	(45, 'category', 'Carbonated'),
	(46, 'category', 'Beverage supplies including bar supplies'),
	(47, 'category', 'Snacks'),
	(48, 'category', 'Cupcakes'),
	(49, 'category', 'Fruits & Nuts'),
	(50, 'category', 'Sandwiches'),
	(51, 'category', 'Cards'),
	(52, 'category', 'Clothing'),
	(53, 'category', 'Women\'s Shoes'),
	(54, 'global', 'We\'re sorry, an error has occurred with this site. The error has been logged and the administrators have been notified. For additional help, please contact {email}'),
	(55, 'global', 'My Wish Lists');
");

		_dbx("INSERT INTO `xlsws_stringtranslate` (`id`, `language`, `translation`)
VALUES
	(1, 'de', 'Beispieltext'),
	(1, 'fr', 'Exemple de texte'),
	(2, 'fr', 'Nom'),
	(3, 'fr', 'Nom de famille'),
	(6, 'fr', 'Adresse Courriel'),
	(36, '', 'Wish List Search'),
	(37, '', 'Click on the wish list name to view.'),
	(38, '', 'Name'),
	(39, '', 'Contains'),
	(40, '', 'Description'),
	(41, '', '{items} item|{items} items'),
	(55, '', 'My Wish Lists');
");


	}




}