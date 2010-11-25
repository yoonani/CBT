<?
/* =============================================================================
File : findProc.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 06
================================================================================
Desc.
        find.php로부터  POST방식으로 학교ID(univ)와 학번(sno)을 전달받아 해당 
	학교의 학번에 해당하는 ID를 찾는 Process 파일
	ID 가 존재하지 않을 경우 경고 후 다시 뒤로 간다
============================================================================= */
require_once("../include/conf.php");
require_once(MY_INCLUDE . "frmValid.php");

//
// 직접 접근 검사
//
if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
?>
<SCRIPT LANGUAGE="JavaScript">
	alert("접근할 수 없습니다.");
	self.close();
</SCRIPT>
<?
}

if(!$fnc->isPOST()){
?>
<SCRIPT LANGUAGE="JavaScript">
        alert("접근할 수 없습니다.");
        self.close();
</SCRIPT>
<?
}
//
// window.name 검사
//
?>
<SCRIPT LANGUAGE="JavaScript">
	if(window.name != 'FindID'){
		alert("접근할 수 없습니다.");
		self.close();
	}
</SCRIPT>
<?
//
// find.php에서 전달받은 학교(univ)와 학번(sno)의 값이 올바른지 검사
//
if($fv->lengthlt($_POST["sno"], 1)) {
?>
<SCRIPT LANGUAGE="JavaScript">
        alert("학번을 입력하세요");
        self.close();
</SCRIPT>
<?
}
if($fv->lengthlt($_POST["univ"], 1)) {
?>
<SCRIPT LANGUAGE="JavaScript">
        alert("학교를 선택하세요");
        self.close();
</SCRIPT>
<?
}

//
// 해당 학교ID와 학생의 학번에 matching 되는 학생 ID 를 DB에서 추출하는 쿼리 -------> 1번
//
$sql = "select u.myID, us.studeemail, us.studName from userinfo u join univstudent us on us.univID = u.univid and us.univSNO = u.univSNO where u.univid = '".$_POST['univ']."' and u.univsno = '".$_POST['sno']."'";

//
// 상단의 1번 쿼리 DB 실행
//
if(!$DB->query($sql)){
?>
<SCRIPT LANGUAGE="JavaScript">
	alert("학교와 학번 정보를 불러올 수 없습니다 잠시후에 다시 시도해 주세요");
        self.close();
</SCRIPT>
<?
}

//
// 쿼리의 결과가 0줄일 때(ID가 존재하지 않음)
//
if($row = $DB->noRows() < 1) {
?>
<SCRIPT LANGUAGE="JavaScript">
        alert("존재하지 않는 아이디입니다. 회원가입 후 사용하세요");
        self.close();
</SCRIPT>
<?
}

//
// 해당 학교ID와 학생의 학번에 matching 되는 학생 ID와 e-mail의 값을 받아옴 -------> 2번
//
$myID = $DB->getResult(0,0);
$myEmail = $DB->getResult(0,1);
$myName = $DB->getResult(0,2);

//
// PASSWORD를 찾는 버튼을 클릭하면 새로운 PASSWORD 를 해당 학생의 메일로 보내야 하지만 일단 경고창만 띄움
//
?>
<script language="JavaScript">
function passwdFind() {
	document.sendPasswd.submit();
}
</script>
<LINK REL="stylesheet" TYPE="text/css" HREF="<?=URL_ROOT?>include/css/medcbt.css">
</head>
<!-- ID 화면 출력 -->
<body topmargin="0" leftmargin="0">
<form name="sendPasswd" method="POST" action="mail.php">
<input type="hidden" name="id" value="<?=trim($myID)?>">
<input type="hidden" name="mail" value="<?=trim($myEmail)?>">
<input type="hidden" name="name" value="<?=trim($myName)?>">
</form>
<table border="0" width="300" height="200" cellpadding="0" cellspacing="0">
<tr>
	<td height="80"><img src="<?=IMG_URL?>site/userjoin/pw_title.gif"></td>
</tr>
</tr>
	<td height="80" align="center" background="<?=IMG_URL?>site/userjoin/pw_bg1.gif">
		<table border="0" width="300" height="80" cellpadding="0" cellspacing="0">
			<tr>
				<td align="center" height="20" style="color:#A82923">아이디는 '<b><?=$myID?></b>' 입니다.</td>
			</tr>
			<tr>
				<td align="center" height="20" style="color:#A82923">패스워드는 E-MAIL로 발송됩니다.</td>
			</tr>
			<tr>
				<td align="center" height="20" style="color:#A82923;font:11pt 굴림;"><b><?=$myEmail?></b></td>
			</tr>
			<tr>
				<td align="center" height="20" style="color:#A82923">주소가 맞으면 보내기 버튼을 눌러주세요.</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td height="40" align="right" background="<?=IMG_URL?>site/userjoin/pw_bg2.gif"><a href="javascript:passwdFind()"><img src="<?=IMG_URL?>site/icon/pw_send.gif"></a>  <a href="javascript:window.close()"><img src="<?=IMG_URL?>site/icon/pw_cancel.gif"></a>&nbsp;&nbsp;</td>
</tr>
</table>
</body>
<?
ob_end_flush();
?>

