<?php
header_remove();//clean previous headers
// connect to database
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "csvweb";
$conn = new mysqli ( $dbhost, $dbuser, $dbpass, $dbname );
if ($conn->connect_errno) die ( 'No database connection!!' );   

$file = $_FILES["csv"]["tmp_name"];
$filename = $_FILES["csv"]["name"];
$filename = str_replace('.', '_', $filename);

// -----------------------------CSV to MySQL table--------------------------
$handle = fopen ( $file, 'r' );//open 'csv' data file

$data = fgetcsv ( $handle);
$num = count($data);
echo $num;
$newTableName = "$filename" . date ( "Ymd" );

$conn->query ( "DROP TABLE IF EXISTS $newTableName" );

$schema = "id int AUTO_INCREMENT, ";
for($i=0; $i<$num-1; $i++){
	$schema = "$schema" . "var". ($i+1) . " varchar(50),\n";
}
$schema = "$schema" . "var$num" . " varchar(50),\n PRIMARY KEY (id)";
$creation = "CREATE TABLE $newTableName ($schema);";
//$conn->query ( $creation );//CREATE TABLE 
if (!$conn->query($creation)){
    echo "Table creation failed: (" . $conn->errno . ") " . $conn->error;
}
fclose ( $handle ); 

$row = 1;
if (($handle = fopen("$file", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        $row++;
		$values = $row-1;
        for ($c=0; $c < $num-1; $c++) {
			$values = "$values" . ",'$data[$c]'";
        }
		$values = "$values" . ",'". $data[$num-1]. "'";
		$insert = "INSERT INTO $newTableName values($values);";
		//$conn->query ( $insert );
		if (!$conn->query($insert)){
			echo "Insert failed: (" . $conn->errno . ") " . $conn->error;
		}
    }
    fclose($handle);
}

mysqli_close($conn);
//redirect
header("Location: csvindex.php?table=$newTableName"); 
die; 
?>
