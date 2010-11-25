<?php
/* =============================================================================
File : examAdmin/index.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	���ٰ��� : A
	��������� Ȩ
============================================================================= */
$usePGNav = "Y";
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
	exit;
}

$sql = "SELECT myID, myTitle, myStart, myEnd, myItemNo FROM ExamAdmin ORDER BY myStart DESC";
if(!$DB->query($sql)) {
	echo $fnc->alertBack("Query�� ������ �� �����ϴ�.");
	exit;
}
$total = $DB->noRows();
$pgNav = new pgNav(RECPERPG, PGPERBLK, $total);
?>
<P ALIGN="CENTER">��������� Page�Դϴ�.</P>

<TABLE BORDER="0" ALIGN="CENTER" WIDTH="590" cellpadding="0" cellspacing="0">
	<TR>
		<TD WIDTH="590"><IMG SRC="<?=IMG_URL?>site/examadmin/title_1.gif" border="0"></TD>
	</TR>
</TABLE>
<TABLE BORDER="0" ALIGN="CENTER" WIDTH="590" cellpadding="0" cellspacing="0">
<TR>
	<TD ALIGN="CENTER" WIDTH="200" class="title">�����</TD>
	<TD ALIGN="CENTER" WIDTH="150" class="title">���۽ð�</TD>
	<TD ALIGN="CENTER" WIDTH="150" class="title">����ð�</TD>
	<TD ALIGN="CENTER" WIDTH="90" class="title">���׼�</TD>
</TR>
<?
while($result = $DB->fetch()) {
?>
<TR>
	<TD class="cleft" style="padding-left:15px"><A HREF="<?=URL_ROOT?>itemAdmin/itemIndex.php?examID=<?=$result[0]?>"><?=$result[1]?></A></TD>
	<TD class="ccenter" align="center"><?=$result[2]?></TD>
	<TD class="ccenter" align="center"><?=$result[3]?></TD>
	<TD class="cright" align="center"><?=$result[4]?></TD>
</TR>
<?
}
?>
</TABLE>

<?
$fnc->imgButton(72, 28, "location.href='" . URL_ROOT . "examAdmin/addExam.php?myPg=".$_GET["myPg"]."'", IMG_URL . "site/icon/exam_add.gif");
?>

<!--
<P ALIGN="CENTER"><A HREF="<?=URL_ROOT?>examAdmin/addExam.php?myPg=<?=$_GET["myPg"]?>"><img src="<?=IMG_URL?>site/icon/exam_add.gif" border="0"></A></P>
-->
<?
require_once (MY_INCLUDE . "closing.php");
?>
