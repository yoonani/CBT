<?php
/* =============================================================================
File : itemAdmin/itemIndex.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	���ٰ��� : A
	���躰 ���װ�����
============================================================================= */
$noDBI=2;
$usePGNav = "Y";
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "header.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
	exit;
}

$myExamStatus = array ("E" => "������", "R" => "�ǽ���", "D" => "����");

$sql = "SELECT myTitle, myStart, myEnd, myItemNo, myStatus FROM examAdmin WHERE myID = " . $_GET["examID"];
if(!$DB[0]->query($sql)) {
	$fnc->alertBack("Query�� ������ �� �����ϴ�.");
	exit;
}
$result = $DB[0]->fetch();
?>
<table width="590" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2" width="590">
			<img src="<?=IMG_URL?>site/examadmin/title_2.gif" border="0">
		</td>
	</tr>
	<tr>
		<td class="htitle" align="center">�����</td>
		<td class="htop"><?=$result[0]?></td>
	</tr>
	<tr>
		<td class="htitle" align="center">���۽ð�</td>
		<td class="hmiddle"><?=$result[1]?></td>
	</tr>
	<tr>
		<td class="htitle" align="center">����ð�</td>
		<td class="hmiddle"><?=$result[2]?></td>
	</tr>
	<tr>
		<td class="htitle" align="center">���׼�</td>
		<td class="hbottom"><?=$result[3]?></td>
	</tr>
	<tr>
		<td class="htitle" align="center">�������</td>
		<td class="hbottom"><?=$myExamStatus[$result[4]]?>
<?
	if($result[4] == "D") {
		echo " [<A HREF=\"" . URL_ROOT . "exam/viewResult.php?examID=" . $_GET["examID"] . "\">�������</A>]";
	}
?>
</td>
	</tr>
</table>
<?
$sql = "SELECT count(*) FROM ExamItem WHERE examID = " . $_GET["examID"];
if(!$DB[0]->query($sql)) {
	$fnc->alertBack("Query�� ������ �� �����ϴ�.");
	exit;
}
$total = $DB[0]->getResult(0, 0);
?>
��ü <?=$total?>���� ������ �ֽ��ϴ�.

<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="5"><img src="<?=IMG_URL?>site/examadmin/title_4.gif" border="0"></td>
	</tr>
	<TR height="20">
		<TD class="title" align="center">����</TD>
		<TD class="title" align="center">Group</TD>
		<TD class="title" align="center">����</TD>
		<TD class="title" align="center">����</TD>
		<TD class="title" align="center">����</TD>
	</TR>
<?
$sql = "SELECT b.myText, b.isIndep, b.myID FROM ExamGroup as a, ItemGroupTable as b WHERE  a.groupID = b.myID AND a.ExamID = " . $_GET["examID"];
if(!$DB[0]->query($sql)) {
	$fnc->alertBack("��ü Group���׼��� ������ �� �����ϴ�.");
	exit;
}
$total = $DB[0]->noRows();
$pn = new pgNav($RECPERPG, $PGPERBLK, $total);
$pn->initStart($_GET["myPg"]);
$pgStart = ($pn->myPage-1) * $RECPERPG;

$sql = "SELECT b.myText, b.isIndep, b.myID FROM ExamGroup as a, ItemGroupTable as b  WHERE  a.groupID = b.myID AND a.ExamID = " . $_GET["examID"] . " ORDER BY myID ASC LIMIT " . $RECPERPG . " OFFSET " . $pgStart;
if(!$DB[0]->query($sql)) {
//	echo $sql;
	$fnc->alertBack("Group������ ������ �� �����ϴ�.");
	exit;
}
$i = $pgStart + 1;
$myCorrect = "";
$myScore = "";
while($grpInfo = $DB[0]->fetch()) {
	if($grpInfo[1] == "N") {
		// Group������ ���
		$myLinkStr = URL_ROOT . "itemAdmin/viewGrpItem.php?examID=" . $_GET["examID"] . "&myPg=" . $pn->myPage . "&myGrpID=" . $grpInfo[2]."&isIndep=" .$grpInfo[1];
		$myCorrect = "-";
		$myScore = "-";
		$isGroup = "Group";
	} else  {
		// ���������� ��� ���������� ��� �����´�.
		$sql2 ="SELECT a.itemID, b.isObject, b.myCorrect, b.myScore FROM ItemGInfoTable as a JOIN ItemTable as b  ON (a.itemID = b.myID) WHERE a.groupID = " . $grpInfo[2];
		if(!$DB[1]->query($sql2)) {
			$fnc->alertBack("Group������ ������ �� �����ϴ�.");
			exit;
		}
		$indepInfo = $DB[1]->fetch();
		$myLinkStr = URL_ROOT . "itemAdmin/viewItem.php?examID=" . $_GET["examID"] . "&myPg=" . $pn->myPage . "&itemID=" . $indepInfo[0] . "&myGrpID=" . $grpInfo[2]."&isIndep=" .$grpInfo[1];

		// �ܺ� ������ ���
		if ($indepInfo[1] == "Y") {
				$grpInfo[0] = "�ܺ�����";
		}
		$myCorrect = $indepInfo[2];
		$myScore = $indepInfo[3];
		$isGroup = "-";
	}
?>
	<TR>
		<TD class="cleft" align="center"><?=$i?></TD>
		<TD class="ccenter" align="center"><?=$isGroup?></TD>
		<TD class="ccenter" style="padding-left:15px"><A HREF="<?=$myLinkStr?>"><?=substr(strip_tags($grpInfo[0]), 0, 20)?></A></TD>
		<TD class="ccenter" align="center"><?=$myCorrect?></TD>
		<TD class="cright" align="center"><?=$myScore?></TD>
	</TR>
<?
	$i++;
}
?>
</TABLE>

<!-- Page Navigation //-->
<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
	<TR>
		<TD>&nbsp;</TD>
		<TD align="center">
<?
if($pn->isPrevBLK() ) {
?>
<A HREF="<?=$_SERVER["PHP_SELF"]?>?examID=<?=$_GET["examID"]?>&myPg=<?=$pn->prevBLKptr?>">[���� <?=$PGPERBLK?>Page]</A>
<?
}

if($pn->isPrev() ) {
?>
<A HREF="<?=$_SERVER["PHP_SELF"]?>?examID=<?=$_GET["examID"]?>&myPg=<?=$pn->prevPG?>"><img src="<?=IMG_URL?>site/icon/pge_pre.gif" border="0" align="absmiddle"></A>
<?
}

$pn->prtPage("?examID=" . $_GET["examID"] . "&myPg", "[", "]");

if($pn->isNext() ) {
?>
<A HREF="<?=$_SERVER["PHP_SELF"]?>?examID=<?=$_GET["examID"]?>&myPg=<?=$pn->nextPG?>"><img src="<?=IMG_URL?>site/icon/pge_next.gif" border="0" align="absmiddle"></A>
<?
}

if($pn->isNextBLK() ) {
?>
<A HREF="<?=$_SERVER["PHP_SELF"]?>?examID=<?=$_GET["examID"]?>&myPg=<?=$pn->nextBLKptr?>">[���� <?=$PGPERBLK?>Page]</A>
<?
}
?>
		</TD>
		<TD>&nbsp;</TD>
	</TR>
	<tr>
		<td colspan="3" align="right">
			<A HREF="<?=URL_ROOT?>itemAdmin/addItem.php?examID=<?=$_GET["examID"]?>&isIndep=Y&myPg=<?=$pn->myPage?>"><img src="<?=IMG_URL?>site/icon/add_ques.gif" border="0"></A><A HREF="<?=URL_ROOT?>itemAdmin/addGroup.php?examID=<?=$_GET["examID"]?>&myPg=<?=$pn->myPage?>"><img src="<?=IMG_URL?>site/icon/add_group.gif" border="0"></A>
		</td>
	</tr>
</TABLE>

<?
require_once (MY_INCLUDE . "closing.php");
?>
