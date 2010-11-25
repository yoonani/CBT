<?
/* =============================================================================
File : takeExam/takeItem.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 7
================================================================================
Desc.
	���ٰ��� A, B, F
	������ ���� â�̴�.
	$_GET["examID"]�� $_GET["myItem"]�� �޴´�.
============================================================================= */

require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "B", "F")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
	exit;
}
$fnc->alertDiffWin("innerExam", "�� �� ���� â�Դϴ�.");
//echo $_GET["myItem"];
$sql = "SELECT a.myText, a.isIndep, b.myTest, b.isObject, d.myFormat, d.myType FROM (ItemGInfoTable as c JOIN (ItemTable as b JOIN ItemAInfoTable as d ON (b.myID = d.itemID) ) ON (c.itemID = b.myID)) JOIN ItemGroupTable as a ON (c.groupID = a.myID) WHERE c.itemID = " . $_GET["myItem"];
if(!$DB->query($sql)) {
	$fnc->alertBack("���������� ������ �� �����ϴ�");
	exit;
}
$result = $DB->fetch();
?>
<HTML>
<HEAD>
<script language="JavaScript">
 try {
 document.attachEvent('oncontextmenu', function () {
  return false;
 });
 } catch(e) {}
</script>
<STYLE TYPE="text/css">
td, body {font-size:10pt; font-family:"����,tahoma"; }
</STYLE>
</HEAD>
<SCRIPT LANGUAGE="JavaScript">
function smtAnswer() {
	if(confirm("����� �����Ͻðڽ��ϱ�?\n����� ������ �ٽ� Ǯ�� ���մϴ�.")) {
		document.smtQue.submit();
	} else {
		return false;
	}
}
</SCRIPT>
<BODY topmargin="5" leftmargin="0">
<?
// Group�� �����̸� Group�� ������ �����ش�.
if($result[1] == "N") {
echo "
<TABLE BORDER=\"0\" WIDTH=\"500\" CELLPADDING=\"5\" CELLSPACING=\"0\" ALIGN=\"CENTER\" style=\"background-color:white; border-width:1; border-color:rgb(68,112,162); border-style:dotted;\">
<TR>
	<TD>
";
echo trim($result[0]);
echo "
	</TD>
</TR>
</TABLE>
<BR />
";
} else {
	// �ƴϸ� ���� Item���� Group Item ���
	$result[2] = $result[0];
}
?>
<?
//Form�� ���� �Էµ� �����̸�
if($result[3] == "N") {
	echo "<TABLE BORDER=\"0\" WIDTH=\"540\" CELLPADDING=\"5\" CELLSPACING=\"1\" ALIGN=\"CENTER\" BGCOLOR=\"#4470A2\">\n";
?>
<TR>
	<TD ALIGN="CENTER"><FONT COLOR="WHITE">����</FONT></TD>
</TR>
<TR>
	<TD BGCOLOR="WHITE">
		<textarea id=txt1 style="display:none;" rows="0" cols="0">
		<?=trim($result[2])?>
		</textarea>
		<script language="javascript" src="okplugin_js.php?txtid=txt1"></script>
	</TD>
</TR>
</TABLE>
<!-- ���� ��� //-->
<?
switch($result[4]) {
	case "A" :
	case "K" :
		$optType = "radio";
		$optName = "smtOption";
		break;
	case "R" :
		$optType = "checkbox";
		$optName = "smtOption[]";
		break;
}
?>
<TABLE WIDTH="540" ALIGN="CENTER">
<FORM NAME="smtQue" METHOD="POST" ACTION="<?=URL_ROOT?>takeExam/procSolve.php">
<TR>
<?
$imgPath = IMG_URL . "testimages/" . $_GET["examID"] . "/";
if($result[4] != "D") {
	$sql2 = "SELECT myText, myImage FROM OptionTable WHERE ItemID = " . $_GET["myItem"]  . " ORDER BY myOrder";
	if(!$DB->query($sql2)) {
		$fnc->alertBack("���� ������ ������ �� �����ϴ�.");
		exit;
	}
	$i = 1;
	while($result2 = $DB->fetch()) {
?>
<TR>
	<TD WIDTH="30" ALIGN="CENTER" VALIGN="TOP"><INPUT TYPE="<?=$optType?>" NAME="<?=$optName?>" VALUE="<?=$i?>"></TD>
	<TD WIDTH="50" VALIGN="TOP"><?=$i?>]</TD>
	<TD WIDTH="460">&nbsp;<? if($result[5] != "A") echo "<IMG SRC=\"" . $imgPath . trim($result2[1]) . "\">"?><?=trim($result2[0])?></TD>
<?
		$i++;
	}
echo "
</TR>
";
} else {
// �ܴ��� �����̸�
echo "
	<TD>����</TD>
	<TD><INPUT TYPE=\"TEXT\" NAME=\"smtOption\"></TD>
";
}
?>
</TR>
</TABLE>
<INPUT TYPE="HIDDEN" NAME="examID" VALUE="<?=$_GET["examID"]?>">
<INPUT TYPE="HIDDEN" NAME="myItem" VALUE="<?=$_GET["myItem"]?>">
<INPUT TYPE="HIDDEN" NAME="getOptType" VALUE="<?=$result["4"]?>">
<INPUT TYPE="HIDDEN" NAME="getOptNo" VALUE="<?=$i?>">
<P ALIGN="CENTER"><A HREF="#" Onclick="smtAnswer();"><img src="<?=IMG_URL?>site/icon/exam_send.gif" width="77" height="38" border="0"></A></CENTER>
</FORM>
<?
} else {
// File�� ���۵� �����̸�
?>
</BODY>
</HTML>
<?
}
ob_end_flush();
?>
