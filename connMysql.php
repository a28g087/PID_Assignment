<?php 
	//資料庫主機設定
	$db_host = "localhost";
	
	$db_username = "root";
	$db_password = "root";
	$db_name = "phpcart";

	$db_link=@new mysqli($db_host,$db_username,$db_password,$db_name);
	if($db_link->connect_error!=""){
		echo "資料庫連結失敗";
	}
	else{
		$db_link->query("SET NAMES 'utf8'");
	}
?>