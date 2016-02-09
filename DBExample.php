<?php
//Create a mySQL DB connection:
$dbhost = "166.62.8.11";
$dbuser = "auxstudDB5";
$dbpass = "auxstud5DB1!";
$dbname = "auxstudDB5";
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
if(! $conn ) {
	die('Could not connect: ' . mysql_error());
}        
mysql_select_db($dbname);

//
// Posts part
//
// delete btn pressed
if (!empty($_POST['delete'])) {
	$ids = $_POST['ids'];
	if(!empty($ids)) {
			foreach($ids as $id) {
			$result = mysql_query("DELETE FROM `items_209` WHERE id = $id");
			if(!$result){
			echo "Error";
			}else{
			}
		}	
	}
}

// insert btn pressed
if (isset($_POST['insert'])) {
	$img = $_POST['img'];
	$price = $_POST['price'];
	$store = $_POST['store'];
	$type = $_POST['type'];
	

	$sql = "INSERT INTO items_209 ". "(img, price,store,type) "
	. "VALUES('$img','$price','$store','$type')";// the id field generated automatically
        
    mysql_select_db('auxstudDB5');
    $retval = mysql_query( $sql, $conn );
    $insertId = mysql_insert_id();              
    if(! $retval ) {
    	die('Could not enter data: ' . mysql_error());
    }
}	
					
?>




<!DOCTYPE html>
<html>
	<head>
		<title>Angels</title>
	</head> 
	<body>
		
		<!-- simple view example -->
		<div>
			<h2>Select:</h2>
			<?php
			$result = mysql_query("SELECT `id`,`price`,`img`,`store`,`type` FROM `items_209`"); //select all
			// or
			$result = mysql_query("SELECT `id`,`price`,`img`,`store`,`type` FROM `items_209` WHERE id = '10'");//select condition
			if(!$result){
				echo "Error";
			}else{
				while($row = mysql_fetch_array($result, MYSQL_NUM)){
					?>
					<b>img</b> <img src="<?php echo $row[2]; ?>">| <b>price</b> <?php echo $row[1]; ?>
					<b>store</b> <?php echo $row[3]; ?> <b>type</b> <?php echo $row[4]; ?> <br>
					<?php
				}						
			}									
			mysql_free_result($result);	
			?>
		</div>
		<br>
		<!-- delete example -->
		<form method="post" action="DBExample.php">
			<fieldset>
				<legend>Delete:</legend>
				<?php
				$result = mysql_query("SELECT `id`,`price`,`img`,`store`,`type` FROM `items_209`"); // select rows to show for delete
				if(!$result){
					echo "Error";
				}else{
					?>
					
					<ul>
					<?php
					while($row = mysql_fetch_array($result, MYSQL_NUM)){  // for each row print <li>...</li>
					?>
						<li>
							<label>
								<input type="checkbox" name="ids[]" value="<?php echo $row[0]; ?>">img: <?php echo $row[2]; ?>  <!-- input value is the id to delete-->
							</label>
						</li>	
					<?php
					}									
				}								
				mysql_free_result($result);	
				?>
					</ul>
					<input type="submit" name="delete" value="submit">
				</fieldset>
		</form>	
		<br>
		<!-- insert example -->
		<form method="post" action="DBExample.php">
			<fieldset>
				<legend>Insert:</legend>
					<label></label><input type="text" name="img" placeholder="img url"></label><br>
					<label><input type="text" name="price" placeholder="price"></label><br>
					<label><input type="text" name="store" placeholder="store"></label><br>
					<label><input type="text" name="type" placeholder="type"></label><br>
					<label><input type="submit" name="insert" value="submit"></label>	
			</fieldset>
		</form>		
		
		  
	</body>
</html>