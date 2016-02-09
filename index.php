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


// Post Status
if (isset($_POST['submit'])) {
	$category = $_POST['category'];
	$subcategory = $_POST['subCategory'];
	$location = "RamatGan, Rehovot Hanahar 22";
	$body = $_POST['body'];
	$date = $_POST['date'];
	$time = $_POST['time'];
	$dt = $date . " " . $time .":00";
	$email = $_POST['email']; 
	$invites = $_POST['invites'];

	// Create task 
	$sql = "INSERT INTO tbl_angels_tasks_205 ". "(UserID, Description, Time,Location,Category,SubCategory,Value) "
	. "VALUES('$_COOKIE[$cookie_name]','$body','$dt','$location','$category','$subcategory','0')";
        
    mysql_select_db('auxstudDB5');
    $retval = mysql_query( $sql, $conn );
    $insertId = mysql_insert_id();              
    if(! $retval ) {
    	die('Could not enter data: ' . mysql_error());
    }
         
    // Invite friends to participate
	if(!empty($invites)) {
		foreach($invites as $invite) {
			$sql = "INSERT INTO tbl_angels_participations_205 ". "(TaskID, UserID, Status) "
			. "VALUES('$insertId',$invite,'0')";
		               
		    mysql_select_db('auxstudDB5');
		    $retval = mysql_query( $sql, $conn );
		            
		    if(! $retval ) {
		    	die('Could not enter data: ' . mysql_error());
		    }
		}
	}
	
	// Redirect to task page
	header("Location: task.php?tid=".$insertId); 
	exit();
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
					<?php
						$result = mysql_query("SELECT `FirstName`, `LastName`, `Level`, `ProfilePic`, `Coins` FROM `tbl_angels_users_205` WHERE UserID = '$_COOKIE[$cookie_name]'");
						if(!$result){
							echo "Error";
						}else{
							$row = mysql_fetch_array($result, MYSQL_NUM);
							$fname = $row[0];
							$Lname = $row[1];
							$level = $row[2];
							$pic = $row[3];
							$coins = $row[4];
						}
						mysql_free_result($result);						
					?>
					<div class="boxProfilePic">
						<img src="images/profile/<?php echo $pic; ?>">
					</div>
					<div class="boxProfileInfo">					
						<span class="golden">
							<?php
							echo $fname. " ".substr($Lname,0,1) ;
							 ?>
						</span>
						<br>
						<span class="ProfileLevel">
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
					<div class="boxProfileWallet">
						<img src="images/icons/iconCoinsGold.png">
						<span> You earned <b><?php echo $coins; ?></b> Coins</span>
					</div>
				</article>
				<article class="box">
					<nav>
						<ul>
							<li>
							<a href="#"><img src="images/icons/iconTasks.png">
								Tasks</a>
							</li>
							<li>
							<a href="#"><img src="images/icons/iconFriends.png">
								Friends</a>
							</li>
							<li>
							<a href="#"><img src="images/icons/iconMessages.png">
								Messages</a>
							</li>
							<li>
							<a href="#"><img src="images/icons/iconWallet.png">
								Wallet</a>
							</li>
							<li>
							<a href="#"><img src="images/icons/iconShop.png">
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
					<h2><img src="images/icons/iconTasks.png">Task Queue</h2>
						<ul>
							<?php
								$result = mysql_query("SELECT `TaskID` FROM `tbl_angels_participations_205` WHERE UserID = '$_COOKIE[$cookie_name]' AND Status = '1'");
								if(!$result){
									echo "Error";
								}else{
									while($row = mysql_fetch_array($result, MYSQL_NUM)){
										$result2 = mysql_query("SELECT `Description`,`Time` FROM `tbl_angels_tasks_205` WHERE TaskID = '$row[0]'");
										if(!$result2){
											echo "Error";
										}else{
											while($row2 = mysql_fetch_array($result2, MYSQL_NUM)){
											?>	
												<li>
													<a href="task.php?tid=<?php echo $row[0];?>"><?php echo substr($row2[0],0,20); echo (strlen($row2[0]) > 19 ? '..':''); ?></a>
												</li>
											<?php
											}	
										}
										mysql_free_result($result2);
									}						
								}								
								mysql_free_result($result);						
							?>
						</ul>
				</article>
				<article class="box">
					<h2><img src="images/icons/iconCuopons.png"> Coupons</h2>
						<ul>
							<li>
								<a href="#">Aromma</a>
							</li>
							<li>
								<a href="#">Adir Miller</a>
							</li>
						</ul>
				</article>
			</section>
			<main>
				<article id="newPost">
					<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<a><span><img src="images/icons/iconPhotoGold.png"> Add Photo/Video</span></a>
						<a><span><img src="images/icons/iconDocumentGold.png"> Add Document</span></a>
						<br>
						<label><input placeholder="Need some help?"  class="tb postTask" required></label>
						<br>
						<section class="extendedInput">
							<label>
								<select name="category" class="tb category">
									<option value="people" selected>People</option>
									<option value="pets">Pets</option>
									<option value="elder">Elder</option>
									<option value="children">Children</option>
								</select>
							</label>
							<label>
								<select name="subCategory" class="tb category">
									<option value="health" selected>Health</option>
									<option value="mobility">Mobility</option>
									<option value="food">Food</option>
									<option value="shelter">Shelter</option>
								</select>
							</label>
							<br>
							<label><input type="date" name="date" class="tb time" required></label>
							<label><input type="time" name="time" class="tb time" required></label>
						</section>
						<br>
						<a><img src="images/icons/iconPinGold.png"><span>Ramat Gan, Anna Frank</span></a>
						<a id="startLightBox" class="lightBoxOppener" data-lightbox-id="inviteFriends"><img src="images/icons/iconFriendsGold.png"><span>Invite Friends</span></a>
						<input type="image" src="images/buttons/btnPost.png" class="btn"  type="submit" name="submit" value="Submit Form">
						<input type="image" src="images/buttons/btnPublic.png" alt="Submit" class="btn">
						<br>
						<div class="clear"></div>
						<!-- light box -->
						<div id="inviteFriends" class="lightBoxContainer">
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
				<article class="box">
					<h2>
						Angels Map
					</h2>
					<p>
						<img src="images/mapIndex.png">
					</p>
				</article>
				<?php 	
						// Get Task details						
						$result = mysql_query("SELECT `TaskID`,`UserID`,`Description`, `Time`,`Location`, `Category`,`SubCategory`,`Value` FROM `tbl_angels_tasks_205` LIMIT 0,5");

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
								// Get User details
								$result2 = mysql_query("SELECT `FirstName`, `LastName`, `Level`, `ProfilePic40` FROM `tbl_angels_users_205` WHERE UserID = '$userid'");
								if(!$result2){
									echo "Error";
								}else{
									$row2 = mysql_fetch_array($result2, MYSQL_NUM);
									$fname = $row2[0];
									$lname = $row2[1];
									$level = $row2[2];
									$pic = $row2[3];
									?>
										<article>
											<div class="postPic"><img src="images/profile/<?php echo $pic; ?>"></div>
											<div class="postBody">
												<div class="postContact">
													<span class="golden"><?php echo $fname ." ". $lname; ?></span>
													 in 
													<span class="golden"><?php echo $location ?></span> 
													4km
													<img src="images/icons/iconArrival.png">
												</div>
												<div class="postTimes">
													1 day ago . 89k views
												</div>
												<div class="postContent">
													<a href="task.php?tid=<?php echo $taskid ?>"><?php echo substr($body,0,70); ?><br><span class="readmore">read more..</span></a>
												</div>												
												<div class="activity">
												<a href="#">	<span><img src="images/icons/iconLikeGoldSmall.png">3.4k</span>
													<span><img src="images/icons/iconShareGoldSmall.png">1.2k</span>
													<span><img src="images/icons/iconCommentGoldSmall.png">3.2k</span></a>
												</div>
											</div>
											<div class="clear"></div>
										</article>
									
									<?php
								}
							}
						}
						mysql_free_result($result);
					?>
			</main>
			<aside>
				<article class="box">
					<h2>Hot Coupons</h2>
					<figure>
						<a href="#"><img src="images/cuopons/bag.png" alt="Michael Kors">
						<figcaption>Michael Kors<br><span><img src="images/icons/iconCoinsGold.png">1025 coins</span></figcaption></a>
					</figure>
					<figure>
						<a href="#"><img src="images/cuopons/s-l400.jpg" alt="Michael Kors">
						<figcaption>GoPro HERO4<br><span><img src="images/icons/iconCoinsGold.png">800 coins</span></figcaption></a>
					</figure>
				</article>
			</aside>
			<div class="clear"></div>
		</div>
		<script>
			(function() {									
				init();
			})();
		</script>
	</body>
</html>
 <?php mysql_close($conn); ?>