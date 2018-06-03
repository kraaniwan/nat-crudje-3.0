<?php
error_reporting(E_ALL);
ini_set("error_reporting", E_ALL);	

	function pp($src, $title='print_r vardump') {
		echo "<pre><h2>$title</h2>";
		echo print_r($src, true),"</pre>";
	}

	$conn = new PDO("mysql:host=localhost;dbname=dervanrelatie;port=3306","root", "mysql");
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//pp($conn, 'conn');
	
	if(isset($_GET['s'])) {
		pp($_GET,'get');
		switch($_GET['s']) {
			case 'T':
				echo "<h2>Upload successful!</h2>";
				break;
		}
		echo "<a href='#'>Display list</a> | <a href='index.php'>Home</a><hr>";
	}

	if(count($_FILES) > 0) {
		if(is_uploaded_file($_FILES['userImage']['tmp_name'])) {

			$imgData = file_get_contents($_FILES['userImage']['tmp_name']);
			$imageProperties = getimageSize($_FILES['userImage']['tmp_name']);
			
			foreach($_POST as $k=>$v)
				$$k = $v;
			
			echo "<pre>"; print_r($_POST); echo "</pre>";
			echo "<pre>"; print_r($imageProperties); echo "</pre>";
			echo "De variabele txtRegion is |$txtRegion|<br>";
			
			$sql = "INSERT INTO regionsBLOB (regionname, mimetype, image) VALUES(:reg, :mim, :img)";
			try {
				$st = $conn->prepare($sql);
				
				$st->bindParam(':reg', $txtRegion, PDO::PARAM_STR);
				$st->bindParam(':mim', $imageProperties['mime']);
				$st->bindParam(':img', $imgData, PDO::PARAM_LOB);
				$st->execute();
				$lid = $conn->lastInsertId();
			} 
			catch(PDOException $e) {
				echo "$sql<br>". $e->getMessage();
				exit("<br>---");
			}

			header("Location: addRegions.php?id=$lid&s=T");
			exit("<br>Ready by exit statement!"); // should not be displayed
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
<title>Upload Image to MySQL BLOB FIELD</title>
<link href="https://fonts.googleapis.com/css?family=Concert+One" rel="stylesheet">
<style>
body {
	font-family: 'Concert One', cursive;
	background-color: #eee;
	font-size: 1.2em;
}

#container {
	background-color: white;
	width: 60%;
	margin: 0 auto;
	padding: 0.5cm;
	border: solid 1px #999;
	border-radius: 10px;
}

input[type=text], input[type=file], select {
		font-family: 'Concert One', cursive;
		font-size: 1.2em;
    padding: 10px 10px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type=submit] {
		font-family: 'Concert One', cursive;
		font-size: 1.2em;
    width: 40%;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type=submit]:hover {
    background-color: #45a049;
}

input.wider {
	width: 450px;
}

input.lrg { 
		width: 22px; height: 22px; 
}

</style>
</head>
<body>
<div id="container">
<h3>Region and Image upload</h3>
<form enctype="multipart/form-data" action="addRegions.php" method="post" class="">
<table border='0' align='center' cellpadding='5' cellspacing='2'>

<tr><td><label>Region name</label></td>
<td><input name="txtRegion" type="text" required class="wider"></td></tr>
<input type="hidden" name="MAX_FILE_SIZE" value="300000" />
<tr><td><label>Upload Image File:</label></td>
<td><input name="userImage" type="file" class=""></td></tr>

<tr><td colspan='2' align='center'><input type="submit" value="Start upload" class=""></td></tr>
</form>
</table>
</div> <!-- einde container -->
</body>
</html>
