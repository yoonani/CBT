<?php
/* =============================================================================
File : itemAdmin/viewItem.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	���ٰ��� : A
	���׼��� ������ �����ش�.
	itemIndex���� ���� ���� ��������.
	viewGrpItem���κ��� ���� ���� Group�� ����
	- $_GET["examID"] : ���� ID
	- $_GET["myPg"] : itemIndex�� Page
	- $_GET["myGrpID"] : ���� Group�� ID
	- $_GET["isIndep"] : �������׿���
	  > ��������(Y)�̸� �Ϲ� ����ó�� �����ش�.(���� �ƿ� �Ϲݹ��� ��������
            �̵���ų ������ �����
	  > ����Group�� ���� ���(N)���� ���� ���׵�� �����߰� Button�� 
	    �����ȴ�.
	- $_GET["itemID"] : ���� ID
============================================================================= */
$useItemInfo = "Y";
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
<H3>���� ����</H3>
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
<!-- Group���� �������� //-->
<?
if($_GET["isIndep"] == "N") {
?>
<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
<tr>
	<td><img src="<?=IMG_URL?>site/examadmin/title_18.gif">
</tr>
<TR>
<?
	$sql = "SELECT myText FROM ItemGroupTable WHERE myID = " . $_GET["myGrpID"];
	if(!$DB->query($sql)) {
		$fnc->alertBack("�Ӹ� ���������� ������ �� �����ϴ�.");
       	 	exit;
	}
	$result = $DB->fetch();
?>
	<TD COLSPAN="4" class="hbottom_bold"><?=trim($result[0])?></TD>
</TR>
</TABLE>
<br />
<?
}

// �ش� ������ ������ �����´�.
// ���� ������ ��� ������ ItemGroupTable�� �����Ƿ� Query�� �ΰ��� ������.
if($_GET["isIndep"] == "Y") {
	$sql = "SELECT c.myText, a.myCorrect, a.myScore, a.isObject, d.myFormat, d.myCategory,  d.myLevel, d.mySubject, d.myMeSH, d.myCase, d.myType FROM (ItemTable as a JOIN ItemAInfoTable as d ON (a.myID = d.itemID)) , ItemGInfoTable as b, ItemGroupTable as c WHERE a.myID = b.itemID AND b.groupID = c.myID AND a.myID = " . $_GET["itemID"];
} else {
	$sql = "SELECT a.myTest, a.myCorrect, a.myScore, a.isObject, d.myFormat, d.myCategory,  d.myLevel, d.mySubject, d.myMeSH, d.myCase, d.myType FROM ItemTable as a JOIN ItemAInfoTable as d ON (a.myID = d.itemID) WHERE a.myID = " . $_GET["itemID"];
}
if(!$DB->query($sql)) {
//	echo $sql;
	$fnc->alertBack("���� ������ ������ �� �����ϴ�.");
        exit;
}
$result = $DB->fetch();
?>
<!-- �������� ���� //-->
<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="4"><img src="<?=IMG_URL?>site/examadmin/title_4.gif"></td>
	</tr>
	<tr>
		<td WIDTH="70" HEIGHT="20" ALIGN="CENTER" CLASS="htitle">Subject</td>
		<td WIDTH="225" ALIGN="CENTER" CLASS="htop"><?=ucfirst($mySubject[trim($result[7])])?></td>
		<td WIDTH="70" ALIGN="CENTER" CLASS="htitle">Format</td>
		<td WIDTH="225" ALIGN="CENTER" CLASS="htop"><?=ucfirst($myFormat[trim($result[4])])?></td>
	</tr>
	<tr>
		<td HEIGHT="20" ALIGN="CENTER" CLASS="htitle">Category</td>
		<td ALIGN="CENTER" CLASS="hmiddle"><?=ucfirst($myCategory[trim($result[5])])?></td>
		<td ALIGN="CENTER" CLASS="htitle">Item Type</td>
		<td ALIGN="CENTER" CLASS="hmiddle"><?=ucfirst($myItemType[trim($result[6])])?></td>
	</tr>
	<tr>
		<td HEIGHT="20" ALIGN="CENTER" CLASS="htitle">MeSH</td>
		<td COLSPAN="3" CLASS="hbottom">&nbsp;<?=trim($result[8])?></td>
	</tr>
</TABLE>
<br>
<!-- ���� ���� //-->
<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
	<tr>
		<td WIDTH="590" COLSPAN="2"><img src="<?=IMG_URL?>site/examadmin/title_19.gif"></td>
	</tr>
<?
if($result[3] == "Y") {
	// �ܺ����Ͽ� ���� �����϶�
	$sql = "SELECT myFile FROM ObjectTable WHERE itemID = " . $_GET["itemID"];
	if(!$DB->query($sql)) {
	//	echo $sql;
		$fnc->alertBack("���� ������ ������ �� �����ϴ�.");
       	 	exit;
	}
	$object = $DB->getResult(0, 0);
	// �ܺ� ���� ���׺���
	// File�� �����ͼ� �����־�� �Ѵ�.
	// FLASH���� ���� JavaScript
?>
	<TR>
		<TD WIDTH="590" class="pad15"><?=$object[0]?></TD>
	</TR>
	<tr>
		<td CLASS="hbottom_bold2">&nbsp;</td>
	</tr>
</TABLE>
<?
} else {
?>
	<TR>
		<TD WIDTH="590" class="pad15"><?=$result[0]?></TD>
	</TR>
	<tr>
		<td CLASS="hbottom_bold2">&nbsp;</td>
	</tr>
</TABLE>
<br />
<TABLE border="0" width="590" cellpadding="0" cellspacing="0" style="border-top:solid 1px #9EB1DB">
<?
	// FORM�� ���ؼ� ������ �����϶�
	// ���� ������ �����´�.
	$sql = "SELECT myText, myImage FROM OptionTable WHERE itemID = " . $_GET["itemID"] . " ORDER BY myOrder ASC";
	if(!$DB->query($sql)) {
		$fnc->alertBack("���� ������ ������ �� �����ϴ�.");
       	 	exit;
	}
	$idx = 1;
	while($options = $DB->fetch()) {
		if($result[10] != "A") {
			$imgStr =  "<IMG SRC=\"" . IMG_URL . "testimages/" . $_GET["examID"] . "/" . $options[1] . "\">&nbsp;";
		}
		// FORM�� ���� ������ ���׺���
?>
		<TR>
			<TD WIDTH="90" ALIGN="CENTER" CLASS="htitle2">���� <?=$idx?></TD>
			<TD WIDTH="500" class="hbottom2"><?=$imgStr?><?=$options[0]?></TD>
		</TR>
<?
		$idx++;
	}
}
?>
</TABLE>
<br />
<TABLE border="0" width="590" cellpadding="0" cellspacing="0" style="border-top:solid 1px #9EB1DB">
	<TR>
		<TD ALIGN="CENTER" WIDTH="45" HEIGHT="20" style="background:#9EB1DB;color:white;">����</TD>
		<TD ALIGN="CENTER" WIDTH="250" class="hbottom2"><?=$result[1]?></TD>
		<TD ALIGN="CENTER" WIDTH="45" HEIGHT="20" style="background:#9EB1DB;color:white;">����</TD>
		<TD ALIGN="CENTER" WIDTH="250" class="hbottom2"><?=$result[2]?>��</TD>
	</TR>
</TABLE>
	
<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
	<tr>
		<td align="right">[<A HREF="<?=URL_ROOT?>itemAdmin/itemIndex.php?examID=<?=$_GET["examID"]?>&myPg=<?=$_GET["myPg"]?>">������</A>][<A HREF="<?=URL_ROOT?>/itemAdmin/modiItem.php?examID=<?=$_GET['examID']?>&myPg=<?=$_GET['myPg']?>&itemID=<?=$_GET['itemID']?>&myGrpID=<?=$_GET['myGrpID']?>&isIndep=<?=$_GET['isIndep']?>">����</A>][����]</td>
	</tr>
</TABLE>
<?
// Group ������ ��� Group���� ���׵鵵 ��� �����ش�.
if($_GET["isIndep"] == "N") {
?>
<br />
<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
<TR>
	<TD CLASS="htitle" ALIGN="CENTER" HEIGHT="20" WIDTH="40">No</TD>
	<TD CLASS="htitle" ALIGN="CENTER" WIDTH="350">����</TD>
	<TD CLASS="htitle" ALIGN="CENTER" WIDTH="100">����</TD>
	<TD CLASS="htitle" ALIGN="CENTER" WIDTH="100">����</TD>
</TR>
<!-- ���� ���׵� //-->
<?
	unset($result);
	$sql = "SELECT a.myID, a.myTest, a.myCorrect, a.myScore, a.isObject FROM ItemTable as a JOIN ItemGInfoTable as b ON (a.myID = b.itemID) WHERE b.groupID = " . $_GET["myGrpID"] . " ORDER BY a.myID ASC";	
	if(!$DB->query($sql)) {
		$fnc->alertBack("���� Group���� ���������� ������ �� �����ϴ�.");
       	 	exit;
	}
	$idx = 1;
	while($result = $DB->fetch()) {
		if($result[4] == "Y") {
			$result[1] = "�ܺ�����";
		}
?>
<TR>
	<TD ALIGN="CENTER" WIDTH="30" HEIGHT="20" CLASS="hleft">
<?
// ���� �����̸� ȭ��ǥ Icon ��� �ƴϸ� $idx �� ǥ��
		if($result[0] == $_GET["itemID"]) {
			echo "<IMG SRC=\"" . IMG_URL . "site/icon/now.gif\" ALIGN=\"absmiddle\">";
		} else {
			echo $idx;
		}
?>
	</TD>
	<TD CLASS="hcenter">&nbsp;<A HREF="<?=URL_ROOT?>itemAdmin/viewItem.php?examID=<?=$_GET["examID"]?>&itemID=<?=$result[0]?>&isIndep=N&myGrpID=<?=$_GET["myGrpID"]?>&myPg=<?=$_GET["myPg"]?>"><?=substr(strip_tags(trim($result[1])), 0, 25)?></A></TD>
	<TD ALIGN="CENTER" CLASS="hcenter"><?=substr(strip_tags(trim($result[2])), 0, 10)?></TD>
	<TD ALIGN="CENTER" CLASS="hright"><?=$result[3]?></TD>
</TR>
<?
		$idx++;
	}
?>
</TABLE>
<?
}
require_once (MY_INCLUDE . "closing.php");
?>
