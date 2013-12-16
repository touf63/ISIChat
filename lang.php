<?php

	/*
	 * \file : lang.php
	 * \brief Multi-language engine of the chat
	 * \author Christophe TETTARASSAR
	 * \year 2013
	*/

	header("Cache-Control: no-cache"); // flush cache
	$default_lang = 'fr'; // default language
	$dir_lang = './lang/'; // languages directory
	$extension = '.php'; // file extension
	
	$languages = array('fr', 'en', 'es', 'de'); // array with availables languages
	$lang = '';
	
	if (isset($_GET['lang']) && in_array($_GET['lang'], $languages)) {
		$lang = $_GET['lang'];
	}
	
	else if (isset($_POST['language']) && in_array($_POST['language'], $languages)) {
		$lang = $_POST['language'];
	}
	
	// always includes the default language file
	include($dir_lang . $default_lang . $extension);
	
	// or one of the others available languages when selected
	if (!empty($lang) && $lang != $default_lang && is_file($dir_lang. $lang . $extension)) {
		include($dir_lang . $lang . $extension);
	}	
	
?>
