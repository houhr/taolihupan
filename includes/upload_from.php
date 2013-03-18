<link href="../css/other.css" rel="stylesheet" type="text/css" media="screen" />
<iframe id="invisableFrame" name="invisableFrame" width="300px" height="100px" style="display:none"></iframe>
<form method="post" enctype="multipart/form-data" target="invisableFrame" onsubmit="startUpload();" 
	action="./upload.php"
	id="upload_form" name="upload_form">
	<?php
		if(!empty($_GET['path'])){
			echo '<input type="hidden" name="upload_path" value="'.$_GET['path'].'"/>';
		}
	?>
	<input type="hidden" name="MAX_FILE_SIZE" value="< ?php echo MAX_FILE_SIZE;?>"/>
	<input size="31" type="file" id="upload_file" name="upload_file" onchange="submit()"/>
</form>