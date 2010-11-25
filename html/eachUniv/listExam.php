<?php
/* =============================================================================
File : eachUniv/listExam.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	���ٰ��� : A, F
	���� ����Ʈ�� �����ش�.
	- �б��� ������
============================================================================= */
$usePGNav = "Y";
$noDBI = 2;
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "F")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
	exit;
}

$sql = "SELECT myID, myTitle, myStart, myEnd, myItemNo FROM ExamAdmin ORDER BY myStart DESC";
if(!$DB[0]->query($sql)) {
	echo $fnc->alertBack("Query�� ������ �� �����ϴ�.");
	exit;
}
$total = $DB[0]->noRows();
$pgNav = new pgNav(RECPERPG, PGPERBLK, $total);
?>
<P ALIGN="CENTER">�б��� ������ Page�Դϴ�.</P>
<TABLE BORDER="1" ALIGN="CENTER" WIDTH="600">
<TR>
	<TD ALIGN="CENTER" WIDTH="200">�����</TD>
	<TD ALIGN="CENTER" WIDTH="100">���۽ð�</TD>
	<TD ALIGN="CENTER" WIDTH="100">����ð�</TD>
	<TD ALIGN="CENTER" WIDTH="100">���׼�</TD>
	<TD ALIGN="CENTER" WIDTH="100">����л�</TD>
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
		echo $fnc->alertBack("���������� �б������� ������ �� �����ϴ�.");
		exit;
	}
	echo $DB[1]->getResult(0, 0) . "��";
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
