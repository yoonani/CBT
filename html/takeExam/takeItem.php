<?
/* =============================================================================
File : takeExam/takeItem.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 7
================================================================================
Desc.
	접근가능 A, B, F
	시험을 보는 창이다.
	$_GET["examID"]와 $_GET["myItem"]를 받는다.
============================================================================= */

require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "B", "F")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
	exit;
}
$fnc->alertDiffWin("innerExam", "열 수 없는 창입니다.");
//echo $_GET["myItem"];
$sql = "SELECT a.myText, a.isIndep, b.myTest, b.isObject, d.myFormat, d.myType FROM (ItemGInfoTable as c JOIN (ItemTable as b JOIN ItemAInfoTable as d ON (b.myID = d.itemID) ) ON (c.itemID = b.myID)) JOIN ItemGroupTable as a ON (c.groupID = a.myID) WHERE c.itemID = " . $_GET["myItem"];
if(!$DB->query($sql)) {
	$fnc->alertBack("문항정보를 가져올 수 없습니다");
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
td, body {font-size:10pt; font-family:"돋움,tahoma"; }
</STYLE>
</HEAD>
<SCRIPT LANGUAGE="JavaScript">
function smtAnswer() {
	if(confirm("답안을 제출하시겠습니까?\n제출된 문항은 다시 풀지 못합니다.")) {
		document.smtQue.submit();
	} else {
		return false;
	}
}
</SCRIPT>
<BODY topmargin="5" leftmargin="0">
<?
// Group내 문항이면 Group의 문항을 보여준다.
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
	// 아니면 문항 Item으로 Group Item 사용
	$result[2] = $result[0];
}
?>
<?
//Form을 통해 입력된 문항이면
if($result[3] == "N") {
	echo "<TABLE BORDER=\"0\" WIDTH=\"540\" CELLPADDING=\"5\" CELLSPACING=\"1\" ALIGN=\"CENTER\" BGCOLOR=\"#4470A2\">\n";
?>
<TR>
	<TD ALIGN="CENTER"><FONT COLOR="WHITE">문제</FONT></TD>
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
<!-- 보기 출력 //-->
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
		$fnc->alertBack("보기 정보를 가져올 수 없습니다.");
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
// 단답형 문항이면
echo "
	<TD>정답</TD>
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
// File로 제작된 문항이면
?>
</BODY>
</HTML>
<?
}
ob_end_flush();
?>
