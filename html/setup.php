<?php
/* =============================================================================
File : setup.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Desc 
	- ��ġ ����
	- DB ����
	- ��ü������ ����
	- ���丮 ���� Ȯ��
============================================================================= */
?>
<HTML>
<HEAD>
	<TITLE>[Install - Step#1]</TITLE>
</HEAD>
<BODY>
<?
// �⺻ ���丮 ����
$cdir = realpath("./");
$baseDir = substr($cdir, 0, strrpos($cdir, "/"));
define("BASE_DIR", substr($cdir, 0, strrpos($cdir, "/")) );
$processOK = true;

// ���丮 ���� ����
// ./data/exam
// ./html/images/testimages
// ./html/images/swf
// �������� �а� ���� ����� �־�� �Ѵ�.

$checkPermList = array("/data/exam", "/html/images/testimages", "/html/images/swf", "/include", "/include/setting.php");

echo "<UL>";

// ����������� ���� ���� Ȯ��
if(is_readable(BASE_DIR . $checkPermList[0]) AND is_writable(BASE_DIR . $checkPermList[0])) {
	echo "<LI>�������� ���丮 ���� Ȯ�� : OK</LI>\n";
} else {
	echo "<LI><FONT COLOR='red'>�������� ���丮 ���� Ȯ�� : FAILED<br /></FONT>\n";
	echo "- ������ ���� ������ ������ �ּ���<br />\n";
	echo "&nbsp;&nbsp;&nbsp;\$ chmod 707 " . BASE_DIR . $checkPermList[0] . "</LI>\n";
	$processOK = false;
}

// �̹��� ���� ���� Ȯ��
if(is_readable(BASE_DIR . $checkPermList[1]) AND is_writable(BASE_DIR . $checkPermList[1])) {
	echo "<LI>�̹��� ���ε� ���� Ȯ�� : OK</LI>\n";
} else {
	echo "<LI><FONT COLOR='red'>�̹��� ���ε� ���� Ȯ�� : FAILED</FONT>\n";
	echo "- ������ ���� ������ ������ �ּ���<br />\n";
	echo "&nbsp;&nbsp;&nbsp;\$ chmod 707 " . BASE_DIR . $checkPermList[1] . "</LI>\n";
	if($processOK) 
		$processOK = false;
}

// Flash File ���� ���� Ȯ��
if(is_readable(BASE_DIR . $checkPermList[2]) AND is_writable(BASE_DIR . $checkPermList[2])) {
	echo "<LI>Flash File ���ε� ���� Ȯ�� : OK</LI>\n";
} else {
	echo "<LI><FONT COLOR='red'>Flash File ���ε� ���� Ȯ�� : FAILED</FONT>\n";
	echo "- ������ ���� ������ ������ �ּ���<br />\n";
	echo "&nbsp;&nbsp;&nbsp;\$ chmod 707 " . BASE_DIR . $checkPermList[2] . "</LI>\n";
	if($processOK) 
		$processOK = false;
}


// ���� File ���� Ȯ��
if(is_readable(BASE_DIR . $checkPermList[3]) AND is_writable(BASE_DIR . $checkPermList[3]) AND is_readable(BASE_DIR . $checkPermList[4]) AND is_writable(BASE_DIR . $checkPermList[4])) {
	echo "<LI>�������� ���� Ȯ�� : OK</LI>\n";
} else {
	echo "<LI><FONT COLOR='red'>�������� ���� Ȯ�� : FAILED</FONT>\n";
	echo "- ������ ���� ������ ������ �ּ���<br />\n";
	echo "&nbsp;&nbsp;&nbsp;\$ chmod -R 707 " . BASE_DIR . $checkPermList[3] . "</LI>\n";
	if($processOK) 
		$processOK = false;
}

echo "</UL>\n";


// ���� ������ ��� ���������� Ȯ��
if(!$processOK) {
	echo "<SCRIPT LANGUAGE='JavaScript'>\n";
	echo "\talert('���� ������ ��� ó������ �ʾ� ��� ������ �� �����ϴ�.\\nȮ���Ͻ� �� ���ΰ�ħ�� �Ͻø� ��� �����Ͻ� �� �ֽ��ϴ�.');\n";
	echo "</SCRIPT>";
	exit;
}

// ��ü ���� ���� ����
$rewrite = "define(\"MY_INCLUDE\", \"" . BASE_DIR . "/include/" . "\");
";

// ���۸� �������� ����
$bufConf = BASE_DIR . "/include/conf.php";
$cnt = 17;
$oldconf = file( $bufConf );
$oldconf[$cnt] = $rewrite;
$newconf = implode("", $oldconf);
$fp = fopen($bufConf, "w");
fwrite($fp, $newconf);
fclose($fp);

// no ���۸� �������� ����
$bufConf = BASE_DIR . "/include/confnoBuf.php";
$cnt = 15;
$oldconf = file( $bufConf );
$oldconf[$cnt] = $rewrite;
$newconf = implode("", $oldconf);
$fp = fopen($bufConf, "w");
fwrite($fp, $newconf);
fclose($fp);
?>
<P ALIGN="CENTER">[<A HREF="setup2.php">�����ܰ�</A>]</P>
</BODY>
</HTML>
