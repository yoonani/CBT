<?php
/* =============================================================================
File : eachUniv/regStd2Exam.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Desc.
	접근가능 : A, F
	학교별로 학생을 시험에 등록시킨다.
	- $_GET["examID"] : 시험 ID	
============================================================================= */
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "header.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "F")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
	exit;
}
$sql = "SELECT myTitle, myStart, myEnd FROM examAdmin WHERE myID = " . $_GET["examID"];
if(!$DB->query($sql)) {
        $fnc->alertBack("Query를 수행할 수 없습니다.");
        exit;
}
$result = $DB->fetch();
?>
<table width="590" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="2"><img src="<?=IMG_URL?>site/examadmin/title_2.gif"></td>
</tr>
<tr>
	<td width="150" class="htitle" align="center">시험명</td>
	<td width="440" class="htop"><?=$result[0]?></td>
</tr>
<tr>
	<td class="htitle" align="center">시작시간</td>
	<td class="hmiddle"><?=$result[1]?></td>
</tr>
<Tr>
	<td class="htitle" align="center">종료시간</td>
	<td class="hmiddle"><?=$result[2]?></td>
</tr>
<tr>
	<td class="htitle" align="center">본교의 응시생수</td> 
	<td class="hbottom">
<?
$sql = "SELECT  count(*) FROM ExamStudent as a, UserInfo as b WHERE a.studentID = b.myID AND b.univID = '" . $_SESSION["UID"] . "'";
if(!$DB->query($sql)) {
	echo $fnc->alertBack("시험정보와 학교정보를 가져올 수 없습니다.");
	exit;
}
echo $DB->getResult(0, 0) . "명";
?>
	</td>
</tr>
</table>
<SCRIPT LANGUAGE="JavaScript">
function CheckAll(checkBoxes, checked, no) {
	var i;
	if(checkBoxes.length)  {
		for(i=0; i<checkBoxes.length;i++) 
			checkBoxes[i].checked=checked
	} else {
		checkBoxes.checked=checked
	}
}

</SCRIPT>
<FORM NAME="std" METHOD="POST" ACTION="<?=URL_ROOT?>eachUniv/chExamStatus.php">
<TABLE border="0" cellpadding="0" cellspacing="0" width="590">
<TR>
	<TD VALIGN="TOP" align="center">
	<!-- 응시생 명단//-->
<?
// 현재 학교의 해당 시험의 응시생 명단을 보여준다.
$sql = "SELECT b.univSNO, c.studName, b.myID, c.studgrade FROM ExamStudent as a JOIN (UserInfo as b JOIN UnivStudent as c USING (univID, univSNO)) ON (a.studentID = b.myID ) WHERE  a.examID = " . $_GET["examID"] . " AND b.univID = '" . $_SESSION["UID"] . "'";
if(!$DB->query($sql)) {
	$fnc->alertBack("응시생 명단을 가져올 수 없습니다.");
	exit;
}
$myNo = $DB->noRows();
?>
		<TABLE border="0" width="290" cellpadding="0" cellspacing="0">
		<TR height="25">
			<TD colspan="3" align="center"><img src="<?=IMG_URL?>site/examadmin/exam_check_list.gif"></TD>
		</TR>
		<TR height="25">
			<TD width="40" class="title" align="center"><INPUT TYPE="CHECKBOX" style="border:none;align:middle" NAME="ca" OnClick="CheckAll(document.std['myCB[]'], this.checked, <?=$myNo?>);"></TD>
			<TD width="100" class="title" align="center">학번</TD>
			<TD width="50" class="title" align="center">학년</TD>
			<TD width="105" class="title" align="center">성명</TD>
		</TR>
<?
$i = 0;
while($result = $DB->fetch()) {
	echo "<TR>\n";
	echo "<TD align=\"center\" class=\"cleft\"><INPUT style=\"border:none\" TYPE=\"CHECKBOX\" NAME=\"myCB[]\" VALUE='" . trim($result[2]) . "'></TD>\n";
	echo "<TD align=\"center\" class=\"ccenter\">" . $result[0]. "</TD>\n";
	echo "<TD align=\"center\" class=\"ccenter\">" . $result[3]. "</TD>\n";
	echo "<TD align=\"center\" class=\"cright\">" . $result[1]. "</TD>\n";
	echo "<TD>" . "" .  "</TD>\n";
	echo "</TR>\n";
	$i++;
}
?>
<INPUT TYPE="HIDDEN" NAME="myStatus" VALUE="S">
<INPUT TYPE="HIDDEN" NAME="examID" VALUE="<?=$_GET["examID"]?>">
</FORM>
		<TR>
			<TD COLSPAN="3" align="right"><A HREF="#" OnClick="document.std.submit();"><img src="<?=IMG_URL?>site/icon/del_examuser.gif"></A></TD>
		</TR>
		</TABLE>
	</TD>

	<TD VALIGN="TOP" align="center">
	<!-- 미응시생 명단//-->
<?
// 현재 학교의 해당 시험의 미응시생 명단을 보여준다.
$sql = "SELECT b.myID FROM ExamStudent as a JOIN (UserInfo as b JOIN UnivStudent as c USING (univID, univSNO)) ON (a.studentID = b.myID ) WHERE  a.examID = " . $_GET["examID"] . " AND b.univID = '" . $_SESSION["UID"] . "'";
$sql2 = "SELECT b.univSNO, c.studName, b.myID, c.studgrade FROM UserInfo as b JOIN UnivStudent as c USING (univID, univSNO) WHERE  b.univID = '" . $_SESSION["UID"] . "' AND b.myID NOT IN (" . $sql . ")";
if(!$DB->query($sql2)) {
	$fnc->alertBack("미응시생 명단을 가져올 수 없습니다.");
	exit;
}
$myNo = $DB->noRows();
?>
		<TABLE width="290" cellpadding="0" cellspacing="0" border="0">
<FORM NAME="ustd" METHOD="POST"  ACTION="<?=URL_ROOT?>eachUniv/chExamStatus.php">
		<TR height="25">
			<TD colspan="3"  align="center"><img src="<?=IMG_URL?>site/examadmin/exam_uncheck_list.gif"></TD>
		</TR>
		<TR height="25">
			<TD width="40" class="title" align="center"><INPUT style="border:none;" TYPE="CHECKBOX" NAME="ca2" OnClick="CheckAll(document.ustd['myCB2[]'], this.checked, <?=$myNo?>);"></TD>
			<TD width="100" class="title" align="center">학번</TD>
			<TD width="50" class="title" align="center">학년</TD>
			<TD width="105" class="title" align="center">성명</TD>
		</TR>
<?
$i=0;
while($result = $DB->fetch()) {
	echo "<TR>\n";
	echo "<TD align=\"center\" class=\"cleft\"><INPUT style=\"border:none\" TYPE=\"checkbox\" NAME=\"myCB2[]\" VALUE='" . trim($result[2]) . "'></TD>\n";
	echo "<TD align=\"center\" class=\"ccenter\">" . $result[0]. "</TD>\n";
	echo "<TD align=\"center\" class=\"ccenter\">" . $result[3]. "</TD>\n";
	echo "<TD align=\"center\" class=\"cright\">" . $result[1]. "</TD>\n";
	echo "<TD>" . "" . "</TD>\n";
	echo "</TR>\n";
	$i++;
}
?>
<INPUT TYPE="HIDDEN" NAME="myStatus" VALUE="U">
<INPUT TYPE="HIDDEN" NAME="examID" VALUE="<?=$_GET["examID"]?>">
</FORM>
		<TR>
			<TD COLSPAN="3" align="right"><A HREF="#" OnClick="document.ustd.submit();"><img src="<?=IMG_URL?>site/icon/add_examuser.gif"></A></TD>
		</TR>
		</TABLE>
	</TD>
</TR>
</TABLE>

<?
require_once (MY_INCLUDE . "closing.php");
?>
