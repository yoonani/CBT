<?php
/* =============================================================================
File : setup.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Desc 
	- 설치 파일
	- DB 설정
	- 전체관리자 설정
	- 디렉토리 권한 확인
============================================================================= */
?>
<HTML>
<HEAD>
	<TITLE>[Install - Step#1]</TITLE>
</HEAD>
<BODY>
<?
// 기본 디렉토리 설정
$cdir = realpath("./");
$baseDir = substr($cdir, 0, strrpos($cdir, "/"));
define("BASE_DIR", substr($cdir, 0, strrpos($cdir, "/")) );
$processOK = true;

// 디렉토리 권한 설정
// ./data/exam
// ./html/images/testimages
// ./html/images/swf
// 웹서버가 읽고 쓰고 지울수 있어야 한다.

$checkPermList = array("/data/exam", "/html/images/testimages", "/html/images/swf", "/include", "/include/setting.php");

echo "<UL>";

// 답안제출정보 저장 권한 확인
if(is_readable(BASE_DIR . $checkPermList[0]) AND is_writable(BASE_DIR . $checkPermList[0])) {
	echo "<LI>시험정보 디렉토리 권한 확인 : OK</LI>\n";
} else {
	echo "<LI><FONT COLOR='red'>시험정보 디렉토리 권한 확인 : FAILED<br /></FONT>\n";
	echo "- 다음과 같이 설정을 변경해 주세요<br />\n";
	echo "&nbsp;&nbsp;&nbsp;\$ chmod 707 " . BASE_DIR . $checkPermList[0] . "</LI>\n";
	$processOK = false;
}

// 이미지 저장 권한 확인
if(is_readable(BASE_DIR . $checkPermList[1]) AND is_writable(BASE_DIR . $checkPermList[1])) {
	echo "<LI>이미지 업로드 권한 확인 : OK</LI>\n";
} else {
	echo "<LI><FONT COLOR='red'>이미지 업로드 권한 확인 : FAILED</FONT>\n";
	echo "- 다음과 같이 설정을 변경해 주세요<br />\n";
	echo "&nbsp;&nbsp;&nbsp;\$ chmod 707 " . BASE_DIR . $checkPermList[1] . "</LI>\n";
	if($processOK) 
		$processOK = false;
}

// Flash File 저장 권한 확인
if(is_readable(BASE_DIR . $checkPermList[2]) AND is_writable(BASE_DIR . $checkPermList[2])) {
	echo "<LI>Flash File 업로드 권한 확인 : OK</LI>\n";
} else {
	echo "<LI><FONT COLOR='red'>Flash File 업로드 권한 확인 : FAILED</FONT>\n";
	echo "- 다음과 같이 설정을 변경해 주세요<br />\n";
	echo "&nbsp;&nbsp;&nbsp;\$ chmod 707 " . BASE_DIR . $checkPermList[2] . "</LI>\n";
	if($processOK) 
		$processOK = false;
}


// 설정 File 권한 확인
if(is_readable(BASE_DIR . $checkPermList[3]) AND is_writable(BASE_DIR . $checkPermList[3]) AND is_readable(BASE_DIR . $checkPermList[4]) AND is_writable(BASE_DIR . $checkPermList[4])) {
	echo "<LI>설정파일 권한 확인 : OK</LI>\n";
} else {
	echo "<LI><FONT COLOR='red'>설정파일 권한 확인 : FAILED</FONT>\n";
	echo "- 다음과 같이 설정을 변경해 주세요<br />\n";
	echo "&nbsp;&nbsp;&nbsp;\$ chmod -R 707 " . BASE_DIR . $checkPermList[3] . "</LI>\n";
	if($processOK) 
		$processOK = false;
}

echo "</UL>\n";


// 위의 세팅이 모두 맞춰졌는지 확인
if(!$processOK) {
	echo "<SCRIPT LANGUAGE='JavaScript'>\n";
	echo "\talert('위의 설정이 모두 처리되지 않아 계속 진행할 수 없습니다.\\n확인하신 후 새로고침을 하시면 계속 진행하실 수 있습니다.');\n";
	echo "</SCRIPT>";
	exit;
}

// 전체 설정 파일 변경
$rewrite = "define(\"MY_INCLUDE\", \"" . BASE_DIR . "/include/" . "\");
";

// 버퍼링 설정파일 변경
$bufConf = BASE_DIR . "/include/conf.php";
$cnt = 17;
$oldconf = file( $bufConf );
$oldconf[$cnt] = $rewrite;
$newconf = implode("", $oldconf);
$fp = fopen($bufConf, "w");
fwrite($fp, $newconf);
fclose($fp);

// no 버퍼링 설정파일 변경
$bufConf = BASE_DIR . "/include/confnoBuf.php";
$cnt = 15;
$oldconf = file( $bufConf );
$oldconf[$cnt] = $rewrite;
$newconf = implode("", $oldconf);
$fp = fopen($bufConf, "w");
fwrite($fp, $newconf);
fclose($fp);
?>
<P ALIGN="CENTER">[<A HREF="setup2.php">다음단계</A>]</P>
</BODY>
</HTML>
