<?php
/* =============================================================================
File : examAdmin/addExam.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	접근가능 : A
	시험 출제 Page
============================================================================= */
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "header.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
	exit;
}
$curDT = getdate();
// print_r($curDT);
?>
<SCRIPT LANGUAGE="JavaScript">
	function frmSubmit(frm) {
		if(frm.getTitle.value.length < 1) {
			alert("시험명을 적어주세요");
			frm.getTitle.focus();
			return false;
		}
		myStr1 = frm.getTitle.value;
		myStr2 = frm.getYear.value + "년 " + frm.getMonth.value + "월 " + frm.getDay.value + "일 " + frm.getHour.value + "시 " + frm.getMinute.value + "분";
		myStr3 = frm.getYear2.value + "년 " + frm.getMonth2.value + "월 " + frm.getDay2.value + "일 " + frm.getHour2.value + "시 " + frm.getMinute2.value + "분";
		if(confirm(myStr1 + "\n시작시간 : " + myStr2 + "\n종료시간 : " + myStr3 + "\n\n위 정보가 맞습니까?")) {
			frm.submit();
		} else {
			return false;
		}
	}
</SCRIPT>
<!--
//
//정호가 추가시킨거 css test
//
-->


<FORM NAME="addExam" METHOD="POST" ACTION="<?=URL_ROOT?>examAdmin/addExamProc.php">
<table border="0" width="590" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2" width="590">
			<img src="<?=IMG_URL?>site/examadmin/title_3.gif"
		</td>
	</tr>
	<tr>
		<td width="150" height="20" class="htitle" align="center">
			시험명  
		</td>
		<td width="440" height="20" class="htop" align=center">
			<INPUT TYPE="TEXT" SIZE="20" MAXLENGTH="50" NAME="getTitle">
		</td>
	</tr>
	<tr>
		<td width="150" height="20" class="htitle" align="center">
			시작시간 
		</td>
		<td width="440" height="20" class="hmiddle">
			<SELECT NAME="getYear">
				<OPTION VALUE="<?=$curDT["year"]?>" SELECTED><?=$curDT["year"]?></OPTION>
<?
for($i=$curDT["year"]+1; $i < $curDT["year"]+5; $i++) {
?>
				<OPTION VALUE="<?=$i?>"><?=$i?></OPTION>
<?
}
?>
			</SELECT> 년
			<SELECT NAME="getMonth">
<?
for($i=1; $i < 13; $i++) {
?>
				<OPTION VALUE="<?=$i?>" <?  if($i==$curDT["mon"]) echo " SELECTED "; ?>><?=$i?></OPTION>
<?
}
?>
			</SELECT> 월 
			<SELECT NAME="getDay">
<?
for($i=1; $i < 32; $i++) {
?>
				<OPTION VALUE="<?=$i?>" <?  if($i==$curDT["mday"]) echo " SELECTED "; ?>><?=$i?></OPTION>
<?
}
?>
			<SELECT> 일 
			&nbsp;&nbsp;
			<SELECT NAME="getHour">
<?
for($i=0; $i < 24; $i++) {
?>
				<OPTION VALUE="<?=$i?>"><?=sprintf("%02d",$i)?></OPTION>
<?
}
?>
			</SELECT> 시 
			<SELECT NAME="getMinute">
<?
for($i=0; $i < 56; $i=$i+5) {
?>
				<OPTION VALUE="<?=$i?>"><?=sprintf("%02d",$i)?></OPTION>
<?
}
?>
			</SELECT> 분 
		</td>
	</tr>
	<tr>
		<td width="150" height="20" class="htitle" align="center">
			종료시간
		</td>
		<td width="440" height="20" class="hbottom">
			<SELECT NAME="getYear2">
				<OPTION VALUE="<?=$curDT["year"]?>" SELECTED><?=$curDT["year"]?></OPTION>
<?
for($i=$curDT["year"]+1; $i < $curDT["year"]+5; $i++) {
?>
				<OPTION VALUE="<?=$i?>"><?=$i?></OPTION>
<?
}
?>
				</SELECT> 년
				<SELECT NAME="getMonth2">
<?
for($i=1; $i < 13; $i++) {
?>
					<OPTION VALUE="<?=$i?>" <?  if($i==$curDT["mon"]) echo " SELECTED "; ?>><?=$i?></OPTION>
<?
}
?>
				</SELECT> 월 
				<SELECT NAME="getDay2">
<?
for($i=1; $i < 32; $i++) {
?>
					<OPTION VALUE="<?=$i?>" <?  if($i==$curDT["mday"]) echo " SELECTED "; ?>><?=$i?></OPTION>
<?
}
?>
				</SELECT> 일 
				&nbsp;&nbsp;
				<SELECT NAME="getHour2">
<?
for($i=0; $i < 24; $i++) {
?>
					<OPTION VALUE="<?=$i?>"><?=sprintf("%02d",$i)?></OPTION>
<?
}
?>
				</SELECT> 시 
				<SELECT NAME="getMinute2">
<?
for($i=0; $i < 56; $i=$i+5) {
?>
					<OPTION VALUE="<?=$i?>"><?=sprintf("%02d",$i)?></OPTION>
<?
}
?>
				</SELECT> 분 
		</td>
	</tr>
	<INPUT TYPE="HIDDEN" NAME="myPg" VALUE="<?=$_GET["myPg"]?>">

<P ALIGN="CENTER">
	<tr>
		<td colspan="2" align="right">
			<A HREF="#" OnClick="javaScript:frmSubmit(document.addExam);"><img src="<?=IMG_URL?>site/icon/add.gif" border="0"></A>
			<A HREF="#" OnClick="history.go(-1);"><img src="<?=IMG_URL?>site/icon/cancel.gif" border="0"></A>
		</td>
</table>
</FORM>
<?
require_once (MY_INCLUDE . "closing.php");
?>
