<html>
	<head>
		<title>File History - CSV to Table Converter</title>
	</head>
	<body>
	<?php 
			echo "<b>Your uploaded files are listed below.</b><br><br>"; //generic success notice 
			//display the imported data as table

			$dbhost = "localhost";
			$dbuser = "root";
			$dbpass = "";
			$dbname = "csvweb";
			$conn = new mysqli ( $dbhost, $dbuser, $dbpass, $dbname );
			if ($conn->connect_errno) die ( 'No database connection!!' );  

			// sending query
			$sql = "SHOW TABLES FROM $dbname";
			$result = $conn->query($sql);

			if (!$result) {
				echo "DB Error, could not list tables";
				exit;
			}

			while ($row = mysqli_fetch_row($result)) {
				echo "Table: <a href='csvindex.php/?table={$row[0]}'>{$row[0]}</a><br>";
			}

			mysqli_free_result($result);
			mysqli_close($conn);
	?>
		<br><br>
		<a href="csvindex.php">Back to home!</a>
	</body>
</html>
