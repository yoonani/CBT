<?php
/* =============================================================================
File : examAdmin/addExam.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	���ٰ��� : A
	���� ���� Page
============================================================================= */
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "header.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
	exit;
}
$curDT = getdate();
// print_r($curDT);
?>
<SCRIPT LANGUAGE="JavaScript">
	function frmSubmit(frm) {
		if(frm.getTitle.value.length < 1) {
			alert("������� �����ּ���");
			frm.getTitle.focus();
			return false;
		}
		myStr1 = frm.getTitle.value;
		myStr2 = frm.getYear.value + "�� " + frm.getMonth.value + "�� " + frm.getDay.value + "�� " + frm.getHour.value + "�� " + frm.getMinute.value + "��";
		myStr3 = frm.getYear2.value + "�� " + frm.getMonth2.value + "�� " + frm.getDay2.value + "�� " + frm.getHour2.value + "�� " + frm.getMinute2.value + "��";
		if(confirm(myStr1 + "\n���۽ð� : " + myStr2 + "\n����ð� : " + myStr3 + "\n\n�� ������ �½��ϱ�?")) {
			frm.submit();
		} else {
			return false;
		}
	}
</SCRIPT>
<!--
//
//��ȣ�� �߰���Ų�� css test
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
			�����  
		</td>
		<td width="440" height="20" class="htop" align=center">
			<INPUT TYPE="TEXT" SIZE="20" MAXLENGTH="50" NAME="getTitle">
		</td>
	</tr>
	<tr>
		<td width="150" height="20" class="htitle" align="center">
			���۽ð� 
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
			</SELECT> ��
			<SELECT NAME="getMonth">
<?
for($i=1; $i < 13; $i++) {
?>
				<OPTION VALUE="<?=$i?>" <?  if($i==$curDT["mon"]) echo " SELECTED "; ?>><?=$i?></OPTION>
<?
}
?>
			</SELECT> �� 
			<SELECT NAME="getDay">
<?
for($i=1; $i < 32; $i++) {
?>
				<OPTION VALUE="<?=$i?>" <?  if($i==$curDT["mday"]) echo " SELECTED "; ?>><?=$i?></OPTION>
<?
}
?>
			<SELECT> �� 
			&nbsp;&nbsp;
			<SELECT NAME="getHour">
<?
for($i=0; $i < 24; $i++) {
?>
				<OPTION VALUE="<?=$i?>"><?=sprintf("%02d",$i)?></OPTION>
<?
}
?>
			</SELECT> �� 
			<SELECT NAME="getMinute">
<?
for($i=0; $i < 56; $i=$i+5) {
?>
				<OPTION VALUE="<?=$i?>"><?=sprintf("%02d",$i)?></OPTION>
<?
}
?>
			</SELECT> �� 
		</td>
	</tr>
	<tr>
		<td width="150" height="20" class="htitle" align="center">
			����ð�
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
				</SELECT> ��
				<SELECT NAME="getMonth2">
<?
for($i=1; $i < 13; $i++) {
?>
					<OPTION VALUE="<?=$i?>" <?  if($i==$curDT["mon"]) echo " SELECTED "; ?>><?=$i?></OPTION>
<?
}
?>
				</SELECT> �� 
				<SELECT NAME="getDay2">
<?
for($i=1; $i < 32; $i++) {
?>
					<OPTION VALUE="<?=$i?>" <?  if($i==$curDT["mday"]) echo " SELECTED "; ?>><?=$i?></OPTION>
<?
}
?>
				</SELECT> �� 
				&nbsp;&nbsp;
				<SELECT NAME="getHour2">
<?
for($i=0; $i < 24; $i++) {
?>
					<OPTION VALUE="<?=$i?>"><?=sprintf("%02d",$i)?></OPTION>
<?
}
?>
				</SELECT> �� 
				<SELECT NAME="getMinute2">
<?
for($i=0; $i < 56; $i=$i+5) {
?>
					<OPTION VALUE="<?=$i?>"><?=sprintf("%02d",$i)?></OPTION>
<?
}
?>
				</SELECT> �� 
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
