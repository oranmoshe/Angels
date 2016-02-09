<?php

date_default_timezone_set('Asia/Jerusalem');

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
//Testing connection success
if(mysqli_connect_errno()) {
	die("DB connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")"
	);
}

// Set cookie
$valueCookie = "4";
$cookie_name = "angelCookie";
setcookie($cookie_name, $valueCookie);

// Checks if logged in
if(!isset($_COOKIE[$cookie_name])) {
	header("Location: login.php"); /* Redirect browser */
	exit();
}

// Get User Details
$result = mysql_query("SELECT `FirstName`, `LastName`, `Level`, `ProfilePic`, `ProfilePic40` FROM `tbl_angels_users_205` WHERE UserID = '$_COOKIE[$cookie_name]'");
if(!$result){
	echo "Error";
}else{
	$row = mysql_fetch_array($result, MYSQL_NUM);
	$loggedUserFname = $row[0];
	$loggedUserLname = $row[1];
	$loggedUserLevel = $row[2];
	$loggedUserPic = $row[3];
	$loggedUserPicMin = $row[4];
}
mysql_free_result($result);

// Delete User

if (isset($_POST['delete'])) {
	$users = $_POST['users'];
	if(!empty($users)) {
		foreach($users as $user) {
			$result = mysql_query("DELETE FROM `tbl_angels_users_205` WHERE UserID = $user");
			if(!$result){
				echo "Error";
			}else{
			}
		}
	}
}




?>
<!DOCTYPE html>
<html>
	<head>
		<title>Angels</title>
		<meta charset="UTF-8" />
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Josefin+Sans:400,100,100italic,300,700,700italic,600italic,600,400italic,300italic' rel='stylesheet' type='text/css'>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="includes/authgrow.js"></script>
		<script src="includes/scripts.js"></script>	
		<link href="includes/style.css" rel="stylesheet">
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="http://demo.kaazing.com/lib/client/javascript/StompJms.js" type="text/javascript" language="javascript"></script>
	</head>
	<body>
		<header>
			<div id="headerLogo">
				<a href="#"></a>
			</div>
			<div id="headerSearch">
				<input type="search" results="5" class="tb" placeholder=" Search Tasks, Friends etc'">
			</div>
			<nav>
				<ul>
					<li><a href="#"><img src="images/header/settings.png"></a></li>
					<li><a href="#"><img src="images/header/shop.png"></a></li>
					<li><a href="#"><img src="images/header/tasks.png"></a></li>
					<li><a href="#"><img src="images/header/notifications.png"></a></li>
					<li><a href="index.php"><img src="images/header/home.png"></a></li>
					<li><a href="#"><img src="images/profile/<?php echo $loggedUserPicMin;?>"></a></li>
					<li id="humburger">
						<a href="#"><img src="images/icons/iconHumburger.png"></a>
					</li>
				</ul>
			</nav>
			</nav>
			<div class="clear"></div>
		</header>
		<div id="wrapper">
			<section id="leftSec">
				<article class="box">
					<nav>
						<ul>
							<li>
							<a href="#"><img src="images/icons/iconTasks.png">
								Tasks</a>
							</li>
							<li>
							<a href="#"><img src="images/icons/iconFriends.png">
								Users</a>
							</li>
							<li>
							<a href="#"><img src="images/icons/iconMessages.png">
								Messages</a>
							</li>
							<li>
							<a href="#"><img src="images/icons/iconWallet.png">
								Shop</a>
							</li>
							<li>
							<a href="#"><img src="images/icons/iconSettings.png">
								Settings</a>
							</li>
						</ul>
					</nav>
				</article>
				<article class="box">
					<h2><img src="images/icons/iconUser.png">Top Users</h2>
						<ul>
							<?php
							// Get User details
								$result = mysql_query("SELECT `FirstName`, `LastName`, `Level`, `ProfilePic30` FROM `tbl_angels_users_205` ORDER BY `UserID` DESC LIMIT 0,10");
								while ($row = mysql_fetch_array($result, MYSQL_NUM)){
									if(!$result){
										echo "Error";
									}else{										
										$fname = $row[0];
										$lname = $row[1];
										$level = $row[2];
										$pic = $row[3];
										?>
										<li>
											<a href="#"><img src="images/profile/<?php echo $pic;?>"> <span class="golden"><?php echo $fname." ".$lname;?></span></a>
										</li>
										<?php
									}
								}
							?>
						</ul>
				</article>
			</section>
			<main id="bigMain">
				<article class="box">
					<h2>
						Last Tasks						
					</h2>
					<article class="menu">
						<span>Action</span>
						<nav class="box">
							<ul>
								<li>
									<a href="#"> Finished</a>
								</li>
								<li>
									<a href="#"> Edit</a>
								</li>
								<li>
									<a href="#"> Delete</a>
								</li>
								<li>
									<a href="#"> Turn Off Nonifications</a>
								</li>
							</ul>
						</nav>
					</article>
				
					<article class="tableView">
					  <table>
						<?php 	
						// Get Task details						
						$result = mysql_query("SELECT `TaskID`,`UserID`,`Description`, `Time`,`Location`, `Category`,`SubCategory`,`Value` FROM `tbl_angels_tasks_205`  ORDER BY `Time` DESC LIMIT 0,10");

						if(!$result){
							echo "Error";
						}else{
							while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
								$category = $row[5];
								$subcategory = $row[6];
								$location = $row[4];
								$body = $row[2];
								$time = $row[3];
								$value = $row[7];
								$userid = $row[1];
								$taskid = $row[0];
								?>
								<tr>
								<?php
								// Get User details
								$result2 = mysql_query("SELECT `FirstName`, `LastName`, `Level`, `ProfilePic30` FROM `tbl_angels_users_205` WHERE UserID = '$userid'");
								if(!$result2){
									echo "Error";
								}else{
									$row2 = mysql_fetch_array($result2, MYSQL_NUM);
									$fname = $row2[0];
									$lname = $row2[1];
									$level = $row2[2];
									$pic = $row2[3];
									?>
									<td><input type="checkbox" name="users[]"></td>
									<td><?php 
										$format = 'Y-m-d H:i:s';
										$date = DateTime::createFromFormat($format, $time);
										$now = new DateTime();
										
										if($date < $now) {
										    echo "<div class=\"day\"><span>".$date->format('d')."</span></div>";
										}else{
											echo "<div class=\"day golden\"><span>".$date->format('d')."</span></div>";
										}
										?></td>
									<td><img src="images/profile/<?php echo $pic; ?>" class="profile30"></td>
									<td><span class="golden"><?php echo $fname ." ". $lname; ?></span></td>
									<td><?php echo substr($body,0,20); ?></td>
									<td><?php echo rand(10,100); ?>km</td>
									<td><?php echo $subcategory; ?></td>
									<td>edit</td>
								</tr>
									<?php
								}
								mysql_free_result($result2);
							}
						}
						mysql_free_result($result);
						?>
					  </table>
					</article>
				</article>
				<article class="box">
					<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<h2>
							Users						
						</h2>					
						<article class="menu">
							<span>Action</span>
							<nav class="box" id="managerAction">
								<ul>
									<li>
										<a href="#"  class="lightBoxOppener" data-lightbox-id="delete">Delete</a>
									</li>
									<li>
										<a href="#"> Block</a>
									</li>
								</ul>
							</nav>
						</article>
						<div class="searchbar"><input type="search" results="5" class="tb" placeholder="Find Users..'"></div>
						<?php
							// Get User details
							$result = mysql_query("SELECT `FirstName`, `LastName`, `Level`, `ProfilePic40`,`Address`, `UserID` FROM `tbl_angels_users_205`");
							while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
								if(!$result){
									echo "Error";
								}else{
									$fname = $row[0];
									$lname = $row[1];
									$level = $row[2];
									$pic = $row[3];
									$address = $row[4];
									$userid = $row[5];
									?>
									<div class="userBox">	
										<div class="boxProfilePicker">
											<input type="checkbox" name="users[]" value="<?php echo $userid; ?>">
										</div>								
										<div class="boxProfilePic">
											<img src="images/profile/<?php echo $pic; ?>">
										</div>
										<div class="boxProfileInfo">					
											<span class="golden">
												<?php
													echo $fname. " ".$lname;
												 ?>
											</span>
											<br>
											<span class="ProfileLevel">
												<?php
													echo $address;
												 ?>
											</span>
										</div>
										<div class="clear"></div>
									</div>
									<?php
								}
							}
							mysql_free_result($result);
						?>
						<div class="clear"></div>
						<!-- light box -->
						<div id="delete" class="lightBoxContainer">
							<section class="lightBox">
								<article class="box">
									<h2>Delete User <img src="images/icons/iconClose.png" class="closeLightBox"></h2>
									<h3 class="alert red">Are you sure you want to delete <?php echo $fname . " " . $lname; ?>
									user?</h3>
									<div>
										<input type="submit" value="Send" alt="Submit" name="delete" class="btn">
										<div class="clear"></div>
									</div>
								</article>
							</section>
						</div>
					</form>
				</article>
			</main>
			<div class="clear"></div>
		</div>
		<script>
			(function() {									
				init();
			})();
		</script>
	</body>
</html>
 <?php 
 mysql_close($conn); ?>