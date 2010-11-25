<?
require_once("../include/conf.php");
//
// 창에서 직접 여는 것 제어
//
?>
<HTML>
<HEAD>
<TITLE><?=TITLE?></TITLE>
</HEAD>
<SCRIPT LANGUAGE="JavaScript">
if(window.name != 'checkID') {
        alert('접근할 수 없습니다');
        window.close();
}
</SCRIPT>
<?
//
// 부모창 userinput.php에서 받아온 userId 정보
//
$getUserID = $_GET['userID'];

if(!$getUserID) {
        $fnc->alert("ID를 입력하여 주세요");
?>
<SCRIPT LANGUAGE="JavaScript">
        opener.document.join.userID.focus();
        opener.document.join.userID.select();
        self.close();
</SCRIPT>
<?
}

//
// stristr함수로 admin, administrator 으로 시작 혹은 끝나는 ID 제어
//
$chk_admin = stristr($getUserID,"admin");
if($chk_admin){
        $fnc->alert("Admin, admin이 들어간 ID는 사용하실수 없습니다");
?>
<SCRIPT LANGUAGE="JavaScript">
        opener.document.join.userID.focus();
        opener.document.join.userID.select();
        self.close();
</SCRIPT>
<?
}
$query = "select myID from userinfo where myID = '$getUserID' union select myID from staffinfo where myID = '$getUserID'";


if(!$DB->query($query)){
        echo $DB->error();
        exit;
}
?>
<LINK REL="stylesheet" TYPE="text/css" HREF="<?=URL_ROOT?>include/css/medcbt.css">
<body topmargin="0" leftmargin="0">
<?
if($row = $DB->noRows()){
?>
<FORM NAME="subuserInput" METHOD="GET" ACTION="<?=$_SERVER['PHP_SELF']?>">
<table width="300" height="200" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td><img src="<?=IMG_URL?>site/userjoin/check_title2.gif" border="0"></td>
</tr>
<tr>
	<td height="30" align="center">
		<table border="0" width="200" height="20" cellpadding="0" cellspacing="0">
		<tr>
			<td width="80" class="htitle" align="center">아이디</td>
			<td width="120" style="padding-left:10px;border-top:none;border-bottom:none;border-right:none;"><INPUT TYPE="TEXT" NAME="userID" SIZE= "15" MAXLENGTH="12"></td>
		</tr>
		</table>
	</td>
</tr>
<tr height="40">
	<td align="right"><?$fnc->imgButton(66, 36, "javascript:subuserInput.submit();", IMG_URL . "site/icon/submit.gif");?></td>
</tr>
</table>
</FORM>
<?
}else{
?>
<script language="JavaScript">
function change(string) {
        opener.document.join.userID.value = string;
        opener.document.join.chkID.value="T";
        for(var i=0; opener.document.join.elements[i];i++){
                opener.document.join.elements[i].readOnly = false;
        }
        opener.document.join.userPasswd1.focus();
        self.close();



}
</script>
<table width="300" height="200" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td height="100"><img src="<?=IMG_URL?>site/userjoin/check_title1.gif" border="0"></td>
</tr>
<tr>
	<td align="center" style="font:bold 10pt 굴림;color:black;">'<?=$getUserID?>' 는 사용 가능합니다.</td>
</tr>
<tr>
	<td align="right"><?$fnc->imgButton(66, 36, "javascript:change('$getUserID')", IMG_URL . "site/icon/submit.gif");$fnc->imgButton(66, 36, "javascript:window.close()", IMG_URL . "site/icon/cancel.gif");?></td>
</tr>
</table>
<?
}
?>
</body>
<?
ob_end_flush();
?>

