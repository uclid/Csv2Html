<html>
	<head>
		<title>CSV to Table Converter</title>
	</head>
	<body>
	
	<?php 
		if (!empty($_GET["table"])) { 
			echo "<b>Your file has been imported.</b><br>"; //generic success notice 
			//display the imported data as table
			$table = $_GET["table"];

			$dbhost = "localhost";
			$dbuser = "root";
			$dbpass = "";
			$dbname = "csvweb";
			$conn = new mysqli ( $dbhost, $dbuser, $dbpass, $dbname );
			if ($conn->connect_errno) die ( 'No database connection!!' );  

			// sending query
			$sql = "SELECT * FROM $table";
			$result = $conn->query($sql);
			if (!$result) {
				die("Query to show fields from table failed");
			}

			$fields_num = mysqli_num_fields($result);

			echo "<h1>Table: $table</h1>";
			echo "<table border='1'><tr>";
			// printing table headers
			for($i=0; $i<$fields_num; $i++)
			{
				$field = mysqli_fetch_field($result);
				echo "<th>$field->name</th>";
			}
			echo "</tr>\n";
			// printing table rows
			while($row = mysqli_fetch_row($result))
			{
				echo "<tr>";

				// $row is array... foreach( .. ) puts every element
				// of $row to $cell variable
				foreach($row as $cell)
					echo "<td>$cell</td>";

				echo "</tr>\n";
			}
			echo "</table>";
			//header_remove();//clean previous headers
			mysqli_free_result($result);
			mysqli_close($conn);
		}
	?>
		<table width="600">
			<form action="csvweb.php" method="post" enctype="multipart/form-data" name="form1" id="form1"> 
			  Choose your file: <br /> 
			  <input name="csv" type="file" id="csv" />
			  <input type="submit" name="Submit" value="Submit" /> 
			</form> 
		</table>
		</br>
		<a href="history.php">View upload history</a>
	</body>
</html>
