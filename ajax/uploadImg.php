<?php
ini_set("memory_limit","256M");
ini_set('max_execution_time', 300);

function uploadImg($uploadImage){
	$imgDir = "../img/";
	$workDir = "work/";

	$targetDir = $imgDir.$workDir;

	$exp = explode(".",$_FILES['img']['name']);
	$imageName = $exp[sizeof($exp)-2]."-".time();
	$dirName = $exp[sizeof($exp)-2]."-".time();
	exec("mkdir ".$targetDir.$dirName."/");
	$targetDir = $targetDir.$dirName."/";

	$imageFileType = strtolower(pathinfo($_FILES['img']['name'],PATHINFO_EXTENSION));
	$targetFile = $targetDir."original.".$imageFileType;

	$uploadOk = true;

	if(isset($_POST["submit"])){
	    $check = getimagesize($uploadImage["tmp_name"]);
	    if($check !== false) {
	        $uploadOk = false;
	    } else {
	        $uploadOk = false;
	    }
	}

	if (file_exists($targetFile)){
	    $uploadOk = false;
	}

	if ($uploadImage["size"] > 50000000){
	    $uploadOk = false;
	}

	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ){
	    $uploadOk = false;
	}

	if ($uploadOk){
	    if (move_uploaded_file($uploadImage["tmp_name"], $targetFile)){
			if( $imageFileType != "png"){
				$original = new Imagick(realpath($targetFile));
				$original->setImageBackgroundColor(new ImagickPixel('transparent'));

				$geo = $original->getImageGeometry();
				$width = $geo['width'];
				$height = $geo['height'];

				$imagick = new Imagick();
				$imagick->newImage($width, $height, 'transparent');
				$imagick->setImageFormat('png');
				$imagick->setImageBackgroundColor(new ImagickPixel('transparent'));

				$imagick->compositeImage($original, 3, 0, 0);
				$imagick->setImageBackgroundColor(new ImagickPixel('transparent'));

				$imagick->writeImage($targetDir."original.png");
				$imagick->destroy();
			}
			return $targetDir."original.png";
	    }
	}
	return "error";
}

if(isset($_FILES['img'])){
	echo uploadImg($_FILES['img']);
}else{
    echo 'error';
}

?>
