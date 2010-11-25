<?php
/* =============================================================================
File : itemAdmin/addItem.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	���ٰ��� : A
	���� ����
	- Group�� ����, ���� ���� ��� ����
	- �Ѿ�� ����
	  = $_GET["examID"] : ���� ID
	  = $_GET["myGrpID"] : ���� Group�� ID
	  = $_GET["isIndep"] : ���� Group����, N -> ���� Group, Y -> �ܵ�����
	  = $_GET["myPg"] : �� ���躰 ���׸���Ʈ ������ ��ȣ
============================================================================= */
$useItemInfo = "Y";
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "header.php");

if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
	exit;
}
if($_GET["isIndep"] == "N") {
	$myTitleImg = "site/examadmin/title_5.gif";
} else {
	$myTitleImg = "site/examadmin/title_7.gif";
}
?>
<FORM NAME="itemStep1" ACTION="<?=URL_ROOT?>itemAdmin/addItemStep2.php" METHOD="POST">
<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="2"><img src="<?=IMG_URL.$myTitleImg?>" border="0"></td>
</tr>
<TR>
	<TD width="150" height="20" align="center" class="htitle">�����Է�</TD>
	<TD width="440" class="htop">
<SELECT NAME="getItemInputType">
	<OPTION VALUE="N">Form�Է�</OPTION>
	<OPTION VALUE="Y">�ܺ�����</OPTION>
</SELECT>
	</TD>
</TR>
<TR>
	<TD align="center" height="20" class="htitle">Subject</TD>
	<TD class="hmiddle">
<SELECT NAME="getSubject">
<?
while(list($key, $val) = each($mySubject)) {
	echo "
	<OPTION VALUE='" . $key . "'>" . ucfirst($val) ."</OPTION>";
}
?>
</SELECT>
	</TD>
</TR>
<TR>
	<TD align="center" height="20" class="htitle">Format</TD>
	<TD class="hmiddle">
<SELECT NAME="getFormat">
<?
while(list($key, $val) = each($myFormat)) {
	if($val != "������") 
	echo "
	<OPTION VALUE='" . $key . "'>" . $val ."</OPTION>";
}
?>
</SELECT>
	</TD>
</TR>
<TR>
	<TD align="center" height="20" class="htitle">Category</TD>
	<TD class="hmiddle">
<SELECT NAME="getCategory">
<?
while(list($key, $val) = each($myCategory)) {
	echo "
	<OPTION VALUE='" . $key . "'>" . ucfirst($val) ."</OPTION>";
}
?>
</SELECT>
	</TD>
</TR>
<TR>
	<TD align="center" height="20" class="htitle">Item Type</TD>
	<TD class="hmiddle">
<SELECT NAME="getLevel">
<?
while(list($key, $val) = each($myItemType)) {
	echo "
	<OPTION VALUE='" . $key . "'>" . $val ."</OPTION>";
}
?>
</SELECT>
	</TD>
</TR>
<TR>
	<TD align="center" height="20" class="htitle">MeSH</TD>
	<TD class="hmiddle"><INPUT TYPE="TEXT" NAME="getMeSH" SIZE="40" MAXLENGTH="255"></TD>
</TR>
<TR>
	<TD align="center" height="20" class="htitle">������ ����</TD>
	<TD class="hmiddle">
<SELECT NAME="getOptType">
	<OPTION VALUE="A">TEXT</OPTION>
	<OPTION VALUE="B">Image</OPTION>
	<OPTION VALUE="C">TEXT+Image</OPTION>
</SELECT>
	</TD>
</TR>
<TR>
	<TD align="center" height="20" class="htitle">������ ����</TD>
	<TD class="hbottom">
<SELECT NAME="getOptNo">
	<OPTION VALUE="1">1</OPTION>
	<OPTION VALUE="2">2</OPTION>
	<OPTION VALUE="3">3</OPTION>
	<OPTION VALUE="4" SELECTED>4</OPTION>
	<OPTION VALUE="5">5</OPTION>
	<OPTION VALUE="6">6</OPTION>
	<OPTION VALUE="7">7</OPTION>
	<OPTION VALUE="8">8</OPTION>
	<OPTION VALUE="9">9</OPTION>
	<OPTION VALUE="10">10</OPTION>
</SELECT>
	</TD>
</TR>
<INPUT TYPE="HIDDEN" NAME="examID" VALUE="<?=$_GET["examID"]?>">
<INPUT TYPE="HIDDEN" NAME="myGrpID" VALUE="<?=$_GET["myGrpID"]?>">
<INPUT TYPE="HIDDEN" NAME="isIndep" VALUE="<?=$_GET["isIndep"]?>">
<INPUT TYPE="HIDDEN" NAME="myPg" VALUE="<?=$_GET["myPg"]?>">
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td align="right" colspan="2"><a href="javascript:itemStep1.submit();"><img src="<?=IMG_URL?>site/icon/continu.gif"></a><!--<INPUT TYPE="SUBMIT" NAME="submit">--></td>
</tr>
</TABLE>
</FORM>
<?
require_once (MY_INCLUDE . "closing.php");
?>
