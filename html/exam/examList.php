<?php
/* =============================================================================
File : exam/examList.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	���ٰ��� : A, B, F
	�⺻������ ��ü ���� ����Ʈ�� �����ش�.
	- �л��� ��� �ڽ��� ���� �����
	- �����ڿ� �б��� �������� ��� ��ü �������� ���´�.
	- $noDBI�� 2�� �����Ͽ� ����Ѵ�.
============================================================================= */

$noDBI = 2;
$usePGNav = "Y";
require_once("../../include/conf.php");
require_once (MY_INCLUDE . "header.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "B", "F")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
	exit;
}

$myExamStatus = array ("E" => "������", "R" => "�ǽ���", "D" => "����", "D1" => "������", "D2" => "������");

switch($_SESSION["Level"]) {
	case "A" :
		$myTitle = "��ü ������ �Դϴ�.";
		$myCol5 = "ccenter";
		$sql = "SELECT myID, myTitle, myStart, myEnd, myStatus FROM ExamAdmin";
		$sql2 = "SELECT myID, myTitle, myStart, myEnd, myStatus FROM ExamAdmin ORDER BY myStart DESC, myid DESC";
		break;
	case "F" :
		$myTitle = $levelInfo["univ"] . "�� ������ �Դϴ�.";
		$myCol5 = "ccenter";
		$sql = "SELECT myID, myTitle, myStart, myEnd, myStatus FROM ExamAdmin";
		$sql2 = "SELECT myID, myTitle, myStart, myEnd, myStatus FROM ExamAdmin ORDER BY myStart DESC, myid DESC";
		break;
	case "B" :
		$myTitle = $_SESSION["Name"] . "���� ������ �Դϴ�.";
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
	<TD CLASS="title" WIDTH="200" ALIGN="CENTER">�����</TD>
	<TD CLASS="title" WIDTH="100" ALIGN="CENTER">���۽ð�</TD>
	<TD CLASS="title" WIDTH="100" ALIGN="CENTER">����ð�</TD>
	<TD CLASS="title" WIDTH="75" ALIGN="CENTER">������Ȳ</TD>
<?
// �����ڿ� ��ü�������� ��� ���û����� ���´�.
if($_SESSION["Level"] == "A" || $_SESSION["Level"] == "F") {
?>
	<TD CLASS="title" WIDTH="75" ALIGN="CENTER">�����ڼ�</TD>
<?
}
?>
</TR>
<?
if(!$DB[0]->query($sql)) {
//	echo $sql;
	$fnc->alertBack("��ϵ� ������ ���� ������ �� �����ϴ�.");
	exit;
}
$total = $DB[0]->noRows();
$pn = new pgNav($RECPERPG, $PGPERBLK, $total);
$pn->initStart($_GET["myPg"]);
$myStart = ($pn->myPage - 1) * $RECPERPG;
$sql2 .= " LIMIT " . $RECPERPG . " OFFSET " . $myStart;
if(!$DB[0]->query($sql2)) {
//	echo $sql;
	$fnc->alertBack("���������� ������ �� �����ϴ�.");
	exit;
}

$i = $myStart;
while($result = $DB[0]->fetch()) {
	$idx = $i+1;

	// �л��� ����̰� ������ ���°� �ƴϸ� ����, ������ ���� ���
	if($_SESSION["Level"] == "B" AND $result[4] != "E") {
		$sql2 = "SELECT stdStatus FROM ExamStudent WHERE examID = " . $result[0] . " AND studentID = '" . $_SESSION["ID"] . "'";
		if(!$DB[1]->query($sql2)) {
			$fnc->alertBack("���������� ������ �� �����ϴ�.");
			exit;
		}
		$tmpStatus = $DB[1]->getResult(0, 0);

		// ���� ���� ���̸�
		if($fnc->psqlTime2UT($result[2]) < time() ) {
			// ���������� ��������
			if($tmpStatus == "Y") {
				$col5Out = $myExamStatus["D1"];
				$examTitle = trim($result[1]);
			
			} else {
				// ����ʰ� �������̸� ������
				if($fnc->psqlTime2UT($result[3]) > time()) {
					$col5Out = $myExamStatus["R"];
					// �������϶��� ���� ��ũ ����
					$examTitle = "<A HREF=\"#\" OnClick=\"window.open('" . URL_ROOT . "takeExam/index.php?examID=" . $result[0]. "', 'examWin', 'left=0,top=0,width=830,height=630,toolbar=no,menubar=no,status=no,scrollbars=yes,resizable=no');\">" . trim($result[1]) . "</A>";
			} else {
				// ����ʰ� �����ĸ� "������"
					$col5Out = $myExamStatus["D2"];
					$examTitle = trim($result[1]);
				}
			}
		} else {
			// ���� �������̸�
			$col5Out = $myExamStatus[$result[4]];
			$examTitle = trim($result[1]);
		}
	} else {
		switch($_SESSION["Level"]) {
			case "A" :
			// ��ü �������� ��� ���� ���¸� �����ų �� �־�� �Ѵ�.
			// ���� ���� ��ȭ�� ���� Ŭ����ư ����
				$col5Out = "<A HREF=\"" . URL_ROOT . "exam/examToggle.php?examID=" . $result[0] . "&cState=" . $result[4] . "&myPg=" . $pn->myPage . "\">" . $myExamStatus[$result[4]] . "</A>";
				$examTitle = "<A HREF=\"" . URL_ROOT . "itemAdmin/itemIndex.php?examID=" . $result[0] . "\">" . trim($result[1]) . "</A>";
				 break;
			case "F" :
			// �б��� �������� ��� ���迡 ���� �����ڸ� �����Ѵ�.
				$col5Out = $myExamStatus[$result[4]];
				if($result[4] != "D") {
					$examTitle = "<A HREF=\"" . URL_ROOT . "eachUniv/regStd2Exam.php?examID=" . $result[0] . "\">" . trim($result[1]) . "</A>";
				} else {
					$examTitle = trim($result[1]);
				}
				break;
			case "B" :
			// �л��� ���
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
// ��ü�������̰ų� �б��� �������� ��� �����ڼ� ���
	if($_SESSION["Level"] != "B") {
		if($_SESSION["Level"] == "A") {
			$sql2 = "SELECT COUNT(*) FROM ExamStudent WHERE examID = " . $result[0] . " AND stdStatus = 'Y'";
		} else {
			$sql2 = "SELECT COUNT(*) FROM ExamStudent as a JOIN UserInfo as b ON (a.studentID = b.myID) WHERE a.examID = " . $result[0] . " AND a.stdStatus = 'Y' AND b.univID = '" . $_SESSION["UID"] . "'";
		}
		if(!$DB[1]->query($sql2)) {
//			echo $sql2;
			$fnc->alertBack("�����ڼ��� ������ �� �����ϴ�.");
			exit;
		}
?>
	<TD CLASS="cright" ALIGN="CENTER"><?=$DB[1]->getResult(0, 0)?>��</TD>
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
<A HREF="<?=$_SERVER["PHP_SELF"]?>?myPg=<?=$pn->prevBLKptr?>">[���� <?=$PGPERBLK?>Page]</A>
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
<A HREF="<?=$_SERVER["PHP_SELF"]?>?myPg=<?=$pn->nextBLKptr?>">[���� <?=$PGPERBLK?>Page]</A>
<?
}
?>
	</TD>
	<TD>&nbsp;</TD>
</TR>
</TABLE>
<?
// ��ü �������� ��� ���� �߰� ��ư ����
if($_SESSION["Level"] == "A") {
echo "<P ALIGN=\"CENTER\">";
$fnc->imgButton(72, 28, "location.href='" . URL_ROOT . "examAdmin/addExam.php?myPg=".$_GET["myPg"]."'", IMG_URL . "site/icon/exam_add.gif");
echo "</P>\n";
}
require_once (MY_INCLUDE . "closing.php");
?>
