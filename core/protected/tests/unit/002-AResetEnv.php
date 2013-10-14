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
	(61, 1, 'axia', 'payment', NULL, NULL, 15, 'a:5:{s:5:\"label\";s:36:\"Credit card (Visa, Mastercard, Amex)\";s:10:\"source_key\";s:32:\"tBLbnzONj82GH1kWcBCqfu7b6DZoksqT\";s:14:\"source_key_pin\";s:0:\"\";s:4:\"live\";s:4:\"live\";s:17:\"ls_payment_method\";s:11:\"Credit Card\";}', '2013-04-16 15:16:48', NULL),
	(64, 1, 'paypal', 'payment', 1, 'PayPal', 9, 'a:4:{s:5:\"label\";s:6:\"PayPal\";s:5:\"login\";s:36:\"kris.w_1331482444_biz@eightounce.com\";s:4:\"live\";s:4:\"test\";s:17:\"ls_payment_method\";s:11:\"Credit Card\";}', '2013-04-16 15:38:26', NULL),
	(65, 1, 'storepickup', 'shipping', NULL, NULL, 21, 'a:4:{s:5:\"label\";s:12:\"Store Pickup\";s:3:\"msg\";s:71:\"Please quote order ID %s with photo ID at the reception for collection.\";s:7:\"product\";s:8:\"SHIPPING\";s:6:\"markup\";s:1:\"0\";}', '2013-04-16 15:16:48', NULL),
	(66, 1, 'authorizedotnetaim', 'payment', 1, 'Authorize.Net', 16, 'a:7:{s:5:\"label\";s:34:\"Authorize.Net Advanced Integration\";s:5:\"login\";s:9:\"6Cy3vg3DT\";s:9:\"trans_key\";s:16:\"3msw2846jXT6pVEu\";s:4:\"live\";s:4:\"test\";s:3:\"ccv\";i:1;s:11:\"specialcode\";s:0:\"\";s:17:\"ls_payment_method\";s:11:\"Credit Card\";}', '2013-04-16 15:38:26', NULL),
	(67, 1, 'beanstreamaim', 'payment', 1, 'Beanstream (US/CAN)', 17, 'a:4:{s:5:\"label\";s:31:\"Beanstream Advanced Integration\";s:5:\"login\";s:9:\"263770000\";s:15:\"restrictcountry\";s:4:\"null\";s:17:\"ls_payment_method\";s:11:\"Credit Card\";}', '2013-04-16 16:52:04', NULL),
	(68, 1, 'beanstreamsim', 'payment', 1, 'Beanstream (US/CAN)', 18, 'a:4:{s:5:\"label\";s:14:\"Beanstream SIM\";s:5:\"login\";s:9:\"198870000\";s:7:\"md5hash\";s:0:\"\";s:17:\"ls_payment_method\";s:11:\"Credit Card\";}', '2013-04-16 15:38:26', NULL),
	(71, 1, 'tieredshipping', 'shipping', NULL, NULL, 16, 'a:4:{s:5:\"label\";s:19:\"Tier Based Shipping\";s:9:\"tierbased\";s:5:\"price\";s:15:\"restrictcountry\";N;s:7:\"product\";s:8:\"SHIPPING\";}', '2013-04-16 15:16:48', NULL),
	(73, 1, 'authorizedotnetsim', 'payment', 1, 'Authorize.Net', 19, 'a:6:{s:5:\"label\";s:12:\"Auth.net SIM\";s:5:\"login\";s:9:\"6Cy3vg3DT\";s:9:\"trans_key\";s:16:\"3msw2846jXT6pVEu\";s:7:\"md5hash\";s:7:\"hashish\";s:4:\"live\";s:4:\"test\";s:17:\"ls_payment_method\";s:15:\"Web Credit Card\";}', '2013-04-16 15:38:26', NULL),
	(74, 1, 'wsbsidebar', 'sidebar', NULL, NULL, 4, NULL, '2013-04-16 15:16:48', NULL),
	(76, 1, 'fedex', 'shipping', NULL, NULL, 5, 'a:16:{s:5:\"label\";s:5:\"FedEx\";s:9:\"accnumber\";s:9:\"294946276\";s:11:\"meternumber\";s:9:\"102942395\";s:12:\"securitycode\";s:25:\"st0xxm7g6jxGh2czWs3TIWOmF\";s:7:\"authkey\";s:16:\"BzUmPf8YjAvWasAN\";s:10:\"originadde\";s:13:\"2655 Freewood\";s:10:\"origincity\";s:6:\"Dallas\";s:14:\"originpostcode\";s:5:\"75220\";s:13:\"origincountry\";s:2:\"US\";s:11:\"originstate\";s:2:\"TX\";s:9:\"packaging\";s:14:\"YOUR_PACKAGING\";s:8:\"ratetype\";s:10:\"RATED_LIST\";s:7:\"customs\";s:12:\"CLEARANCEFEE\";s:15:\"restrictcountry\";N;s:7:\"product\";s:8:\"SHIPPING\";s:6:\"markup\";s:1:\"3\";}', '2013-04-16 15:16:48', NULL),
	(80, 1, 'worldpaysim', 'payment', 1, 'Worldpay', 20, 'a:4:{s:5:\"label\";s:8:\"WorldPay\";s:5:\"login\";s:6:\"123123\";s:4:\"live\";s:4:\"test\";s:17:\"ls_payment_method\";s:15:\"Web Credit Card\";}', '2013-04-16 15:38:26', NULL),
	(84, 1, 'merchantware', 'payment', NULL, NULL, 21, 'a:5:{s:5:\"label\";s:12:\"Merchantware\";s:4:\"name\";s:6:\"Xsilva\";s:7:\"site_id\";s:8:\"6VBYB5BC\";s:9:\"trans_key\";s:29:\"DW8YD-9C77X-AZP81-AN9M8-AXGX3\";s:17:\"ls_payment_method\";s:11:\"Credit Card\";}', '2013-04-16 15:16:48', NULL),
	(86, 1, 'phoneorder', 'payment', 1, 'Phone Order', 22, 'a:3:{s:5:\"label\";s:11:\"Phone Order\";s:5:\"phone\";s:47:\"Please call us on ith your credit card details.\";s:17:\"ls_payment_method\";s:11:\"Credit Card\";}', '2013-04-16 15:38:26', NULL),
	(93, 1, 'canadapost', 'shipping', NULL, NULL, 23, 'a:6:{s:5:\"label\";s:11:\"Canada Post\";s:14:\"originpostcode\";s:7:\"V5T 3E2\";s:3:\"cpc\";s:17:\"CPC_DUNBAR_CYCLES\";s:14:\"defaultproduct\";s:16:\"Priority Courier\";s:7:\"product\";s:8:\"SHIPPING\";s:6:\"markup\";s:1:\"3\";}', '2013-04-16 15:16:48', NULL),
	(94, 1, 'paypalpro', 'payment', 1, 'PayPal Pro', 25, 'a:9:{s:5:\"label\";s:10:\"Paypal Pro\";s:12:\"api_username\";s:1:\"k\";s:12:\"api_password\";s:1:\"k\";s:13:\"api_signature\";s:1:\"k\";s:4:\"live\";s:4:\"test\";s:15:\"api_username_sb\";s:41:\"kris.w_1331482444_biz_api1.eightounce.com\";s:15:\"api_password_sb\";s:10:\"1331482483\";s:16:\"api_signature_sb\";s:56:\"An5ns1Kso7MWUdW4ErQKJJJ4qi4-AwneZA03eTr3ififGIk-YlERzbtu\";s:17:\"ls_payment_method\";s:15:\"Web Credit Card\";}', '2013-04-16 15:38:26', NULL),
	(95, 1, 'freeshipping', 'shipping', NULL, NULL, 9, 'a:8:{s:5:\"label\";s:13:\"Free shipping\";s:4:\"rate\";s:2:\"15\";s:9:\"startdate\";s:0:\"\";s:7:\"enddate\";s:0:\"\";s:9:\"promocode\";s:0:\"\";s:13:\"qty_remaining\";s:0:\"\";s:15:\"restrictcountry\";N;s:7:\"product\";s:8:\"SHIPPING\";}', '2013-04-16 15:16:48', NULL),
	(97, 1, 'purchaseorder', 'payment', 1, 'Purchase Order', 24, 'a:2:{s:5:\"label\";s:19:\"Pay with Membership\";s:17:\"ls_payment_method\";s:14:\"Purchase Order\";}', '2013-04-16 15:38:26', NULL),
	(102, 1, 'ups', 'shipping', NULL, NULL, 25, 'a:8:{s:5:\"label\";s:3:\"UPS\";s:14:\"originpostcode\";s:5:\"78759\";s:13:\"origincountry\";s:2:\"US\";s:14:\"defaultproduct\";s:3:\"1DA\";s:8:\"ratecode\";s:20:\"Regular+Daily+Pickup\";s:7:\"package\";s:2:\"CP\";s:7:\"product\";s:8:\"SHIPPING\";s:6:\"markup\";s:1:\"3\";}', '2013-04-16 15:16:48', NULL),
	(104, 1, 'usps', 'shipping', NULL, NULL, 27, 'a:7:{s:5:\"label\";s:4:\"USPS\";s:8:\"username\";s:12:\"786ALTER3964\";s:14:\"originpostcode\";s:5:\"11222\";s:15:\"restrictcountry\";N;s:7:\"product\";s:8:\"SHIPPING\";s:6:\"markup\";s:1:\"3\";s:9:\"shiptypes\";s:124:\"Express Mail,Priority Mail,First-Class Mail Large Envelope,Express Mail International,Standard Post,First-Class Mail Parcel,\";}', '2013-04-16 15:16:48', NULL),
	(121, 0, 'brooklyn', 'template', NULL, NULL, NULL, 'a:13:{s:19:\"LISTING_IMAGE_WIDTH\";s:3:\"180\";s:20:\"LISTING_IMAGE_HEIGHT\";s:3:\"190\";s:18:\"DETAIL_IMAGE_WIDTH\";s:3:\"256\";s:19:\"DETAIL_IMAGE_HEIGHT\";s:3:\"256\";s:16:\"MINI_IMAGE_WIDTH\";s:2:\"30\";s:17:\"MINI_IMAGE_HEIGHT\";s:2:\"30\";s:20:\"CATEGORY_IMAGE_WIDTH\";s:3:\"180\";s:21:\"CATEGORY_IMAGE_HEIGHT\";s:3:\"180\";s:19:\"PREVIEW_IMAGE_WIDTH\";s:2:\"30\";s:20:\"PREVIEW_IMAGE_HEIGHT\";s:2:\"30\";s:18:\"SLIDER_IMAGE_WIDTH\";s:2:\"90\";s:19:\"SLIDER_IMAGE_HEIGHT\";s:2:\"90\";s:22:\"DEFAULT_TEMPLATE_THEME\";s:5:\"light\";}', '2012-09-24 16:23:18', NULL),
	(122, 1, 'flatrate', 'shipping', NULL, NULL, 28, 'a:5:{s:5:\"label\";s:18:\"Flat rate shipping\";s:3:\"per\";s:4:\"item\";s:4:\"rate\";s:1:\"1\";s:15:\"restrictcountry\";N;s:7:\"product\";s:8:\"SHIPPING\";}', '2013-04-16 15:16:48', NULL),
	(123, 1, 'australiapost', 'shipping', NULL, NULL, 29, 'a:6:{s:5:\"label\";s:14:\"Australia Post\";s:14:\"originpostcode\";s:4:\"4000\";s:14:\"defaultproduct\";s:8:\"STANDARD\";s:15:\"restrictcountry\";N;s:7:\"product\";s:8:\"SHIPPING\";s:6:\"markup\";s:1:\"3\";}', '2013-04-16 15:16:48', NULL),
	(124, 1, 'destinationshipping', 'shipping', NULL, NULL, 30, 'a:3:{s:5:\"label\";s:20:\"Destination Shipping\";s:3:\"per\";s:4:\"item\";s:7:\"product\";s:8:\"SHIPPING\";}', '2013-04-16 15:16:48', NULL),
	(126, 1, 'wsphoto', 'CEventPhoto', 1, 'Web Store Internal', 1, NULL, '2013-04-16 15:16:42', NULL),
	(127, 1, 'wsmailchimp', 'CEventCustomer', 1, 'MailChimp', 1, 'a:2:{s:7:\"api_key\";s:36:\"7ace7f2ad23a4a0f748dc95e945a103e-us5\";s:4:\"list\";s:9:\"Web Store\";}', '2013-04-23 14:18:52', NULL),
	(129, 0, 'cheque', 'payment', 1, 'Check', NULL, 'a:3:{s:5:\"label\";s:6:\"Cheque\";s:15:\"restrictcountry\";N;s:17:\"ls_payment_method\";s:15:\"Web Credit Card\";}', '2013-04-16 15:38:26', '2013-04-16 15:23:19'),
	(130, 0, 'ewayaim', 'payment', 1, 'eWAY CVN Australia', NULL, 'a:4:{s:5:\"label\";s:4:\"eWay\";s:5:\"login\";s:8:\"87654321\";s:4:\"live\";s:4:\"test\";s:17:\"ls_payment_method\";s:15:\"Web Credit Card\";}', '2013-04-16 16:52:04', '2013-04-16 15:23:19'),
	(131, 0, 'moneris', 'payment', 1, 'Moneris', NULL, 'a:9:{s:5:\"label\";s:7:\"Moneris\";s:8:\"store_id\";s:6:\"store5\";s:9:\"api_token\";s:6:\"yesguy\";s:4:\"live\";s:4:\"live\";s:3:\"ccv\";s:1:\"1\";s:3:\"avs\";s:1:\"1\";s:11:\"specialcode\";N;s:15:\"restrictcountry\";s:4:\"null\";s:17:\"ls_payment_method\";s:15:\"Web Credit Card\";}', '2013-04-16 16:52:04', '2013-04-16 15:23:19'),
	(132, 1, 'wsamazon', 'CEventProduct,CEventPhoto,CEventOrder', 1, 'Amazon MWS', 1, 'a:6:{s:18:\"AMAZON_MERCHANT_ID\";s:13:\"ABZUSRJL7VB69\";s:24:\"AMAZON_MWS_ACCESS_KEY_ID\";s:20:\"AKIAJMEUPZC75EQ7ERYQ\";s:21:\"AMAZON_MARKETPLACE_ID\";s:13:\"ATVPDKIKX0DER\";s:28:\"AMAZON_MWS_SECRET_ACCESS_KEY\";s:40:\"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\";s:7:\"product\";s:8:\"SHIPPING\";s:17:\"ls_payment_method\";s:15:\"Web Credit Card\";}', '2013-04-22 13:36:13', NULL);
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


	}




}