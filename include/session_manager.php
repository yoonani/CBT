<?php
// $LIFETIME = get_cfg_var("session.gc_maxlifetime");

function sessionOpen($save_path, $session_name) {
	global $sessDB;
	if(!is_object($sessDB)) {
		echo "Session을 사용할 수 없습니다." . "<br />";
		$sessDB->error();
		exit;
	} else {
		$sessDB->connect();
		return true;
	}
}

function sessionRead ($session_key) { 
	global $sessDB; 
	$session_key = addslashes($session_key); 

	$sql = "SELECT session_value FROM sessions WHERE session_key = '$session_key'";
	if(!$sessDB->query($sql)) {
		return false;
	} else {
		$result = $sessDB->fetch();
	}

	if($sessDB->noRows() == 1) { 
		return $result[0]; 
	} else { 
		return false; 
	} 
}
 
function sessionWrite ($session_key, $val) { 
	global $sessDB; 
	$session_key = addslashes($session_key); 
	$val = addslashes($val); 

	$sql = "SELECT count(*) FROM sessions WHERE session_key = '$session_key'"; 
	if(!$sessDB->query($sql)) {
		echo $sql;
		echo "Session Write Error!" . "<br />";
		echo "Terminated...." . "<br />";
		echo $sessDB->error();
		exit;
	}
	$isSess = $sessDB->fetch();

	if ($isSess[0] == "0") { 
		$sql = "INSERT INTO sessions (session_key, session_expire, session_value) VALUES ('$session_key', " . time(). ", '$val')";
	} else { 
		$sql = "UPDATE sessions SET session_value = '$val', session_expire = " . time(). " WHERE session_key = '$session_key'"; 
	}
	$ret = $sessDB->query($sql); 
//	echo "Here!";
	return $ret;
}

function sessionDestroyer ($session_key) { 
	global $sessDB; 
	$session_key = addslashes($session_key); 
	$sql = "DELETE FROM sessions WHERE session_key = '$session_key'"; 
	$ret = $sessDB->query($sql); 
	return $ret;
}

function sessionGc ($maxlifetime) { 
	global $sessDB; 
	$expirationTime = time() - $maxlifetime; 
	$sql = "DELETE FROM sessions WHERE session_expire < $expirationTime"; 
	$ret = $sessDB->query($sql); 
	return $ret;
} 

function sessionClose() {
	global $sessDB; 
	$sessDB->close();
	return true;
}

//ini_set("session.save_handler", "user");
// echo ini_get("session.save_handler");

session_set_save_handler ( 
'sessionOpen', 
'sessionClose', 
'sessionRead', 
'sessionWrite', 
'sessionDestroyer', 
'sessionGc' 
); 

session_start();
?>
