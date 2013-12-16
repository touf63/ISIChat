/*
 * \file : process.js
 * \brief Client-side engine of the chat
 * \author Christophe TETTARASSAR
 * \year 2013
*/

var disable = false; // allow or not the AJAX execution
var state;	// state of Chat (by counting lines of chat.txt)
var file; // file : chat.txt

//definition of Chat (not really a class)
function Chat (){
	this.update = updateChat;
	this.send = sendChat;
	this.getState = getStateOfChat;
}

//gets the state of the chat
function getStateOfChat(){
	if(!disable){
		// AJAX : on
		disable = true;
		$.ajax({
			type: "POST",
			url: "process.php",
			data: {  
				'function': 'getState',
				'file': file
			},
			dataType: "json",
			success: function(data){
				// AJAX : off
				state = data.state;
				disable = false;
			},
		});
	}	 
}

//updates the chat
function updateChat(){
	if(!disable){
		// AJAX : on
		disable = true;
		$.ajax({
			type: "POST",
			url: "process.php",
			data: {  
				'function': 'update',
				'state': state,
				'file': file
			},
			dataType: "json",
			success: function(data){
				
				// if there are messages to display
				if(data.text) {
					// updates the chat-room
					for (var i = 0; i < data.text.length; i++) {
						$('#chat-area').append($("<p>" + data.text[i] + "</p>"));
					}								  
				}
				
				// if there are users to display
				if(data.users) {
					
					// flushs the users list
					document.getElementById("friend-list").innerHTML = "";
					
					// updates the users list
					for (var j = 0; j < data.users.length; j++) {
						$('#friend-list').append($("<p>" + data.users[j] + "</p>"));
					}
					
				}
				
				// automatic scroll of the chat-zone
				document.getElementById('chat-area').scrollTop = document.getElementById('chat-area').scrollHeight;
				
				// AJAX : off
				disable = false;
				state = data.state;
			},
		});
	}
	else {
		// just in case of non-call of the update function by chat.php
		setTimeout(updateChat, 1500);
	}
}

//send the message
function sendChat(message, nickname){       
	
	updateChat(); // call the function update of chat
	
	$.ajax({
		type: "POST",
		url: "process.php",
		data: {  
			'function': 'send',
			'message': message,
			'nickname': nickname,
			'file': file
		},
		dataType: "json",
		success: function(data){
			updateChat(); // recall the function update of chat
		},
	});
}
