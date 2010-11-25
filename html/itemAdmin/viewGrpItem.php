<?php
/* =============================================================================
File : itemAdmin/viewGrpItem.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	���ٰ��� : A
	���� Group�� �����ش�.
	itemIndex�� ���� ������ ������ �޴´�.
	- $_GET["examID"] : ���� ID
	- $_GET["myPg"] : itemIndex�� Page
	- $_GET["myGrpID"] : ���� Group�� ID
	- $_GET["isIndep"] : �������׿���
	  > ��������(Y)�̸� �Ϲ� ����ó�� �����ش�.(���� �ƿ� �Ϲݹ��� ��������
            �̵���ų ������ �����
	  > ����Group�� ���� ���(N)���� ���� ���׵�� �����߰� Button�� 
	    �����ȴ�.
============================================================================= */
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "header.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
	exit;
}
$sql = "SELECT myTitle, myStart, myEnd FROM examAdmin WHERE myID = " . $_GET["examID"];
if(!$DB->query($sql)) {
        $fnc->alertBack("Query�� ������ �� �����ϴ�.");
        exit;
}
$result = $DB->fetch();
?>
<!--
//
//��ȣ�� css test �Ѱ�
//
-->

<table border="0" width="590" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2" width="590">
			<img src="<?=IMG_URL?>site/examadmin/title_2.gif" border="0">
		</td>
	</tr>
	<tr>
        	<td width"" class="htitle" align="center">�����</td>
		<td width="" class="htop"><?=$result[0]?></td>
	</tr>
	<tr>
		<td class="htitle" align="center">���۽ð�</td>
		<td class="hmiddle"><?=$result[1]?></td>
	</tr>
	<tr>
        	<td class="htitle" align="center">����ð�</td>
		<td class="hbottom"><?=$result[2]?></td>
	</tr>
</table>
<br>
<br>
<?
// �ش� ���� Group�� ������ �����´�.
$sql = "SELECT mytext FROM ItemGroupTable WHERE myID = " . $_GET["myGrpID"] ;
if(!$DB->query($sql)) {
//	echo $sql;
	$fnc->alertBack("Group�� ���� ������ ������ �� �����ϴ�.");
        exit;
}
$itemGrpText = $DB->fetch();
?>
<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
	<tr>
		<td><?=$itemGrpText[0]?></td>
	</tr>
</TABLE>
<br>
<br>
<?
// �ش� ���� Group�� ������ �����´�.
$sql = "SELECT it.myTest, myCorrect, myScore, isObject, it.myID FROM (ItemGroupTable as igt JOIN  ItemGInfoTable as iit ON(igt.myID = iit.groupID)) JOIN ItemTable as it ON (iit.itemID = it.myID) WHERE igt.myID = " . $_GET["myGrpID"]. " ORDER BY iit.groupOrder ";
if(!$DB->query($sql)) {
//	echo $sql;
	$fnc->alertBack("Group�� ���� ������ ������ �� �����ϴ�.");
        exit;
}
?>
<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="4"><img src="<?=IMG_URL?>site/examadmin/title_6.gif" border="0"></td>
	</tr>
	<TR height="20">
		<TD class="title" align="center">����</TD>
		<TD class="title" align="center">����</TD>
		<TD class="title" align="center">����</TD>
		<TD class="title" align="center">�ܺ�����</TD>
	</TR>
<?
while($itemInfo = $DB->fetch() ) {
?>
	<TR>
		<TD class="cleft" style="padding-left:15px"><A HREF="<?=URL_ROOT?>itemAdmin/viewItem.php?examID=<?=$_GET["examID"]?>&itemID=<?=$itemInfo[4]?>&isIndep=<?=$_GET["isIndep"]?>&myGrpID=<?=$_GET["myGrpID"]?>&myPg=<?=$_GET["myPg"]?>"><?=substr(strip_tags($itemInfo[0]), 0, 15)?></A></TD>
		<TD class="ccenter" align="center"><?=$itemInfo[1]?></TD>
		<TD class="ccenter" align="center"><?=$itemInfo[2]?></TD>
		<TD class="cright" align="center"><?=$itemInfo[3]?></TD>
	</TR>
<?
}
?>
	<Tr>
		<td colspan="4">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" align="right">
			<A HREF="<?=URL_ROOT?>itemAdmin/addItem.php?examID=<?=$_GET["examID"]?>&myGrpID=<?=$_GET["myGrpID"]?>&isIndep=<?=$_GET["isIndep"]?>&myPg=<?=$_GET["myPg"]?>"><img src="<?=IMG_URL?>site/icon/add_group.gif" border="0"></A><A HREF="<?=URL_ROOT?>itemAdmin/itemIndex.php?examID=<?=$_GET["examID"]?>&myPg=<?=$_GET["myPg"]?>"><img src="<?=IMG_URL?>site/icon/ques_list.gif" border="0"></A>
		</td>
	</tr>
</TABLE>
<?
require_once (MY_INCLUDE . "closing.php");
?>
