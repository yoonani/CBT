<?php
/* =============================================================================
File : viewResult/viewResultList.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	���ٰ��� : A,  F
	�⺻������ �Ϸ�� ���� ����Ʈ�� �����ش�.
	- ��ü �����ڴ� �б���, �л�����, ������� ��µǰ� �л��̸��� Ŭ���ϸ�
	  ���� ������Ȳ�� �� �� �ִ�.
	- �б��� �����ڴ� �л�����, ������� ��µǰ� �л��̸��� Ŭ���ϸ�
	  ���� ������Ȳ�� �� �� �ִ�.
	- $noDBI�� 2�� �����Ͽ� ����Ѵ�.
	���޺���
	- GET["examID"]
============================================================================= */


$noDBI = 2;
$usePGNav = "Y";
require_once("../../include/conf.php");
require_once (MY_INCLUDE . "header.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "F")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
	exit;
}

switch($_SESSION["Level"]) {
	case "A" :
		$twidth = array(100, 100, 100, 100, 95, 95);
		$ttitle = array("�б�", "����", "�й�", "ID", "�����", "����");
		$sql = "SELECT a.mytitle, b.studname, b.univsno, c.myid, a.mycode FROM univinfo as a, univstudent as b, userinfo as c, examstudent as d WHERE a.mycode = b.univid AND b.univid = c.univid AND b.univsno = c.univsno AND c.myid = d.studentid AND d.examid = " . $_GET["examID"];
		break;
	case "F" :
		$twidth = array(120, 120, 120, 115, 115);
		$ttitle = array("����", "�й�", "ID", "�����", "����");
		$sql = "SELECT b.studname, b.univsno, c.myid FROM univstudent as b, userinfo as c, examstudent as d WHERE b.univid = c.univid AND b.univsno = c.univsno AND b.univid = '" . $_SESSION["UID"] . "' AND c.myid = d.studentid AND d.examid = ". $_GET["examID"];
}

if(!$DB[0]->query($sql)) {
	$fnc->alertBack("�����ڼ��� ������ �� �����ϴ�.");
	exit;
}

$total = $DB[0]->noRows();
$pn = new pgNav($RECPERPG, $PGPERBLK, $total);
$pn->initStart($_GET["myPg2"]);
$pgStart = ($pn->myPage-1) * $RECPERPG;

// ���᰹���� ����
$corNum = array();
$sqlS1 = "select count(*) from examsubmit where examid = " . $_GET["examID"] . " AND iscorrect = '1' GROUP BY userid";
if(!$DB[0]->query($sqlS1)) {
	$fnc->alertBack("���� ������ ���� �� �����ϴ�.");
	exit;
}
while($tmp = $DB[0]->fetch()) {
	array_push($corNum, $tmp[0]);
}


// ������ ����
$corScore = array();
$sqlS2 = "select sum(b.myscore)  from examsubmit as a, itemtable as b where a.itemid = b.myid AND a.examid = " . $_GET["examID"] . " AND a.iscorrect = '1' GROUP BY userid";
if(!$DB[0]->query($sqlS2)) {
	$fnc->alertBack("���� ������ ���� �� �����ϴ�.");
	exit;
}

while($tmp = $DB[0]->fetch()) {
	array_push($corScore, $tmp[0]);
}
?>

<TABLE ALIGN="CENTER" WIDTH="590" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="5"><img src="<?=IMG_URL?>site/examadmin/title_21-1.gif" border="0"></td>
</TR>
<TR>
	<TD>�����</TD>
	<TD>���</TD>
	<TD><?=sprintf("%5.2f", $fnc->getAVG($corNum))?></TD>
	<TD>ǥ������</TD>
	<TD><?=sprintf("%5.2f", $fnc->getSD($corNum))?></TD>
</tr>
<TR>
	<TD ROWSPAN="2">����</TD>
	<TD>���</TD>
	<TD><?=sprintf("%5.2f", $fnc->getAVG($corScore))?></TD>
	<TD>ǥ������</TD>
	<TD><?=sprintf("%5.2f", $fnc->getSD($corScore))?></TD>
</tr>
</TABLE>
<TABLE ALIGN="CENTER" WIDTH="590" cellpadding="0" cellspacing="0">
<TR>
	<TD ALIGN="CENTER">[<A HREF="./downResultFile.php?examID=<?=$_GET["examID"]?>">��� ���� �ޱ�</A>]</TD>
</TR>
</TABLE>
<TABLE ALIGN="CENTER" WIDTH="590" cellpadding="0" cellspacing="0">
<tr>
<TR>
<?

switch($_SESSION["Level"]) {
	case "A" :
		$sql2 = $sql . " ORDER BY a.mytitle, b.univsno DESC LIMIT " . $RECPERPG . " OFFSET " . $pgStart;
		break;
	case "F" :
		$sql2 = $sql . " ORDER BY b.univsno DESC LIMIT " . $RECPERPG . " OFFSET " . $pgStart;
}

for($i = 0; $i < count($twidth); $i++) {
?>
	<TD CLASS="title" WIDTH="<?=$twidth[$i]?>" HEIGHT="20" ALIGN="CENTER"><?=$ttitle[$i]?></TD>
<?
}

?>
</TR>
<?
if(!$DB[0]->query($sql2)) {
	$fnc->alertBack("�л������� ������ �� �����ϴ�.");
	exit;
}
?>
<TR>
<?
while($stdInfo = $DB[0]->fetch()) {
	switch($_SESSION["Level"]) {
		case "A" :
			$scCode = $stdInfo[4];
?>
	<TD CLASS="cleft" HEIGHT="20" ALIGN="CENTER"><?=trim($stdInfo[0])?></TD>
	<TD CLASS="ccenter" ALIGN="CENTER"><?=trim($stdInfo[1])?></TD>
<?
			$nextIdx = 2;
			break;
		case "F" :
			$scCode = $_SESSION["UID"];
			$nextIdx = 1;
?>
	<TD CLASS="cleft" HEIGHT="20" ALIGN="CENTER"><?=trim($stdInfo[0])?></TD>
<?
			break;
	}

	$sql3 = "SELECT count(a.iscorrect), sum(b.myscore) FROM examsubmit as a, itemtable as b WHERE a.itemid = b.myid AND a.examid = " . $_GET["examID"] . " AND a.userid ='" . trim($stdInfo[$nextIdx+1]). "' AND a.iscorrect='1'";

	if(!$DB[1]->query($sql3)) {
		$fnc->alertBack("���������� ������ �� �����ϴ�.");
		exit;
	}

	$stdResult = $DB[1]->fetch();
	
?>
	<TD CLASS="ccenter" ALIGN="CENTER"><?=trim($stdInfo[$nextIdx])?></TD>
	<TD CLASS="ccenter" ALIGN="CENTER"><?=trim($stdInfo[$nextIdx+1])?></TD>
	<TD CLASS="ccenter" ALIGN="CENTER">
<?
	$isNS = true;
	if($stdResult[0] == 0) {
		echo "<FONT COLOR='RED'>������</FONT>";
	} else {
		$isNS = false;
		echo $stdResult[0] . "��";
	}
?>
	</TD>
	<TD CLASS="cright" ALIGN="CENTER">
<?
	if($isNS) {
		echo "<FONT COLOR='RED'>������</FONT>";
	} else {
		echo $stdResult[1] . "��";
	}
?>
	</TD>
</TR>
<?
}
?>
</TABLE>
<BR />

<!-- Page Navigation //-->
<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
<TR>
	<TD>&nbsp;</TD>
	<TD align="center">
<?
if($pn->isPrevBLK() ) {
?>
<A HREF="<?=$_SERVER["PHP_SELF"]?>?examID=<?=$_GET["examID"]?>&myPg2=<?=$pn->prevBLKptr?>&">[���� <?=$PGPERBLK?>Page]</A>
<?
}

if($pn->isPrev() ) {
?>
<A HREF="<?=$_SERVER["PHP_SELF"]?>?examID=<?=$_GET["examID"]?>&myPg2=<?=$pn->prevPG?>"><img src="<?=IMG_URL?>site/icon/pge_pre.gif" border="0" align="absmiddle"></A>
<?
}

$pn->prtPage("?examID=".$_GET["examID"]."&myPg2", "[", "]");

if($pn->isNext() ) {
?>
<A HREF="<?=$_SERVER["PHP_SELF"]?>?examID=<?=$_GET["examID"]?>&myPg2=<?=$pn->nextPG?>"><img src="<?=IMG_URL?>site/icon/pge_next.gif" border="0" align="absmiddle"></A>
<?
}

if($pn->isNextBLK() ) {
?>
<A HREF="<?=$_SERVER["PHP_SELF"]?>?examID=<?=$_GET["examID"]?>&myPg2=<?=$pn->nextBLKptr?>">[���� <?=$PGPERBLK?>Page]</A>
<?
}
?>
	</TD>
	<TD>&nbsp;</TD>
</TR>
</TABLE>
<?
require_once (MY_INCLUDE . "closing.php");
?>
