<?php
	$conn = new PDO("mysql:host=localhost;dbname=php_beginner_crud_level_1;port=3306", "root", "mysql");
	$q = $conn->prepare("SELECT regionname, mimetype, image FROM regionsBLOB where id=:imgid");
	$q->execute(array(':imgid' => $_GET['id']));
	$data = $q->fetch(PDO::FETCH_OBJ);
	$w = isset($_GET['w']) ? $_GET['w'] : 50;
	$h = isset($_GET['h']) ? $_GET['h'] : 50;
	$st = "style='width:{$w}px;height:{$h}px'";
	echo "<img $st src='data:{$data->mimetype};base64,"
		.base64_encode( $data->image )
		."' title='image: {$data->regionname}'/>";
?>
