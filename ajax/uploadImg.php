<?php
ini_set("memory_limit","256M");
ini_set('max_execution_time', 300);

function uploadImg($uploadImage){
	$target_dir = "../img/work/";

	$exp = explode(".",$_FILES['img']['name']);
	$imageName = $exp[sizeof($exp)-2]."-".time();

	$imageFileType = strtolower(pathinfo($_FILES['img']['name'],PATHINFO_EXTENSION));
	$target_file = $target_dir . basename($imageName.".".$imageFileType);

	$uploadOk = true;

	if(isset($_POST["submit"])){
	    $check = getimagesize($uploadImage["tmp_name"]);
	    if($check !== false) {
	        $uploadOk = false;
	    } else {
	        $uploadOk = false;
	    }
	}

	if (file_exists($target_file)){
	    $uploadOk = false;
	}

	if ($uploadImage["size"] > 50000000){
	    $uploadOk = false;
	}

	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ){
	    $uploadOk = false;
	}

	if ($uploadOk){
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

if(isset($_FILES['img'])){
	$originalFile = uploadImg($_FILES['img']);
    echo $originalFile['path'];
}else{
    echo 'error';
}

?>
