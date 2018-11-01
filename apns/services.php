<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');

//----------------------------------------------------------------------------------------	
function sendMessage($sandbox, $payload,$devices,$certificate){		

	ini_set('display_errors','On'); 
	error_reporting(E_ALL);
	$passphrase = ''; 
	$ctx = stream_context_create();
	// Change 3 : APNS Cert File name and location.
	stream_context_set_option($ctx, 'ssl', 'local_cert', $certificate); 
	//stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
	// Open a connection to the APNS server
	$serverPath = 'ssl://gateway.push.apple.com:2195';
	if($sandbox)
		$serverPath = 'ssl://gateway.sandbox.push.apple.com:2195';

	$fp = stream_socket_client(
	    $serverPath, $err,
	    $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);


	if (!$fp){
		echo('{"status":"Failed to connect"}');
		die();
	}

	$result;

	foreach ($devices as $device) {
		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $device) . pack('n', strlen($payload)) . $payload;
		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));
	}

	if (!$result)
		echo('{"status":"failed"}');
	else
	    echo('{"status":"success"}');

	// Close the connection to the server
	fclose($fp);
	removeCertificate($certificate);
}
//-----------------------------------------------------------------------------------------------
function removeCertificate($file){
    unlink($file); // delete file	
}
//-----------------------------------------------------------------------------------------------
$opc = isset($_POST["action"])?$_POST["action"]:"";
if($opc=="push"){
	$tokensStr = isset($_POST["tokens"])?$_POST["tokens"]:"";
	$payload = isset($_POST["payload"])?$_POST["payload"]:"";	
	$server = isset($_POST["server"])?$_POST["server"]:"0";
	$certificate = isset($_FILES["certificate"])?$_FILES["certificate"]:null;
	$dir_subida = 'certificates/';
	$fichero_subido = $dir_subida . basename(date("dmyhms", time())."_". $_FILES['certificate']['name']);

	if (!move_uploaded_file($_FILES['certificate']['tmp_name'], $fichero_subido)) {		
		header('X-PHP-Response-Code: 401', true, 401);
		echo('{"status":"failed","error":"certificate error"}');
		die();
	}

	if($payload==""){
		header('X-PHP-Response-Code: 401', true, 401);
		echo('{"status":"failed","error":"payload error"}');
		die();
	}

	if($tokensStr==""){
		header('X-PHP-Response-Code: 401', true, 401);
		echo('{"status":"failed","error":"token error"}');
		die();
	}

	$isSandbox = true;
	if($server=="1")
		$isSandbox = false;

	$tokens = explode(',', $tokensStr);
	sendMessage($isSandbox,$payload,$tokens,$fichero_subido);
}
/*
$payload='{"aps": {"alert": {"title": "Acme title","body": "Acme description"},"badge": 1,"sound": "default"}}';
$pp="47ef8a4ee14a524bc5a9918a096972c5f427f4c3da297d57a9a0d54fc890ef78";
$tokens = explode(',', $pp);
$fichero_subido="certificates/apns-dev.pem";
sendMessage(true,$payload,$tokens,$fichero_subido);
*/
?>