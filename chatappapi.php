<?php
	date_default_timezone_set('Asia/Calcutta');
	
	$db=mysql_select_db('nt_jtsys_test',mysql_connect('localhost', 'root', 'naaptol@123'))or die("cannot select DB");	
	
	$data = $text= $_REQUEST['data']; 

if(isset($data) && $data != null){

	$planText = decrypt($data);

	if(isset($planText) && $planText!=false) {
		$paramArray     = explode('|',$planText);
		$keyworddetails = isset($paramArray[0]) ? $paramArray[0] : "";
		$printflag	   	= isset($paramArray[4]) ? $paramArray[4] : "";
		$AccountID		= isset($paramArray[2]) ? $paramArray[2] : "";
		$profileID		= isset($paramArray[1]) ? $paramArray[1] : "";
		$text			= isset($paramArray[3]) ? $paramArray[3] : "";
	} else {
		echo "Authentication Fail";
		exit();
	}
}

////-----------------------Mysql escape string------------------------------------------/////
$keyworddetails = isset($keyworddetails) ? mysql_real_escape_string($keyworddetails) : $keyworddetails;
$printflag		= isset($printflag) ? mysql_real_escape_string($printflag) : $printflag;
$AccountID		= isset($AccountID) ? mysql_real_escape_string($AccountID) : $AccountID;
$profileID		= isset($profileID) ? mysql_real_escape_string($profileID) : $profileID;
$text			= isset($text) ? mysql_real_escape_string($text) : $text;
////-----------------------Mysql escape string------------------------------------------/////
	
switch($keyworddetails) 
{
    
	case 'version':
				$query=mysql_query("select appversion from jts_appmaster where `isactive`='1' ORDER BY pkappid DESC LIMIT 1");
				$q=mysql_fetch_array($query);
				
				echo  $msg = "latest App Version is".$q['appversion'].".";
				break;
	
	
      default:
              echo $msg = "Please Try Again".$data.".";
		

}


function decrypt($cipher, $key = null, $hmacSalt = null) {
	# Private salt
	$salt = 'ZfTfbip&Gs0Z4yz3ZfTfbip&Gs0Z4yz3';
	# Private key
	$key =  'SDefrfdrghgfdfE)SDefrfdrghgfdfE)';
	 
	if (empty($cipher)) {
		echo 'The data to decrypt cannot be empty.'; die();
	}
	if ($hmacSalt === null) {
		$hmacSalt = $salt;
	}

	$cipher = strtr($cipher, '-_,' , '+/=');
	
	$c 	   = base64_decode($cipher);
	$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
	$iv    = substr($c, 0, $ivlen);
	$hmac = substr($c, $ivlen, $sha2len=32);
	$ciphertext_raw = substr($c, $ivlen+$sha2len);
	$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
	$calcmac = hash_hmac('sha256', $ciphertext_raw, $salt, $as_binary=true);

	$msg     = explode("|",$original_plaintext);
	$apiTime = $msg[count($msg)-1];
	$time    = time();
	if (hash_equals($hmac, $calcmac) && ($apiTime - $time <15))
	{
		return $original_plaintext;
	} else {
		return false;
	}
	
}				
?>