<!--
 * \file : chat.php
 * \brief The chat itself
 * \author Christophe TETTARASSAR
 * \year 2013
-->

<!DOCTYPE html>
<html lang="fr">

<head>
	
	<?php
		include('./lang.php'); // multi-language
	?>
	
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title><?php echo $labelTitle; ?></title>
    
    <link rel="stylesheet" href="./design/style.css" type="text/css">
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
	
    <script type="text/javascript" src="./process.js"></script>
	
	<!-- Sorry for the huge javascript code below, I tried to put it somewhere else, but in vain :/ -->
    <script type="text/javascript">
    
        // get the nickname from index.php form by POST method
        <?php
			
			// Redirection to index.php when an error occured
			function back_to_index() {
				$destIndex = "index.php?error=true&lang=" . $lang;
				header("Location: $destIndex");
				exit;
			}
			
			if(!isset($_GET['exit'])) { 
				
				// regular expression to control user's nickname
				if (ereg('^[a-zA-Z]{1,20}[0-9]{0,19}$', $_POST['nickname']))  {
					
					$nickname = $_POST['nickname'];
					$session_file = './users/' . $nickname;
					
					// if the nickname is unavailable :
					if(file_exists($session_file)) {
						back_to_index();
					}
					
					else {
						session_start(); // start session
						$_SESSION['name'] = $nickname; // initialize session
						
						// KEEP CALM and EAT COOKIE
						setcookie('pseudo',$nickname, time() + 365*24*3600, null, null, false, true);
						
						// creates a file in users directory
						$session_file = './users/' . $_SESSION['name'];	
						
						// opens the file with a die just in case
						$handle = fopen($session_file, 'a') or die('Cannot open file:  '.$session_file);
					}
				
				}
				else {
					back_to_index();
				}
			}
			else {
				$session_file = './users/' . $_GET['name'];
				
				unlink($session_file); // Destruction of user-session's file
				session_destroy();	// Destruction of session
				
				$destIndex = "index.php?logout=true&lang=" . $lang;
				header("Location: $destIndex"); // Redirection to index.php
				exit;
			}
		?>
		
		// PHP --> JS of nickname variable
        var name = "<?php echo $_SESSION['name']; ?>";
    	
    	// creation of a new chat
        var chat =  new Chat();
        
        // display to all users that I'm online
        var helloText = "<?php echo $labelHello; ?>";
		chat.send(helloText, name);	
			
		$(function() {
			
			// get the state of the chat (by calling the fonction getState of chat)
    		chat.getState(); 
    		 
			// listener on textarea for key press (down)
			// event e1
			$("#toSend").keydown(function(e1) {  
			 
				var key = e1.which;  
		   
				//all keys including return.  
				if (key >= 33) {
				   
					var maxLength = $(this).attr("maxlength");  
					var length = this.value.length;  
					 
					// don't allow new content if length is maxed out
					if (length >= maxLength) {  
						e1.preventDefault();  
					}  
				}  
			});
																																					 
    		 // listener on textarea for release (up) of key press
			 // event e2
    		 $('#toSend').keyup(function(e2) {	
    		 		
				  // "Enter" Key (13)
    			  if (e2.keyCode == 13) { 
    			  
                    var text = $(this).val();
    				var maxLength = $(this).attr("maxlength");  
                    var length = text.length; 
                     
                    // send 
                    if (length <= maxLength + 1) { 
						// call the function send of chat
    			        chat.send(text, name);	
    			        $(this).val("");
                    } 
					else {
    					$(this).val(text.substring(0, maxLength));
    				}			
    			}
            });
			
    	});
    	
    	//If user wants to end session  
    	$(document).ready(function(){  
			$("#exit").click(function(){  
				var exit = confirm("<?php echo $labelConfirmExit; ?>");  
				
				if(exit==true){
					// display to all users that I'm logout
					var exitText = "<?php echo $labelBye; ?>";
					chat.send(exitText, name);	
					window.location = "chat.php?exit=true&name=<?php echo $_SESSION['name']; ?>&lang=<?php echo $lang; ?>";
				}        
			});  
		});
		
		// Special thanks to openclassroom.com for this amazing part of code
		// Of course I have adapted it in consideration of my own code :)
		// This function handle the text selection and cursor replacing
		$(document).ready(function(){ 
			$(".bbcode").click(function(){  
				
				var field  = document.getElementById("toSend"); 
				var scroll = field.scrollTop;
				
				switch (this.value) {
					// bold
					case 'b':
						var startTag = "[b]";
						var endTag = "[/b]";
					break;
					
					// italic	
					case 'i':
						var startTag = "[i]";
						var endTag = "[/i]";
					break;
					
					// underline
					case 'u':
						var startTag = "[u]";
						var endTag = "[/u]";
					break;	
				}
				
				
				field.focus();
				
				// Taking care of Internet Explorer
				if (window.ActiveXObject) { 
					var textRange = document.selection.createRange();            
					var currentSelection = textRange.text;
							
					textRange.text = startTag + currentSelection + endTag;
					textRange.moveStart("character", -endTag.length - currentSelection.length);
					textRange.moveEnd("character", -endTag.length);
					textRange.select();     
				} 
				
				// For the others
				else { 
					var startSelection = field.value.substring(0, field.selectionStart);
					var currentSelection = field.value.substring(field.selectionStart, field.selectionEnd);
					var endSelection = field.value.substring(field.selectionEnd);
							
					field.value = startSelection + startTag + currentSelection + endTag + endSelection;
					field.focus();
					field.setSelectionRange(startSelection.length + startTag.length, startSelection.length + startTag.length + currentSelection.length);
				} 

				field.scrollTop = scroll;
				
			});
		});
		
		<?php
			// exit handling
			if(isset($_GET['exit'])){   
				$session_file = './users/' . $_GET['name'];
				
				unlink($session_file); // Destruction of user-session's file
				session_destroy();	// Destruction of session
				
				$destIndex = "index.php?logout=true&lang=" . $lang;
				header("Location: $destIndex"); // Redirection to index.php
				exit;
			}
		?>
		
    </script>

</head>

<!-- The body often updates the chat, one update per second -->
<body onload="setInterval('chat.update()', 1000)">

    <div id="page-content">
		
		<div id="logo-chat">
			<img src="./design/logo.png" alt="ISIChat-logo" width="20%" height="20%">
		</div>
		
		<br>
		
		<h2><?php echo $labelTitle; ?></h2>
		
		<br>
		
		<!-- Display user's nickname -->
        <h3><?php echo $labelWelcome . $_SESSION['name']; ?></h3>
		
		<!-- logout "button" -->
		<p id="logout">
			<a id="exit" href="#"><?php echo $labelExit; ?></a>
		</p>
		
		<br>
        
        <div id="chat-zone">
		
			<div id="chat-area">
				<!-- Chat Area -->
			</div>
			
		</div>
        
		<!-- "Your message :" -->
        <h3><?php echo $labelMsg; ?></h3>
			
        <br>
        
        <!-- Text formatting tool, using BBCode -->
		<span>
			<button value="b" class="bbcode"><b>B</b></button>
			<button value="i" class="bbcode"><em>I</em></button>
			<button value="u" class="bbcode"><u>U</u></button>
			<br/>
		</span>
		
		<br/>
        
        <form id="send-message-zone">
			
			<!-- "to Send" message text area -->
            <textarea id="toSend" class="textarea-bbcode" maxlength="1000"></textarea>
			
        </form>
        
        <div id="friend-zone">
			
			<!-- "Users online :" -->
			<h3><?php echo $labelFriends; ?></h3>
			
			<div id="friend-list">
				<!-- List of online users -->
			</div>
			
        </div>
		
		<br>
		
		<div id="logo-isima">
			<img src="./design/isima.png" alt="ISIMA-logo" width="30%" height="30%">
		</div>
    
    </div>

</body>

</html>
