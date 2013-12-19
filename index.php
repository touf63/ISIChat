<!--
 * \file : index.php
 * \brief Index of the chat + Form
 * \author Christophe TETTARASSAR
 * \year 2013
-->

<!DOCTYPE html>
<html lang="fr">

<head>
	
	<?php
		// multi-language engine
		include('./lang.php');
	?>
	
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title><?php echo $labelTitle; ?></title>
    
    <link rel="stylesheet" href="./design/style.css" type="text/css">
    
    <script type="text/javascript">
		// refresh the index page when another language is selected
		function refresh(value) {
			location.href="index.php?lang=" + value;
		}
    </script>
    
</head>

<body>
	
    <div id="page-content">
		
		<br><br>
		
		<div id="logo-chat">
			<img src="./design/logo.png" alt="ISIChat-logo" width="20%" height="20%">
		</div>
		
		<br>
		
		<h2><?php echo $labelTitle; ?></h2>
		
		<br><br>
		
		<?php
			// success message (ex : successfull logout)
			if(isset($_GET['logout'])) {   
				echo '<p id="success">&#10003; '.$labelLogout.'</p> <br>';
			}
			
			// failure message (ex : invalid username)
			if(isset($_GET['error'])) {
				echo '<p id="fail">&#10007; '.$labelError.'</p> <br>';
			}
		?>
		
		<form action="chat.php" method="post">
			
			<!-- nickname -->
			<p id="your_nickname"><?php echo $labelSurname; ?><input type="text" name="nickname" maxlength="20" value="<?php echo $_COOKIE['pseudo'];?>"></p>
			
			<br/>
			
			<!-- language -->
			<select name="language" onChange="refresh(this.value);">

				<option value="fr" <?php if(isset($_GET['lang']) && ($_GET['lang'] == "fr")){echo "selected=true";}?>>Français</option>
				<option value="en" <?php if(isset($_GET['lang']) && ($_GET['lang'] == "en")){echo "selected=true";}?>>English</option>
				<option value="es" <?php if(isset($_GET['lang']) && ($_GET['lang'] == "es")){echo "selected=true";}?>>Español</option>
				<option value="de" <?php if(isset($_GET['lang']) && ($_GET['lang'] == "de")){echo "selected=true";}?>>Deutsch</option>
				
			</select>
			
			<br><br><br>
			
			<!-- submit -->
			<p><input type="submit" id="submit_btn" value="OK"></p>
			
		</form>
		
		<div id="logo-isima">
			<img src="./design/isima.png" alt="ISIMA-logo" width="30%" height="30%">
		</div>
		
    </div>
</body>

</html>
