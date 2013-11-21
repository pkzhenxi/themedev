<?php


class ImageTest extends CDbTestCase
{


	public function testCalculation() {
		$retVal = Images::CalculateNewSize(300,399, 190,190);
		$this->assertEquals(143,$retVal[0]);
		$this->assertEquals(190,$retVal[1]);

	}

	public function testGetImagePath() {

		$strName = Images::GetImagePath('test1.jpg');
		$this->assertContains(realpath(Yii::getPathOfAlias('webroot')) . "/images/test1.jpg",$strName);

	}
	public function testIsWritablePath() {
		$strReturn = Images::IsWritablePath('/product/t/test1.jpg');
		$this->assertTrue($strReturn);
	}

	//Test removed because we're creating thumbnails during upload with extension now
//	public function testCreateThumb() {
//
//		$id = 54;
//		$intWidth=256;
//		$intHeight=256;
//
//		$objParentImage = Images::LoadByParent($id);
//		$this->assertInstanceOf('Images',$objParentImage);
//
//		$thumb = $objParentImage->CreateThumb($intWidth, $intHeight);
//		$this->assertInstanceOf('Images',$thumb);
//		$this->assertEquals(33,$thumb->product_id);
//		$this->assertEquals(0,$thumb->index);
//
//
//	}


	public function testThumbnailPath () {
		//Load a product and display the information


		$pid = 3;
        $objProduct = Product::model()->findByPk($pid);
		$this->assertGreaterThan(0,$objProduct->image_id);

		$intHeight = _xls_get_conf('DETAIL_IMAGE_HEIGHT');
		$intWidth = _xls_get_conf('DETAIL_IMAGE_WIDTH');
		$strExt = _xls_get_conf('IMAGE_FORMAT');
		if ($objProduct->image_id)
		{


			$this->assertEquals('/images/product/c/coke-flat-24-cans-'.$intWidth.'px-'.$intHeight.'px.'.$strExt,
				Images::GetLink($objProduct->image_id,ImagesType::pdetail));
			$this->assertEquals('<img src="/images/product/c/coke-flat-24-cans-'.
					$intWidth.'px-'.$intHeight.'px.'.$strExt.'" alt="" />',
				CHtml::image(Images::GetLink($objProduct->image_id,ImagesType::pdetail)));


			/* Return a path to a file that does exist */
			$objImage = Images::LoadByRowidSize($objProduct->image_id, ImagesType::pdetail);
			$strReturn = Images::GetImageUri($objImage->image_path);
			$this->assertEquals('/images/product/c/coke-flat-24-cans-256px-256px.jpg',$strReturn);


		}

		/* Return a path to a file that doesn't exist, which should go to fallback */
		$strReturn = Images::GetLink(-123);
		$this->assertContains('no_product',$strReturn);

	}



	public function testGetSize() {

		$strSize = ImagesType::pdetail;
		$this->assertEquals(2,$strSize);

		$arrSize = ImagesType::GetSize($strSize);

		$arrReturn = Images::GetSize('pdetailimage');
		$this->assertEquals($arrReturn,$arrSize);



	}

	public function testGetImageName() {

		/* Should return path to original png as it had come from LightSpeed */
		$strReturn = Images::GetImageName('test1.png');
		$this->assertEquals('product/t/test1.png',$strReturn);

		/* Create thumbnail name */
		$strReturn = Images::GetImageName('test1.png',123,456);
		$this->assertEquals('product/t/test1-123px-456px.jpg',$strReturn);

		/* Create thumbnail additional 1 */
		$strReturn = Images::GetImageName('test1.png',0,0,1);
		$this->assertEquals('product/t/test1-add-1.png',$strReturn);

		/* Create thumbnail additional 1 */
		$strReturn = Images::GetImageName('test1.png',100,200,1);
		$this->assertEquals('product/t/test1-add-1-100px-200px.jpg',$strReturn);



		$strName = Images::GetImageName('Κόκα-Κόλα.png');
		$this->assertEquals("product/Κ/Κόκα-Κόλα.png",$strName);



	}





	public function testIsPrimary() {

		$objImage = Images::model()->findByPk(1);
		$objImage2 = Images::model()->findByAttributes(array('width'=>256,'height'=>256,'product_id'=>109));
		//LoadByWidthHeightParent(256,256,54);

		$this->assertTrue($objImage->IsPrimary());
		$this->assertFalse($objImage2->IsPrimary());

	}

	public function testGetPath() {

		$objImage = Images::model()->findByPk(1);
		$objImage2 = Images::model()->findByAttributes(array('width'=>256,'height'=>256,'product_id'=>33));


		$this->assertContains('images/product/a/aandw-root-beer.png',$objImage->GetPath());
		$this->assertContains('images/product/c/cupcakes-for-you-pink-m-256px-256px.jpg',$objImage2->GetPath());

	}

	public function testImageFileExists() {

		$objImage =  Images::model()->findByAttributes(array('width'=>256,'height'=>256,'product_id'=>33));
		$this->assertTrue($objImage->ImageFileExists());

		//Delete image file
		$objImage->DeleteImage();
		$this->assertFalse($objImage->ImageFileExists());





	}


	//ToDo: refactor showfallback to return url to appropriate size
//	public function testShowFallback() {
//
//		$strReturn = Images::ShowFallback(80,80);
//		print_r($strReturn);
//	}




//	public function testDelete() {
//  tested in 99-delete functions
//	}

//	public function testLoadByRowidSize() {
//
//		$objImage = Images::LoadByRowidSize(50,ImagesType::pdetail);
//		$this->assertInstanceOf('Images',$objImage);
//	}

//	public function testLoadByWidthParent() {
//
//		$objImage = Images::LoadByWidthParent(256, 50);
//		$this->assertInstanceOf('Images',$objImage);
//	}
//
//	public function testLoadByParent() {
//
//		$objImage = Images::LoadByParent(50);
//		$this->assertEquals('product/c/coke-flat-24-cans.png',$objImage->image_path);
//	}
//
//	public function testLoadByWidthHeightParent() {
//
//		$objImage = Images::LoadByWidthHeightParent(256, 256,50);
//		$this->assertInstanceOf('Images',$objImage);
//	}


	public function testExistsForOtherProduct() {
		$objProduct = Product::model()->findByAttributes(array('id'=>88));
		$strImageName = Images::GetImageName(substr($objProduct->request_url,0,60));

		//Verify that we return a false when we're dealing with our own image
		$retValue  = Images::ExistsForOtherProduct($strImageName, $objProduct->id);
		$this->assertFalse($retValue);

		echo $strImageName;
		//Verify we return true when we pretend this filename is going to be used for a different product
		$retValue  = Images::ExistsForOtherProduct($strImageName, 1);
		$this->assertTrue($retValue);

	}


	public function testImageDBQuery()
	{

		$strName = "product/こ/この見積有効期限は１週間です。.png";
		$retVal = Images::model()->findByAttributes(array('image_path'=>$strName));

		print_r($retVal);
	}




}

function testRemoveThumbnails($path= "../../../images/product/",$match='*px.jpg'){
	static $deleted = 0,
	$deleted_size = 0;
	$dirs = glob($path."*");
	$files = glob($path.$match);
	foreach($files as $file){
		if(is_file($file)){
			$deleted_size += filesize($file);
			echo($file."\n");
			unlink($file);
			$deleted++;
		}
	}
	foreach($dirs as $dir){
		if(is_dir($dir)){
			$dir = basename($dir) . "/";
			testRemoveThumbnails($path.$dir,$match);
		}
	}
	//echo "$deleted files deleted with a total size of $deleted_size bytes\n";
}