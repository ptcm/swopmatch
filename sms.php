<?php

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$username = $_POST['user'];
		$password = $_POST['password'];
		$recipients = $_POST['number'];
		$sender = $_POST['senderid'];
		$body = $_POST['text'];
	} 

?>
<html>
<head>
<title>SMSs</title>
</head>
<body>
SMS Machine!
</h2>
<form action="http://api.bluedotsms.com/api/mt/SendSMS" method = "post">
<p align="left">
User Name :
<p align="left"><input type="Text"name="user" value="devtest"></p>
Password :
<p align="left"><input type="Text"name="password" value="password12"></p>
Sender ID :
<p align="left"><input type="Text"name="senderid" value="SwopMatch Handler"></p>
Recipient :
<p align="left"><input type="text"name="number" value="0775263810"></p>
Body :
<p align="left"><input type="Text"name="text" value="just testing functionality!!"></p>
<h3 align="left"><input type="submit" value="Submit">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

<input type="submit" value="Reset"></h3>
</html>