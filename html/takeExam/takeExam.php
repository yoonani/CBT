<?php
/* =============================================================================
File : takeExam/takeExam.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	���ٰ��� : A, B
	���躸�� Page
	- $_GET["examID"] : ���� ID
	- $_GET["myItem"] : ���� ������ ���� ID
	- $_GET["isFirst"] : "Y"�̸� ó�� ���� "N" �̰ų� ������ ������
============================================================================= */
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "B", "F")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
	exit;
}
$fnc->alertDiffWin("examWin", "�� �� ���� â�Դϴ�.");

$dataPath = DATA_PATH . "exam/" . $_GET["examID"] . "/" . $_SESSION["UID"] . "/";
if(!file_exists($dataPath)) {
	$fnc->alertBack("�������� ������ �����ϴ�.");
	exit;
} else {
	$solItems = file($dataPath . trim($_SESSION["ID"]));
	$totalItems = count($solItems);
}

$sql = "SELECT myTitle, myStart, myEnd FROM examAdmin WHERE myID = " . $_GET["examID"];
if(!$DB->query($sql)) {
	$fnc->alertBack("���������� ������ �� �����ϴ�.");
	exit;
}
$result = $DB->fetch();
$startTime = $fnc->psqlTime2UT($result[1]);
$endTime = $fnc->psqlTime2UT($result[2]);
if(time() < $startTime) {
	$fnc->alertBack("���� �ð��� ���� �ʾҽ��ϴ�.");
	exit;
}
if(time() > $endTime) {
	$fnc->alertBack("������ ����Ǿ����ϴ�.");
	exit;
}
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=euc-kr">
<title><?=$result[0]?></title>
<STYLE TYPE="text/css">
#T{font:8pt black;text-align: center; }
TD#B{background: white;text-align: center;}
</STYLE>
</head>
<body bgcolor="white" text="black" link="blue" vlink="purple" alink="red" topmargin="0" leftmargin="0">
<table border="0" width="800" cellpadding="0" cellspacing="0">
    <tr>
        <td width="200" height="120" background="<?=IMG_URL?>site/examskin/top_left.gif">
            <table border="0" width="200" height="120" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="200" height="20">&nbsp;</td>
                </tr>
                <tr>
                    <td width="200" height="20" style="font:bold 9pt black;">&nbsp;&nbsp;����� : <?=substr(trim($result[0]), 0, 10)?> </td>
                </tr>
                <tr>
                    <td width="200" height="20" style="font:bold 9pt black;">&nbsp;&nbsp;���۽ð� : <?=date("Y-m-d H:i", $startTime)?></td>
                </tr>
                <tr>
                    <td width="200" height="20" style="font:bold 9pt black;">&nbsp;&nbsp;����ð� : <?=date("Y-m-d H:i", $endTime)?></td>
                </tr>
                <tr>
                    <td width="200" height="20">&nbsp;</td>
                </tr>
                <tr>
                    <td width="200" height="20">&nbsp;</td>
                </tr>
            </table>
        </td>
        <td width="400" height="120" background="<?=IMG_URL?>site/examskin/notice.gif">&nbsp;</td>
        <td width="200" height="120" background="<?=IMG_URL?>site/examskin/top_right.gif">&nbsp;</td>
    </tr>
    <tr>
        <td width="600" colspan="2" height="88" background="<?=IMG_URL?>site/examskin/body.gif" valign="top">
            <table border="0" cellpadding="0" cellspacing="0" width="600">
                <!--
				���蹮�� ����
				-->
				<tr>
                    <td width="590" ALIGN="RIGHT"><IFRAME FRAMEBORDER="0" SRC="takeItem.php?examID=<?=$_GET["examID"]?>&myItem=<?=$_GET["myItem"]?>" NAME="innerExam" MARGINWIDTH="0" MARGINHEIGHT="0" SCROLLING="AUTO" WIDTH="580" HEIGHT="450"></IFRAME></td>
                </tr>
				<!--
				���蹮����
				-->
            </table>
        </td>
        <td width="200" height="88" valign="top" background="<?=IMG_URL?>site/examskin/left_bg.gif">
            <table border="0" cellpadding="0" cellspacing="0" width="200">
                <tr>
                    <td width="200" height="60" background="<?=IMG_URL?>site/examskin/answer.gif">&nbsp;</td>
                </tr>
                <tr>
                    <td width="200">
                        <table border="0" cellpadding="0" cellspacing="1" width="190" bgcolor="#90A0C4">
<?
$head = array();
$iStatus = array();
// ���������Ȳ ���
$j = 0;
for($i=0; $i < $totalItems; $i++) {
	$idx = $i + 1;
	if(!($i % 10)) {
		$headTmp =  "\t\t\t<TR>\n";
		$iStatusTmp =  "\t\t\t<TR>\n";
	}
	$headTmp .= "\t\t\t\t<TD ID=\"T\">" . $idx . "</TD>\n";
	// ���� ����
	$cStatus = substr(trim($solItems[$i]), -1, 1);
	if($cStatus) {
		$myLink = "<IMG SRC=\"" . IMG_URL . "site/icon/sol.gif\">";
	} else {
		$cItemID = intval(substr($solItems[$i], 0, 8));
		$myLink = $fnc->imgButtonNE(18, 19, "location.href='" . URL_ROOT. "takeExam/takeExam.php?examID=" . $_GET["examID"] . "&myItem=" . $cItemID. "&isFirst=N'", IMG_URL . "site/icon/no_sol.gif");
	}
	$iStatusTmp .= "\t\t\t\t<TD ID='B'>" . $myLink . "</TD>\n";
//	echo $headTmp;
//	echo "<br>" . $iStatusTmp;
	
	if(($i % 10) == 9 || $i == ($totalItems - 1)) {
		$headTmp .=  "\t\t\t</TR>\n";
		$iStatusTmp .=  "\t\t\t</TR>\n";
		$head[$j] = $headTmp;
		$iStatus[$j] = $iStatusTmp;
		$j++;
	}
}
for($i=0; $i < count($head); $i++) {
	echo $head[$i];
	echo $iStatus[$i];
}
?>

                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="800" colspan="3" height="20" background="<?=IMG_URL?>site/examskin/bottom.gif">&nbsp;</td>
    </tr>
</table>

</body>

</html>
<?
ob_end_flush();
?>
