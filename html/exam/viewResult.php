<?php
/* =============================================================================
File : exam/viewResult.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Desc.
	접근가능 : A, F
	결과 보기
	- $_GET["examID"] 로부터 결과를 가져온다.
	- 제출하기 버튼을 누르지 않은 경우 해당 학생의 bgcolor를 yellow로
	- 전체 관리자는 전체 제출 현황을, 
	  학교별관리자는 학교별 제출현황을 보여준다.
	- 표시형식 : 성명 클릭시 결과창 생성
	학교, 학번, 성명(아이디), 맞춘문제수 / 전체문제수, 점수
	
============================================================================= */

$noDBI = 2;
$usePGNav = "Y";
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "header.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "F")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
	exit;
}
// 시험정보 출력
$sql = "SELECT myTitle, myStart, myEnd, myItemNo, myStatus FROM examAdmin WHERE myID = " . $_GET["examID"];
if(!$DB[0]->query($sql)) {
        $fnc->alertBack("Query를 수행할 수 없습니다.");
        exit;
}
$result = $DB[0]->fetch();
$totalItems = $result[3];
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
                <td class="hbottom"><?=$totalItems?></td>
        </tr>
<?
// Paging을 위한 전체 갯수 출력
// 관리자별 출력결과를 다르게
if($_SESSION["Level"] == "A") {
	$sqlTotal = "SELECT count(*) FROM examStudent WHERE examID = " . $_GET["examID"];
} else {
	$sqlTotal = "SELECT count(*) FROM examStudent as a JOIN UserInfo as b ON(a.userID = b.myID) WHERE a.examID = " . $_GET["examID"] . " AND b.univID = '" . $_SESSION["UID"] . "'";
}

if(!$DB[0]->query($sqlTotal)) {
	$fnc->alertBack("전체 응시생수를 가져올 수 없습니다.");
	exit;
}
$total = $DB[0]->getResult(0, 0);
?>
	<tr>
                <td class="htitle" align="center">응시생수</td>
                <td class="hbottom"><?=$total?> [<A HREF="<?=URL_ROOT?>exam/downResult.php?examID=<?=$_GET["examID"]?>">결과받기</A>]</td>
	</tr>
</table>
<?
// Page Navigation
$pn = new pgNav($RECPERPG, $PGPERBLK, $total);
$pn->initStart($_GET["myPg"]);
$pgStart = ($pn->myPage-1) * $RECPERPG;

// 학생목록 출력 
// 관리자별 출력결과를 다르게
// 학교, 학번, 성명(아이디), 맞춘문제수 / 전체문제수, 점수
if($_SESSION["Level"] == "A") {
	$sql = "select c.univSNO, c.studName, b.myID, e.stdStatus, d.myTitle from UserInfo as b  NATURAL JOIN (UnivStudent as c JOIN UnivInfo as d ON (c.univID = d.myCode)) JOIN ExamStudent as e ON (b.myID = e.studentID) WHERE e.examID = " . $_GET["examID"] . " LIMIT " . $RECPERPG . " OFFSET " . $pgStart;
} else {
	$sql = "select c.univSNO, c.studName, b.myID, e.stdStatus from (UserInfo as b  NATURAL JOIN UnivStudent as c) JOIN ExamStudent as e ON (b.myID = e.studentID) WHERE e.examID = " . $_GET["examID"] . " AND b.univID = '" . $_SESSION["UID"] . "' LIMIT " . $RECPERPG . " OFFSET " . $pgStart;
}
if(!$DB[0]->query($sql)) {
	$fnc->alertBack("결과를 가져올 수 없습니다.");
	exit;
}
?>
<br />
<table width="590" border="0" cellpadding="0" cellspacing="0">
<TR>
<?
if($_SESSION["Level"] == "A") {
	echo "
	<TD ALIGN=\"CENTER\" CLASS=\"title\">학교</TD>
	";
}
?>
	<TD ALIGN="CENTER" CLASS="title" HEIGHT="20">학번</TD>
	<TD ALIGN="CENTER" CLASS="title">성명</TD>
	<TD ALIGN="CENTER" CLASS="title">정답현황</TD>
	<TD ALIGN="CENTER" CLASS="title">점수</TD>
</TR>
<?
while($result = $DB[0]->fetch()) {
	if($result[3] != "Y") {
		$myBgColor = "bgcolor=\"#999900\"";
	} else {
		$myBgColor = "bgcolor=\"white\"";
	}
	echo "
<TR>
";
if($_SESSION["Level"] == "A") {
	echo "
	<TD ALIGN=\"CENTER\" CLASS=\"cright\" " . $myBgColor. ">" . $result[4]. "</TD>
	";
	$myCSS = "ccenter";
} else {
	$myCSS = "cright";
}

// 맞춘 갯수와 점수계산
	$sql2 = "SELECT count(*), sum(b.myScore) FROM ExamSubmit as a JOIN ItemTable as b ON (a.itemID = b.myID) WHERE a.examID = " . $_GET["examID"] . " AND a.userID = '" . trim($result[2]) . "' AND a.isCorrect = '1'";
	if(!$DB[1]->query($sql2)) {
		$fnc->alertBack("학생 점수를 가져올 수 없습니다.");
		exit;
	}
	$iResult = $DB[1]->fetch();
?>
	<TD ALIGN="CENTER" CLASS="<?=$myCSS?>" <?=$myBgColor?>><?=trim($result[0])?></TD>
	<TD ALIGN="CENTER" CLASS="ccenter" <?=$myBgColor?>><?=trim($result[1])?>(<?=trim($result[2])?>)</TD>
	<TD ALIGN="CENTER" CLASS="ccenter" <?=$myBgColor?>><?=$iResult[0]?>/<?=$totalItems?></TD>
	<TD ALIGN="CENTER" CLASS="cleft" <?=$myBgColor?>><?=$iResult[1]?></TD>
</TR>
<?
}
?>
</TABLE>

<!-- Page Navigation //-->
<TABLE BRODER="0" WIDTH="590">
<TR>
<TD ALIGN="CENTER">
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
</TR>
</TABLE>
<?
require_once (MY_INCLUDE . "closing.php");
?>
