<?php
require_once('./include/header.php');
require_once('./include/footer.php');

echo '
<div id="content">
    <form action="" method="post" enctype="multipart/form-data" id="uploadForm">
        <input type="file" name="img" id="uploadFile" />
        <input type="submit" value="Envoyer" name="Envoyer" id="uploadSubmit">
    	<input type="hidden" name="MAX_FILE_SIZE" value="100000" id="uploadMaxSize"/>
    </form>
    <div id="result">
        <img src="" id="originalFile" />
        <img src="" id="vectorFile" />
    </div>
    <div id="backContent"><img src="./img/loading.gif" /></div>
</div>
';

?>
