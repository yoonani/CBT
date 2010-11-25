<?php
/* =============================================================================
File : eachUniv/listExam.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	접근가능 : A, F
	시험 리스트를 보여준다.
	- 학교별 관리자
============================================================================= */
$usePGNav = "Y";
$noDBI = 2;
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "F")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
	exit;
}

$sql = "SELECT myID, myTitle, myStart, myEnd, myItemNo FROM ExamAdmin ORDER BY myStart DESC";
if(!$DB[0]->query($sql)) {
	echo $fnc->alertBack("Query를 수행할 수 없습니다.");
	exit;
}
$total = $DB[0]->noRows();
$pgNav = new pgNav(RECPERPG, PGPERBLK, $total);
?>
<P ALIGN="CENTER">학교별 관리자 Page입니다.</P>
<TABLE BORDER="1" ALIGN="CENTER" WIDTH="600">
<TR>
	<TD ALIGN="CENTER" WIDTH="200">시험명</TD>
	<TD ALIGN="CENTER" WIDTH="100">시작시간</TD>
	<TD ALIGN="CENTER" WIDTH="100">종료시간</TD>
	<TD ALIGN="CENTER" WIDTH="100">문항수</TD>
	<TD ALIGN="CENTER" WIDTH="100">등록학생</TD>
</TR>
<?
while($result = $DB[0]->fetch()) {
?>
<TR>
	<TD><A HREF="<?=URL_ROOT?>eachUniv/regStd2Exam.php?examID=<?=$result[0]?>"><?=$result[1]?></A></TD>
	<TD><?=$result[2]?></TD>
	<TD><?=$result[3]?></TD>
	<TD><?=$result[4]?></TD>
	<TD>
<?
	$sql2 = "SELECT  count(*) FROM ExamStudent as a, UserInfo as b WHERE a.studentID = b.myID AND b.univID = '" . $_SESSION["UID"] . "'";
	if(!$DB[1]->query($sql2)) {
		echo $fnc->alertBack("시험정보와 학교정보를 가져올 수 없습니다.");
		exit;
	}
	echo $DB[1]->getResult(0, 0) . "명";
?>
	</TD>
</TR>
<?
}
?>
</TABLE>
<?
require_once (MY_INCLUDE . "closing.php");
?>
