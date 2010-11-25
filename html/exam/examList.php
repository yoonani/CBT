<?php
/* =============================================================================
File : exam/examList.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	접근가능 : A, B, F
	기본적으로 전체 시험 리스트를 보여준다.
	- 학생의 경우 자신의 시험 목록을
	- 관리자와 학교별 관리자의 경우 전체 시험목록이 나온다.
	- $noDBI는 2로 설정하여 사용한다.
============================================================================= */

$noDBI = 2;
$usePGNav = "Y";
require_once("../../include/conf.php");
require_once (MY_INCLUDE . "header.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "B", "F")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
	exit;
}

$myExamStatus = array ("E" => "출제중", "R" => "실시중", "D" => "종료", "D1" => "응시함", "D2" => "미응시");

switch($_SESSION["Level"]) {
	case "A" :
		$myTitle = "전체 시험목록 입니다.";
		$myCol5 = "ccenter";
		$sql = "SELECT myID, myTitle, myStart, myEnd, myStatus FROM ExamAdmin";
		$sql2 = "SELECT myID, myTitle, myStart, myEnd, myStatus FROM ExamAdmin ORDER BY myStart DESC, myid DESC";
		break;
	case "F" :
		$myTitle = $levelInfo["univ"] . "의 시험목록 입니다.";
		$myCol5 = "ccenter";
		$sql = "SELECT myID, myTitle, myStart, myEnd, myStatus FROM ExamAdmin";
		$sql2 = "SELECT myID, myTitle, myStart, myEnd, myStatus FROM ExamAdmin ORDER BY myStart DESC, myid DESC";
		break;
	case "B" :
		$myTitle = $_SESSION["Name"] . "님의 시험목록 입니다.";
		$myCol5 = "cright";
		$sql = "SELECT a.myID, a.myTitle, a.myStart, a.myEnd, a.myStatus FROM ExamAdmin as a JOIN ExamStudent as b ON (a.myID = b.examID) WHERE b.studentID = '" . trim($_SESSION["ID"]) . "' ORDER BY a.myStart DESC";
		$sql2 = "SELECT a.myID, a.myTitle, a.myStart, a.myEnd, a.myStatus FROM ExamAdmin as a JOIN ExamStudent as b ON (a.myID = b.examID) WHERE b.studentID = '" . trim($_SESSION["ID"]) . "' ORDER BY a.myStart DESC";
		break;
}
?>
<TABLE ALIGN="CENTER" WIDTH="590" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="6"><img src="<?=IMG_URL?>site/examadmin/title_17.gif" border="0"></td>
</tr>
<TR>
	<TD CLASS="title" WIDTH="40" HEIGHT="20" ALIGN="CENTER">No</TD>
	<TD CLASS="title" WIDTH="200" ALIGN="CENTER">시험명</TD>
	<TD CLASS="title" WIDTH="100" ALIGN="CENTER">시작시간</TD>
	<TD CLASS="title" WIDTH="100" ALIGN="CENTER">종료시간</TD>
	<TD CLASS="title" WIDTH="75" ALIGN="CENTER">시험현황</TD>
<?
// 관리자와 전체관리자의 경우 응시생수가 나온다.
if($_SESSION["Level"] == "A" || $_SESSION["Level"] == "F") {
?>
	<TD CLASS="title" WIDTH="75" ALIGN="CENTER">응시자수</TD>
<?
}
?>
</TR>
<?
if(!$DB[0]->query($sql)) {
//	echo $sql;
	$fnc->alertBack("등록된 시험의 수를 가져올 수 없습니다.");
	exit;
}
$total = $DB[0]->noRows();
$pn = new pgNav($RECPERPG, $PGPERBLK, $total);
$pn->initStart($_GET["myPg"]);
$myStart = ($pn->myPage - 1) * $RECPERPG;
$sql2 .= " LIMIT " . $RECPERPG . " OFFSET " . $myStart;
if(!$DB[0]->query($sql2)) {
//	echo $sql;
	$fnc->alertBack("시험정보를 가져올 수 없습니다.");
	exit;
}

$i = $myStart;
while($result = $DB[0]->fetch()) {
	$idx = $i+1;

	// 학생의 경우이고 출제중 상태가 아니면 응시, 미응시 여부 출력
	if($_SESSION["Level"] == "B" AND $result[4] != "E") {
		$sql2 = "SELECT stdStatus FROM ExamStudent WHERE examID = " . $result[0] . " AND studentID = '" . $_SESSION["ID"] . "'";
		if(!$DB[1]->query($sql2)) {
			$fnc->alertBack("응시정보를 가져올 수 없습니다.");
			exit;
		}
		$tmpStatus = $DB[1]->getResult(0, 0);

		// 시험 시작 후이면
		if($fnc->psqlTime2UT($result[2]) < time() ) {
			// 제출했으면 응시함을
			if($tmpStatus == "Y") {
				$col5Out = $myExamStatus["D1"];
				$examTitle = trim($result[1]);
			
			} else {
				// 제출않고 종료전이면 시행중
				if($fnc->psqlTime2UT($result[3]) > time()) {
					$col5Out = $myExamStatus["R"];
					// 시행중일때만 응시 링크 생성
					$examTitle = "<A HREF=\"#\" OnClick=\"window.open('" . URL_ROOT . "takeExam/index.php?examID=" . $result[0]. "', 'examWin', 'left=0,top=0,width=830,height=630,toolbar=no,menubar=no,status=no,scrollbars=yes,resizable=no');\">" . trim($result[1]) . "</A>";
			} else {
				// 제출않고 종료후면 "미응시"
					$col5Out = $myExamStatus["D2"];
					$examTitle = trim($result[1]);
				}
			}
		} else {
			// 시험 시작전이면
			$col5Out = $myExamStatus[$result[4]];
			$examTitle = trim($result[1]);
		}
	} else {
		switch($_SESSION["Level"]) {
			case "A" :
			// 전체 관리자의 경우 시험 상태를 변경시킬 수 있어야 한다.
			// 시험 상태 변화를 위한 클릭버튼 생성
				$col5Out = "<A HREF=\"" . URL_ROOT . "exam/examToggle.php?examID=" . $result[0] . "&cState=" . $result[4] . "&myPg=" . $pn->myPage . "\">" . $myExamStatus[$result[4]] . "</A>";
				$examTitle = "<A HREF=\"" . URL_ROOT . "itemAdmin/itemIndex.php?examID=" . $result[0] . "\">" . trim($result[1]) . "</A>";
				 break;
			case "F" :
			// 학교별 관리자의 경우 시험에 대한 응시자를 선택한다.
				$col5Out = $myExamStatus[$result[4]];
				if($result[4] != "D") {
					$examTitle = "<A HREF=\"" . URL_ROOT . "eachUniv/regStd2Exam.php?examID=" . $result[0] . "\">" . trim($result[1]) . "</A>";
				} else {
					$examTitle = trim($result[1]);
				}
				break;
			case "B" :
			// 학생의 경우
				$col5Out = $myExamStatus[$result[4]];
				$examTitle = trim($result[1]);
		}		
	}

?>
<TR>
	<TD CLASS="cleft" ALIGN="CENTER"><?=$idx?></TD>
	<TD CLASS="ccenter">&nbsp;<?=$examTitle?></TD>
	<TD CLASS="ccenter" ALIGN="CENTER"><?=date("Y/m/d H:i", $fnc->psqlTime2UT($result[2]))?></TD>
	<TD CLASS="ccenter" ALIGN="CENTER"><?=date("Y/m/d H:i", $fnc->psqlTime2UT($result[3]))?></TD>
	<TD CLASS="<?=$myCol5?>" ALIGN="CENTER"><?=$col5Out?></TD>
<?
// 전체관리자이거나 학교별 관리자의 경우 응시자수 출력
	if($_SESSION["Level"] != "B") {
		if($_SESSION["Level"] == "A") {
			$sql2 = "SELECT COUNT(*) FROM ExamStudent WHERE examID = " . $result[0] . " AND stdStatus = 'Y'";
		} else {
			$sql2 = "SELECT COUNT(*) FROM ExamStudent as a JOIN UserInfo as b ON (a.studentID = b.myID) WHERE a.examID = " . $result[0] . " AND a.stdStatus = 'Y' AND b.univID = '" . $_SESSION["UID"] . "'";
		}
		if(!$DB[1]->query($sql2)) {
//			echo $sql2;
			$fnc->alertBack("응시자수를 가져올 수 없습니다.");
			exit;
		}
?>
	<TD CLASS="cright" ALIGN="CENTER"><?=$DB[1]->getResult(0, 0)?>명</TD>
<?
	}
?>
</TR>
<?
	$i++;
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
<A HREF="<?=$_SERVER["PHP_SELF"]?>?myPg=<?=$pn->prevBLKptr?>">[이전 <?=$PGPERBLK?>Page]</A>
<?
}

if($pn->isPrev() ) {
?>
<A HREF="<?=$_SERVER["PHP_SELF"]?>?myPg=<?=$pn->prevPG?>"><img src="<?=IMG_URL?>site/icon/pge_pre.gif" border="0" align="absmiddle"></A>
<?
}

$pn->prtPage("?myPg", "[", "]");

if($pn->isNext() ) {
?>
<A HREF="<?=$_SERVER["PHP_SELF"]?>?myPg=<?=$pn->nextPG?>"><img src="<?=IMG_URL?>site/icon/pge_next.gif" border="0" align="absmiddle"></A>
<?
}

if($pn->isNextBLK() ) {
?>
<A HREF="<?=$_SERVER["PHP_SELF"]?>?myPg=<?=$pn->nextBLKptr?>">[다음 <?=$PGPERBLK?>Page]</A>
<?
}
?>
	</TD>
	<TD>&nbsp;</TD>
</TR>
</TABLE>
<?
// 전체 관리자의 경우 시험 추가 버튼 생성
if($_SESSION["Level"] == "A") {
echo "<P ALIGN=\"CENTER\">";
$fnc->imgButton(72, 28, "location.href='" . URL_ROOT . "examAdmin/addExam.php?myPg=".$_GET["myPg"]."'", IMG_URL . "site/icon/exam_add.gif");
echo "</P>\n";
}
require_once (MY_INCLUDE . "closing.php");
?>
