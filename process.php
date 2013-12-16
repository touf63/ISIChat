<?php

	/*
	 * \file : process.php
	 * \brief Server-side engine of the chat
	 * \author Christophe TETTARASSAR
	 * \year 2013
	*/

	$function = $_POST['function'];
	
	// array used for communication between process.js and process.php
	$log = array(); // this is THE array !

	// getState, update or send ?
	switch($function) {
		
		// Get the state of the chat
		case('getState'):
		
			if(file_exists('chat.txt')) {
				// gets all the lines of chat.txt
				$lines = file('chat.txt');
			}
			
			// counts lines and put it on log
			$log['state'] = count($lines); 
		
		break;	
		
		// Update the chat
		case('update'):
			
			// get the state by process.js
			$state = $_POST['state'];
			
			if(file_exists('chat.txt')) {
				
				// chat.txt is flushed one time per day
				/*if (date("d m Y", filemtime('chat.txt')) != date("d m Y")) {
					ftruncate('chat.txt', 0);
				}*/
		
				$lines = file('chat.txt');
			}
			
			$count = count($lines);
			
			// if there isn't update of the chat
			if($state == $count) {
				$log['state'] = $state;
				$log['text'] = false;
			}
			
			else {
				
				$text = array(); // array to put messages in
				$log['state'] = $state + count($lines) - $state; // updates the state
				
				// for each line of $lines
				foreach ($lines as $line_num => $line) {
		
					if($line_num >= $state) {
						$line = str_replace("\n", "", $line); // remove "\n" of the line
						$text[] =  $line; 
					}

				}
				// puts the whole text on log
				$log['text'] = $text; 
			}
			
			$dir = "./users"; // users' directory
			$users = array();
			
			if (is_dir($dir)) {
				
				// opens the "./users" directory
				if ($tmpdir = opendir($dir)) {
					
					// while there are users in /users
					while (($oneUser = readdir($tmpdir)) != false) {
						
						// files '.' (current) and '..' (parent) are not users !
						if (($oneUser != '.') && ($oneUser != '..')) {
							$users[] = $oneUser;
						}
					}
					
					// puts the users on log
					$log['users'] = $users;
				}
				closedir($tmpdir); //closes the "./users" directory
			}
			
		break;
		
		// Send a message
		case('send'):
			
			// Never trust the user, never never never !
			$nickname = htmlspecialchars($_POST['nickname']);
			$message = htmlspecialchars($_POST['message']);
			
			if(($message) != "\n") {
				
				$file = fopen('chat.txt', 'a'); // open "chat.txt"
				$time = date("H:i"); // what time is it ? (hour : minutes)
				$message = str_replace("\n", "", $message); // remove "\n" of the message
				
				// write nickname + date + message on file chat.txt
				fwrite($file, "<span>". $nickname . "</span> :: " . $time . " : <br/>" . $message . "\n"); 
				
				fclose($file); // close "chat.txt"
			}
			
		break;

	}
	
	// Encode the log array in json
	echo json_encode($log);
	
?>
