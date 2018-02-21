<?php
ini_set("memory_limit","256M");
ini_set('max_execution_time', 300);

function uploadImg($uploadImage){
	$target_dir = "./img/";

	$exp = explode(".",$_FILES['img']['name']);
	$imageName = $exp[sizeof($exp)-2]."-".time();

	$imageFileType = strtolower(pathinfo($_FILES['img']['name'],PATHINFO_EXTENSION));
	$target_file = $target_dir . basename($imageName.".".$imageFileType);

	$uploadOk = true;

	if(isset($_POST["submit"])) {
	    $check = getimagesize($uploadImage["tmp_name"]);
	    if($check !== false) {
	        $uploadOk = false;
	    } else {
	        $uploadOk = false;
	    }
	}

	if (file_exists($target_file)) {
	    $uploadOk = false;
	}

	if ($uploadImage["size"] > 50000000) {
	    $uploadOk = false;
	}

	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
	    $uploadOk = false;
	}

	if ($uploadOk) {
	    if (move_uploaded_file($uploadImage["tmp_name"], $target_file)) {
			return array(
				"path" => $target_dir.$imageName.".".$imageFileType,
				"name" => $imageName,
				"ext" => $imageFileType,
				"dir" => $target_dir
			);
	    }
	}
	return "error";
}

function getShadow($originalFile){
	$original = new Imagick(realpath($originalFile['path']));
	$original->setImageBackgroundColor(new ImagickPixel('transparent'));

	$geo = $original->getImageGeometry();
	$width = $geo['width']+100;
	$height = $geo['height']+100;

	$imagick = new Imagick();
	$imagick->newImage($width, $height, 'none');
	$imagick->setImageFormat('png');
	$imagick->setImageBackgroundColor(new ImagickPixel('transparent'));

	$c = $original->getImagePixelColor(1, 1);
	$original->floodFillPaintImage ( 'transparent' , 255 , $c , 0, 0 , false);

	$imagick->compositeImage($original, 3, 50, 50);
	$imagick->setImageBackgroundColor(new ImagickPixel('transparent'));

	$c = $imagick->getImagePixelColor(1, 1);
	$imagick->floodFillPaintImage ( 'transparent' , 1 , $c , 0, 0 , false);

	$imagick->writeImage ($originalFile['dir']."0_combine_".$originalFile['name'].".".$originalFile['ext']);

	$imagick->charcoalImage(1,1);
	$imagick->writeImage ($originalFile['dir']."1_charcoal_".$originalFile['name'].".".$originalFile['ext']);
	$imagick->whiteThresholdImage('grey');
	$imagick->floodFillPaintImage ( "#FF1493" , 999 , "#ffffff" , 1 , 1 , false );
	$imagick->floodFillPaintImage ( "#FF1493" , 999 , "#ffffff" , 55 , 55 , false );
	$imagick->writeImage ($originalFile['dir']."2_floodfill_".$originalFile['name'].".".$originalFile['ext']);

	$imageIterator = $imagick->getPixelIterator();

	foreach ($imageIterator as $row => $pixels) {
	   foreach ($pixels as $column => $pixel) {
		   $color = $pixel->getColor();
		   if($color['r'] != 255 || $color['g'] != 20 || $color['b'] != 147){
			   $pixel->setColor("#00FF00");
			}else{
			   $pixel->setColor("#FFFFFF");
			}
	   }
	   $imageIterator->syncIterator();
	}

	$imagick->writeImage ($originalFile['dir']."3_pixelcolor_".$originalFile['name'].".".$originalFile['ext']);

	$imagick->writeImage ($originalFile['dir']."final_".$originalFile['name'].".".$originalFile['ext']);
	$imagick->destroy();

	exec("convert ".$originalFile['dir']."final_".$originalFile['name'].".".$originalFile['ext']." ".$originalFile['dir'].$originalFile['name'].".ppm");
	exec("potrace -s ".$originalFile['dir'].$originalFile['name'].".ppm -o ".$originalFile['dir'].$originalFile['name'].".svg");
}

echo '
<form action="" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="img" id="img" />
	<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
    <input type="submit" value="Envoyer" name="Envoyer">
</form>
';


if(isset($_FILES['img'])){
	$originalFile = uploadImg($_FILES['img']);

	if ($originalFile != false){
		getShadow($originalFile);
		echo '
		<div style="border:1px solid black; padding:5px;">
			<img src="'.$originalFile['path'].'" style="width:300px;" />
			<img src="./img/'.$originalFile['name'].'.svg" style="width:300px" />
		</div>';
	}
}
?>
