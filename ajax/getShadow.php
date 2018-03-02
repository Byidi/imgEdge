<?php
ini_set("memory_limit", "256M");
ini_set('max_execution_time', 300);

function getShadow($file, $range){
	$original = new Imagick(realpath($file['basedir'].$file['path']."/".$file['name'].".".$file['ext']));
	$original->setImageBackgroundColor(new ImagickPixel('transparent'));

	$c = $original->getImagePixelColor(0,0);
    $original->floodFillPaintImage('rgba(255,20,147)', $range, $c, 0, 0, false);

	$imageIterator = $original->getPixelIterator();

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

	$original->writeImage($file['basedir'].$file['path']."/shadow.png");
	$original->destroy();

	exec("convert ".$file['basedir'].$file['path']."/shadow.png ".$file['basedir'].$file['path']."/shadow.ppm");
	exec("potrace -s ".$file['basedir'].$file['path']."/shadow.ppm -o ".$file['basedir'].$file['path']."/shadow.svg");

    return $file['basedir'].$file['path']."/shadow.svg";
}

if(!empty($_POST['file']) && !empty($_POST['range'])){
	$file = $_POST['file'];
	$range = $_POST['range'];
    $fileInfo = array();
    preg_match('#^(?<basedir>[.]+\/)(?<path>.*)[\/](?<name>.*)[.](?<ext>.*)#', $file, $fileInfo);

    echo getShadow($fileInfo, $range);
}else{
    echo 'error';
}
?>
