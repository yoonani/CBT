<?php
/* =============================================================================
File : setup2Proc.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Desc 
	- 설치 파일 2단계 완료하고 DB Test 후 관리자 정보를 입력한다.
	- setting.php File을 수정한다.
============================================================================= */
?>
<HTML>
<?php
$myHost = trim($_POST["getHost"]);
$myPort = trim($_POST["getPort"]);
$myUser = trim($_POST["getUser"]);
$myPassword = trim($_POST["getPassword"]);
$myDBName = trim($_POST["getDBName"]);
$myAID = trim($_POST["getAID"]);
$myAPWD = trim($_POST["getAPWD"]);
$myEMail = trim($_POST["getEMail"]);
$mySiteName = trim($_POST["getSiteName"]);

require_once("../include/useful.php");
$linkStr = " dbname=" . $myDBName . " user=" . $myUser . " password=" . $myPassword;
if($myPort != "5432") {
	$linkStr = "port=" . $myPort . " " . $linkStr;
}
if($myHost != "localhost") {
	$linkStr = "host=" . $myHost . " " . $linkStr;
}

$conn = @pg_connect($linkStr);
if(!is_resource($conn)) {
	$errMsg = "\\n현재 설정값은 다음과 같습니다.\\nHost : " . $myHost . "\\nPort : " . $myPort . "\\nUser : " . $myUser . "\\nPassword : " . $myPassword . "\\nDB Name : " . $myDBName;

	$fnc->alertBack("DB 설정이 잘못되었거나 존재하지 않는 DB 정보입니다." . $errMsg);
	exit;
}


// 기본 디렉토리 설정
$cdir = realpath("./");
$baseDir = substr($cdir, 0, strrpos($cdir, "/"));
define("BASE_DIR", substr($cdir, 0, strrpos($cdir, "/")) );


// DB 설정 파일 변경
$w2DBSet = $myHost . "\n" . $myUser . "\n" . $myPassword . "\n" . $myDBName;
$fp = fopen("../include/dbSet.php", "w");
fwrite($fp, $w2DBSet);
fclose($fp);


// table 생성
$tableSql = file(BASE_DIR . "/sqls/tables.sql");
$next = reset($tableSql);
while($next) {
//	echo str_replace("''", "'", $next);
	$result = pg_query($conn, str_replace("''", "'", trim($next)));
	if(!$result) {
		$fnc->alert("Table을 생성할 수 없습니다.\\nDB설정을 확인해 주세요");
		exit;
	}
	$next = next($tableSql);
}

// setting.php 변경
$settingFile = "<?php\n";
$settingFile .=  $mySiteName . "\n";
$settingFile .= BASE_DIR . "/\n";
$settingFile .= "http://" . $_SERVER["HTTP_HOST"] . substr($_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["REQUEST_URI"], "/")+1) . "\n";
$settingFile .= BASE_DIR . "/html/\n";
$settingFile .= BASE_DIR . "/include/\n";
$settingFile .= "512\n";
$settingFile .= "5\n";
$settingFile .= "?>";

$fp = fopen("../include/setting.php", "w");
fwrite($fp, $settingFile);
fclose($fp);


// 전체관리자 추가
$sql1 =  "INSERT INTO univinfo VALUES ('00', '관리본부')";
$sql2 =  "INSERT INTO staffinfo VALUES ('" . $myAID . "', md5('" . $myAPWD . "'), 'A', '00', '전체관리자', '관리본부', '000000', '" . $myEMail . "')";
$result = pg_query($conn, $sql1);
if(!$result) {
	$fnc->alert("DB를 사용할 수 없습니다.\\nPostgreSQL에서 직접 다음과 같이 입력해 주시기 바랍니다.\\n\\n" . $sql1 . "\\n" . $sql2);
	exit;
}
$result = pg_query($conn, $sql2);
if(!$result) {
	$fnc->alert("DB를 사용할 수 없습니다.\\nPostgreSQL에서 직접 다음과 같이 입력해 주시기 바랍니다.\\n\\n" . $sql2);
	exit;
}
?>
<SCRIPT LANGUAGE="JavaScript">
	alert("설치가 완료되었습니다.\n첫 화면으로 이동후 전체 관리자로 로그인해 주세요");
	location.href="./index.php";
</SCRIPT>
