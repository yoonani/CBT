<?php
/* =============================================================================
File : itemAdmin/itemIndex.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	접근가능 : A
	시험별 문항관리자
============================================================================= */
$noDBI=2;
$usePGNav = "Y";
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "header.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
	exit;
}

$myExamStatus = array ("E" => "출제중", "R" => "실시중", "D" => "종료");

$sql = "SELECT myTitle, myStart, myEnd, myItemNo, myStatus FROM examAdmin WHERE myID = " . $_GET["examID"];
if(!$DB[0]->query($sql)) {
	$fnc->alertBack("Query를 수행할 수 없습니다.");
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
		<td class="htitle" align="center">시험명</td>
		<td class="htop"><?=$result[0]?></td>
	</tr>
	<tr>
		<td class="htitle" align="center">시작시간</td>
		<td class="hmiddle"><?=$result[1]?></td>
	</tr>
	<tr>
		<td class="htitle" align="center">종료시간</td>
		<td class="hmiddle"><?=$result[2]?></td>
	</tr>
	<tr>
		<td class="htitle" align="center">문항수</td>
		<td class="hbottom"><?=$result[3]?></td>
	</tr>
	<tr>
		<td class="htitle" align="center">시험상태</td>
		<td class="hbottom"><?=$myExamStatus[$result[4]]?>
<?
	if($result[4] == "D") {
		echo " [<A HREF=\"" . URL_ROOT . "exam/viewResult.php?examID=" . $_GET["examID"] . "\">결과보기</A>]";
	}
?>
</td>
	</tr>
</table>
<?
$sql = "SELECT count(*) FROM ExamItem WHERE examID = " . $_GET["examID"];
if(!$DB[0]->query($sql)) {
	$fnc->alertBack("Query를 수행할 수 없습니다.");
	exit;
}
$total = $DB[0]->getResult(0, 0);
?>
전체 <?=$total?>개의 문제가 있습니다.

<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="5"><img src="<?=IMG_URL?>site/examadmin/title_4.gif" border="0"></td>
	</tr>
	<TR height="20">
		<TD class="title" align="center">순번</TD>
		<TD class="title" align="center">Group</TD>
		<TD class="title" align="center">문항</TD>
		<TD class="title" align="center">정답</TD>
		<TD class="title" align="center">배점</TD>
	</TR>
<?
$sql = "SELECT b.myText, b.isIndep, b.myID FROM ExamGroup as a, ItemGroupTable as b WHERE  a.groupID = b.myID AND a.ExamID = " . $_GET["examID"];
if(!$DB[0]->query($sql)) {
	$fnc->alertBack("전체 Group문항수를 가져올 수 없습니다.");
	exit;
}
$total = $DB[0]->noRows();
$pn = new pgNav($RECPERPG, $PGPERBLK, $total);
$pn->initStart($_GET["myPg"]);
$pgStart = ($pn->myPage-1) * $RECPERPG;

$sql = "SELECT b.myText, b.isIndep, b.myID FROM ExamGroup as a, ItemGroupTable as b  WHERE  a.groupID = b.myID AND a.ExamID = " . $_GET["examID"] . " ORDER BY myID ASC LIMIT " . $RECPERPG . " OFFSET " . $pgStart;
if(!$DB[0]->query($sql)) {
//	echo $sql;
	$fnc->alertBack("Group문항을 가져올 수 없습니다.");
	exit;
}
$i = $pgStart + 1;
$myCorrect = "";
$myScore = "";
while($grpInfo = $DB[0]->fetch()) {
	if($grpInfo[1] == "N") {
		// Group문항의 경우
		$myLinkStr = URL_ROOT . "itemAdmin/viewGrpItem.php?examID=" . $_GET["examID"] . "&myPg=" . $pn->myPage . "&myGrpID=" . $grpInfo[2]."&isIndep=" .$grpInfo[1];
		$myCorrect = "-";
		$myScore = "-";
		$isGroup = "Group";
	} else  {
		// 독립문항의 경우 문항정보를 모두 가져온다.
		$sql2 ="SELECT a.itemID, b.isObject, b.myCorrect, b.myScore FROM ItemGInfoTable as a JOIN ItemTable as b  ON (a.itemID = b.myID) WHERE a.groupID = " . $grpInfo[2];
		if(!$DB[1]->query($sql2)) {
			$fnc->alertBack("Group문항을 가져올 수 없습니다.");
			exit;
		}
		$indepInfo = $DB[1]->fetch();
		$myLinkStr = URL_ROOT . "itemAdmin/viewItem.php?examID=" . $_GET["examID"] . "&myPg=" . $pn->myPage . "&itemID=" . $indepInfo[0] . "&myGrpID=" . $grpInfo[2]."&isIndep=" .$grpInfo[1];

		// 외부 파일의 경우
		if ($indepInfo[1] == "Y") {
				$grpInfo[0] = "외부파일";
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
<A HREF="<?=$_SERVER["PHP_SELF"]?>?examID=<?=$_GET["examID"]?>&myPg=<?=$pn->prevBLKptr?>">[이전 <?=$PGPERBLK?>Page]</A>
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
<A HREF="<?=$_SERVER["PHP_SELF"]?>?examID=<?=$_GET["examID"]?>&myPg=<?=$pn->nextBLKptr?>">[다음 <?=$PGPERBLK?>Page]</A>
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
