<?php
require_once('./include/header.php');

echo '
<div id="content">
    <form action="" method="post" enctype="multipart/form-data" id="uploadForm">
        <input type="file" name="img" id="uploadFile" />
        <input type="submit" value="Envoyer" name="Envoyer" id="uploadSubmit">
    	<input type="hidden" name="MAX_FILE_SIZE" value="100000" id="uploadMaxSize"/>
    </form>
    <div id="result">
        <div id="canva">
        </div>
        <br/>
        <input type="number" name="range" id="range" value="9999" step="100" min="0" max="1000000" />
    </div>

</div>
<div id="backContent"><img src="./img/loading.gif" /></div>
';
require_once('./include/footer.php');
?>
