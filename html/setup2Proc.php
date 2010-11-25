<?php
/* =============================================================================
File : setup2Proc.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Desc 
	- ��ġ ���� 2�ܰ� �Ϸ��ϰ� DB Test �� ������ ������ �Է��Ѵ�.
	- setting.php File�� �����Ѵ�.
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
	$errMsg = "\\n���� �������� ������ �����ϴ�.\\nHost : " . $myHost . "\\nPort : " . $myPort . "\\nUser : " . $myUser . "\\nPassword : " . $myPassword . "\\nDB Name : " . $myDBName;

	$fnc->alertBack("DB ������ �߸��Ǿ��ų� �������� �ʴ� DB �����Դϴ�." . $errMsg);
	exit;
}


// �⺻ ���丮 ����
$cdir = realpath("./");
$baseDir = substr($cdir, 0, strrpos($cdir, "/"));
define("BASE_DIR", substr($cdir, 0, strrpos($cdir, "/")) );


// DB ���� ���� ����
$w2DBSet = $myHost . "\n" . $myUser . "\n" . $myPassword . "\n" . $myDBName;
$fp = fopen("../include/dbSet.php", "w");
fwrite($fp, $w2DBSet);
fclose($fp);


// table ����
$tableSql = file(BASE_DIR . "/sqls/tables.sql");
$next = reset($tableSql);
while($next) {
//	echo str_replace("''", "'", $next);
	$result = pg_query($conn, str_replace("''", "'", trim($next)));
	if(!$result) {
		$fnc->alert("Table�� ������ �� �����ϴ�.\\nDB������ Ȯ���� �ּ���");
		exit;
	}
	$next = next($tableSql);
}

// setting.php ����
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


// ��ü������ �߰�
$sql1 =  "INSERT INTO univinfo VALUES ('00', '��������')";
$sql2 =  "INSERT INTO staffinfo VALUES ('" . $myAID . "', md5('" . $myAPWD . "'), 'A', '00', '��ü������', '��������', '000000', '" . $myEMail . "')";
$result = pg_query($conn, $sql1);
if(!$result) {
	$fnc->alert("DB�� ����� �� �����ϴ�.\\nPostgreSQL���� ���� ������ ���� �Է��� �ֽñ� �ٶ��ϴ�.\\n\\n" . $sql1 . "\\n" . $sql2);
	exit;
}
$result = pg_query($conn, $sql2);
if(!$result) {
	$fnc->alert("DB�� ����� �� �����ϴ�.\\nPostgreSQL���� ���� ������ ���� �Է��� �ֽñ� �ٶ��ϴ�.\\n\\n" . $sql2);
	exit;
}
?>
<SCRIPT LANGUAGE="JavaScript">
	alert("��ġ�� �Ϸ�Ǿ����ϴ�.\nù ȭ������ �̵��� ��ü �����ڷ� �α����� �ּ���");
	location.href="./index.php";
</SCRIPT>
