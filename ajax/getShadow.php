<?php
ini_set("memory_limit","256M");
ini_set('max_execution_time', 300);

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
	$original->floodFillPaintImage('transparent', 255, $c, 0, 0, false);

	$imagick->compositeImage($original, 3, 50, 50);
	$imagick->setImageBackgroundColor(new ImagickPixel('transparent'));

	$c = $imagick->getImagePixelColor(1, 1);
	$imagick->floodFillPaintImage('transparent', 1 , $c , 0, 0, false);

	//$imagick->writeImage ($originalFile['dir']."0_combine_".$originalFile['name'].".".$originalFile['ext']);

	$imagick->edgeImage(1);

	//$imagick->writeImage ($originalFile['dir']."1_charcoal_".$originalFile['name'].".".$originalFile['ext']);

	$c = $imagick->getImagePixelColor(1, 1);
	$imagick->floodFillPaintImage("#FF1493", 1, $c, 1, 1, false);
	//$imagick->writeImage ($originalFile['dir']."2_floodfill_".$originalFile['name'].".".$originalFile['ext']);

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

	//$imagick->writeImage ($originalFile['dir']."3_pixelcolor_".$originalFile['name'].".".$originalFile['ext']);

	$imagick->writeImage ($originalFile['dir']."final_".$originalFile['name'].".".$originalFile['ext']);
	$imagick->destroy();

	exec("convert ".$originalFile['dir']."final_".$originalFile['name'].".".$originalFile['ext']." ".$originalFile['dir'].$originalFile['name'].".ppm");
	exec("potrace -s ".$originalFile['dir'].$originalFile['name'].".ppm -o ".$originalFile['dir'].$originalFile['name'].".svg");
	exec("rm ".$originalFile['dir']."final_".$originalFile['name'].".".$originalFile['ext']."");
	exec("rm ".$originalFile['dir'].$originalFile['name'].".ppm");

    return $originalFile['dir'].$originalFile['name'].".svg";
}

if(!empty($_POST['file'])){
    $file = $_POST['file'];

    $target_dir = "../img/work/";

	$exp = explode("/",$file);
    $img = $exp[sizeof($exp)-1];
    $exp = explode(".",$img);
	$imageName = $exp[sizeof($exp)-2];

	$imageFileType = strtolower(pathinfo($file,PATHINFO_EXTENSION));
	$target_file = $target_dir . basename($imageName.".".$imageFileType);

    $fileInfo = array(
				"path" => $target_dir.$imageName.".".$imageFileType,
				"name" => $imageName,
				"ext" => $imageFileType,
				"dir" => $target_dir
			);

    echo getShadow($fileInfo);
}else{
    echo 'error';
}
?>
