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





// Post: Join
if (!empty($_POST['join'])) {
   	// Register current user to task  
	$sql = "INSERT INTO tbl_angels_participations_205 ". "(TaskID, UserID, Status) "
	. "VALUES('".$_GET['tid']."','$_COOKIE[$cookie_name]','1')";
        
    mysql_select_db('auxstudDB5');
    $retval = mysql_query( $sql, $conn );
    $insertId = mysql_insert_id();              
    if(! $retval ) {
    	die('Could not enter data: ' . mysql_error());
    }
       
}

// Post: Leave
if (!empty($_POST['leave'])) {
   	// Register current user to task 
	$result = mysql_query("DELETE FROM `tbl_angels_participations_205` WHERE UserID = $_COOKIE[$cookie_name]");
	if(!$result){
	echo "Error";
	}else{
	}
}

// Task Details
						// Get Task details						
						$result = mysql_query("SELECT `UserID`,`Description`, `Time`,`Location`, `Category`,`SubCategory`,`Value` FROM `tbl_angels_tasks_205` WHERE TaskID = '". $_GET['tid']."'");
						if(!$result){
							echo "Error";
						}else{
							while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
								$category = $row[4];
								$subcategory = $row[5];
								$location = $row[3];
								$body = $row[1];
								$time = $row[2];
								$value = $row[6];
								$userID = $row[0];
							}
						}
						// Get User details
						$result = mysql_query("SELECT `FirstName`, `LastName`, `Level`, `ProfilePic` FROM `tbl_angels_users_205` WHERE UserID = '$userID'");
						if(!$result){
							echo "Error";
						}else{
							$row = mysql_fetch_array($result, MYSQL_NUM);
							$fname = $row[0];
							$lname = $row[1];
							$level = $row[2];
							$pic = $row[3];
						}
						mysql_free_result($result);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Angels</title>
		<meta charset="UTF-8" />
		<link href="includes/style.css" rel="stylesheet">
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Josefin+Sans:400,100,100italic,300,700,700italic,600italic,600,400italic,300italic' rel='stylesheet' type='text/css'>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="includes/authgrow.js"></script>
		<script src="includes/scripts.js"></script>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
	</head>
	<body id="taskPage">
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
			<div class="clear"></div>
		</header>
		<div id="askToLeave" class="lightBoxContainer">
			<section class="lightBox">
				<article class="box">
					<h2>Leave Task <img src="images/icons/iconClose.png" class="closeLightBox"></h2>
					<h3 class="red">Do you sure you want leave <?php echo $fname . " " . $Lname; ?> task?.. </h3>
					<div>	
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']."?tid=".$_GET['tid']; ?>">
							<input type="submit" value="Leave" alt="Submit" name="leave" class="btn">
							<div class="clear"></div>
						</form>			
					</div>	
				</article>
			</section>
		</div>
		<div id="askToJoin" class="lightBoxContainer">
			<section class="lightBox">
				<article class="box">
					<h2>Ask To Join <img src="images/icons/iconClose.png" class="closeLightBox"></h2>
					<h3>Ask <?php echo $fname . " " . $lname; ?> to join this task..</h3>
					<div>	
						<label><input placeholder="Need some help?" class="tb toTextarea"></label>
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']."?tid=".$_GET['tid']; ?>">
							<input type="submit" value="Send" alt="Submit" name="join" class="btn">
							<div class="clear"></div>
						</form>		
					</div>
				</article>
			</section>
		</div>
		<div id="wrapper">
			<main>
				<article class="alert green">
					<img src="images/icons/iconSuccess.png" class="icon">
					<span> Your task created successfully! </span>
					<img src="images/icons/iconClose.png" class="close" id="alertClose">
				</article>
				<article class="box profile">
					<div class="pic">
						<img src="images/profile/<?php echo $pic;?>">
					</div>
					<div class="info">					
						<span class="golden"><?php echo $fname. " ".$lname;?></span>
						<br>
						<span class="level">
							<?php
							switch ($level) {
									case 0:
									 	echo "Gold Angel";
										break;
									case 1:
									     echo "Silver Angel";
									     break;
									case 2:
									     echo "White Angel";
									     break;
									default:
									 	 echo "Good Angel";
								}
								?>
						</span>
					</div>
					<div class="clear"></div>
				</article>
				<article class="box details">
					<div class="row"><div><img src="images/icons/iconTime.png"><span><?php echo $time;?></span></div></div>
					<div class="row"><div>&nbsp;<img src="images/icons/iconPinGold.png"><span><?php echo $location;?></span></div></div>
					<div class="row"><div><img src="images/icons/iconCarBig.png"><span><?php echo $category;?> - <?php echo $subcategory;?></span></div></div>
				</article>
				<div class="clear"></div>
				<article class="box description">
					<div class="postContent">
						<?php echo $body;?>					
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']."?tid=".$_GET['tid']; ?>">
							<?php 
							// Checks if user already registered and turn flag
							$result = mysql_query("SELECT `TaskID`, `UserID`, `Status` FROM `tbl_angels_participations_205` WHERE TaskID = '". $_GET['tid'] ."' AND UserID = '$_COOKIE[$cookie_name]'");	
							$isRegistered = 0;
							if(!$result){
								echo "Error";
							}else{
								while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
									$isRegistered = 1;
								}
							}
							if(!$isRegistered){ ?>
							<span class="btn lightBoxOppener" data-lightbox-id="askToJoin">Join</span>
							<?php }else{ ?>
							<span class="btn lightBoxOppener" data-lightbox-id="askToLeave">Cancele Join</span>
							<?php
							}
							?>
						</form>
					</div>
					<div class="activity">
						<span><img src="images/icons/iconLikeGoldSmall.png">0</span>
						<span><img src="images/icons/iconShareGoldSmall.png">0</span>
					</div>
				</article>
				<article id="newPost">
					<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<a><span><img src="images/icons/iconPhotoGold.png"> Add Photo/Video</span></a>
						<a><span><img src="images/icons/iconDocumentGold.png"> Add Document</span></a>
						<br>
						<label><input placeholder="Need some help?" class="tb  postComment"></label>
						<section  class="extendedInput">
							<a><img src="images/icons/iconPinGold.png"><span>Ramat Gan, Anna Frank</span></a>
							<a id="startLightBox" class="lightBoxOppener" data-lightbox-id="dimScreen"><img src="images/icons/iconFriendsGold.png"><span>Tag Friends</span></a>
							<input type="image" src="images/buttons/btnPost.png" class="btn"  type="submit" name="submit" value="Submit Form">
						</section>
						<div class="clear"></div>
						<div id="dimScreen" class="lightBoxContainer">
							<section class="lightBox">
								<article class="box">
									<h2>Invite Friends <img src="images/icons/iconClose.png" class="closeLightBox"></h2>
									<section>
										<div class="findFriend">
											<div>
												<input type="search" results="5" class="tb" placeholder="Find Friends..">
											</div>
											<div>
												<ul id="friends">
													<?php
														$arrayOfFriends=array();
														$result = mysql_query("SELECT `FriendAID`,`FriendBID`  FROM `tbl_angels_friends_205` WHERE FriendAID = '$_COOKIE[$cookie_name]' OR  FriendBID = '$_COOKIE[$cookie_name]'");
														if(!$result){
															echo "Error";
														}else{
															while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
																if($row[0] == $_COOKIE[$cookie_name]){
																	array_push($arrayOfFriends,$row[1]);
																}
																else{
																	array_push($arrayOfFriends,$row[0]);
																}
															}
														}
														$result = mysql_query("SELECT `FirstName`,`LastName`, `ProfilePic`, `UserID`  FROM `tbl_angels_users_205`"); /* can be more effectual  */
														if(!$result){
															echo "Error";
														}else{
															while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
																if (in_array($row[3], $arrayOfFriends)){
																	echo "<li><label><input type=\"checkbox\" name=\"invites[]\" value=\"".$row[3]." \"><img src=\"images/profile/".$row[2]."\"><span>".$row[0]." ".$row[1]."</span></label></li>";
																}
															}
														}
														mysql_free_result($result);
													?>							
													<div class="clear"></div>
												</ul>
											</div>
										</div>
										<div class="selectedFriend">
											<span>selected</span>
											<span id="sumSelected">0</span>
											<div>
												<ul id="friends_queue">
													<div class="clear"></div>
												</ul>
											</div>
											<div class="clear"></div>
										</div>
										
									</section>
									<div class="clear"></div>
								</article>
								<article class="boxClose">
									<img  src="images/buttons/invite.png" alt="Submit" class="btn closeLightBox">
									<div class="clear"></div>
								</article>
							</section>
						</div>
					</form>
				</article>			
			</main>
			<aside>
				<article class="box friends">
					<h2>Invite Friends</h2>
					<?php
						// Invite Friends Bind
						$arrayOfFriends=array();
						$counterGo = 0;
						$counterMaybe = 0;
						$counterInvited = 0;
						$result = mysql_query("SELECT `UserID`,`Status` FROM `tbl_angels_participations_205` WHERE TaskID = '". $_GET['tid']."'");
						if(!$result){
							echo "Error";
						}else{
							while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
								switch((int)$row[1]){
									case 0:
									$counterInvited++;
									break;
									case 1:
									$counterGo++;
									array_push($arrayOfFriends,$row[0]);
									break;
									case 2:
									$counterMaybe++;
									break;
								}
							}
						}
						if($counterGo > 0){								
							$result = mysql_query("SELECT `ProfilePic40`, `UserID`  FROM `tbl_angels_users_205`"); /* can be more effectual  */
							if(!$result){
								echo "Error";
							}else{
								echo "<ul>";
								while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
									if (in_array($row[1], $arrayOfFriends)){
										echo "<li><img src=\"images/profile/".$row[0]."\"></li>";
									}
								}
								echo "</ul><div class=\"clear\"></div>";
							}
						}
						mysql_free_result($result);
					?>
					
					<div>
						<div><span><?php echo $counterGo; ?></span><br><span>Going</span></div>
						<div><span><?php echo $counterMaybe; ?></span><br><span>Maybe</span></div>
						<div><span><?php echo $counterInvited; ?></span><br><span>Invited</span></div>
						<div class="clear"></div>
					</div>
					
				</article>
				<article class="box">
					<h2>Attached</h2>
					<div>
						<figure id="pics">
						<img src="images/attached/img1.png" alt="Michael Kors">
						<figcaption>My Grandfather</figcaption>
						</figure>						
					</div>
					<div class="clear"></div>
				</article>
				<article class="box">
					<h2>How to get here</h2>
					<figure id="miniMap">
						<img src="images/smallMap.png" alt="map">
						<figcaption><?php echo $location;?></figcaption>
					</figure>
					<div class="clear"></div>
				</article>
			</aside>
			<div class="clear"></div>
		</div>
		<a href="taskAfter.html">Later..</a>
		<script>
			(function() {									
				init();
			})();
		</script>
	</body>
</html>
 <?php mysql_close($conn); ?>