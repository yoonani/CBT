<?
require_once "../include/conf.php";
require_once "../include/mailClass.php";
require_once(MY_INCLUDE . "frmValid.php");

//
// 직접 접근 검사
//
if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        $fnc->alertBack("접근할 수 없습니다.");
        exit;
}

if(!$fnc->isPOST()){
        $fnc->alertBack("접근할 수 없습니다.");
        exit;
}
$id = trim($_POST['id']);
$name = trim($_POST['name']);
$to = trim($_POST['mail']);
//
// 변경할 패스워드
//
$newpasswd = microtime();
$newpasswd = substr(microtime(),strrpos(microtime(),".")+1);
$newpasswd = explode(" ",$newpasswd);
$newpasswd = $newpasswd[0];

$from = "cbt\r\n";
$from .= "Content-Type: text/html; charset=euc-kr\r\n";
$title = "대한의사협회 CBT System : ".$name."님의 새로운 패스워드 입니다.";

$mailcontent = "<TABLE WIDTH=640 BORDER=0 CELLPADDING=0 CELLSPACING=0>";
$mailcontent .= "	<TR>";
$mailcontent .= "		<TD background=\"".IMG_URL."site/mail/mail_01.gif\" WIDTH=640 HEIGHT=70>";
$mailcontent .= "                       	&nbsp;";
$mailcontent .= "		</TD>";
$mailcontent .= "	</TR>";
$mailcontent .= "	<TR>";
$mailcontent .= "		<TD background=\"".IMG_URL."site/mail/mail_02.gif\" WIDTH=640 HEIGHT=117>";
$mailcontent .= "               <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
$mailcontent .= "                       <tr>";
$mailcontent .= "                               <td style=\"font:bold 12pt;padding-left:30px\">의학협회 CBT 운영자입니다.</td>";
$mailcontent .= "                       </tr>";
$mailcontent .= "                       <tr>";
$mailcontent .= "                               <td>&nbsp;</td>";
$mailcontent .= "                       </tr>";
$mailcontent .= "                       <tr height=20>";
$mailcontent .= "                               <td style=\"font:10pt;padding-left:40px;\">대한의사협회 CBT System : ".$name."님의 새로운 패스워드 입니다.</td>";
$mailcontent .= "                       </tr>";
$mailcontent .= "                       <tr height=20>";
$mailcontent .= "                               <td style=\"font:10pt;padding-left:40px;\">".$name." 님의 ID는 <B><font color=\"#990202\">'".$id."'</font></B> 이며 임시 패스워드는 <B><font color=\"#990202\">'".$newpasswd."'</font></B> 입니다</td>";
$mailcontent .= "                       </tr>";
$mailcontent .= "                       <tr height=20>";
$mailcontent .= "                               <td style=\"font:10pt;padding-left:40px;\">로그인 하셔서 정보수정을 통하여 원하는 패스워드로 변경하시기 바랍니다.</td>";
$mailcontent .= "                       </tr>";
$mailcontent .= "               </table>";
$mailcontent .= "		</TD>";
$mailcontent .= "	</TR>";
$mailcontent .= "	<TR>";
$mailcontent .= "		<TD>";
$mailcontent .= "			<IMG SRC=\"".IMG_URL."site/mail/mail_03.gif\" WIDTH=640 HEIGHT=293>";
$mailcontent .= "		</TD>";
$mailcontent .= "	</TR>";
$mailcontent .= "</TABLE>";




//$mailcontent = $name . "님의 ID는 <B>'".$id."'</B> 이며 임시 패스워드는 <B>'".$newpasswd."'</B> 입니다<BR />";
//$mailcontent .= "로그인 하셔서 정보수정을 통하여 원하는 패스워드로 변경하시기 바랍니다.<BR>";
//$mailcontent .= "=======================================================================<BR>";
//$mailcontent .= "&nbsp;&nbsp;&nbsp;오늘 하루도 좋은 하루 보내시고, 하시는 일 잘되시길 빌겠습니다.<BR><BR>";
//$mailcontent .= "&nbsp;&nbsp;&nbsp;서울특별시 용산구 이촌 1동 302-75 대한의사협회<BR>";
//$mailcontent .= "&nbsp;&nbsp;&nbsp;Tel : 02-794-2472,  FAX : 02-792-1296<BR>";
//$mailcontent .= "&nbsp;&nbsp;&nbsp;CBT(Computer-Based Testing) SYSTEM 관리자 드림<BR>";
//$mailcontent .= "=======================================================================<BR>";

//
// 해당 사용자 DB Update
//
$sql = "update userinfo set mypassword = md5('".$newpasswd."') where myID = '".$id."'";
$mail = new baseMail();
$mail->setTo($to);
$mail->setFrom($from);
$mail->setSubject($title);
$mail->setBody($mailcontent);
if($mail->send()) {
	if(!$DB->query($sql)){
		$fnc->alertBack("패스워드를 변경할 수 없습니다.");
	}
?>
<script language="JavaScript">
	alert("메일을 성공적으로 보냈습니다");
	opener.location.href='<?=URL_ROOT?>';
        self.close();
</script>
<?
} else {
?>
<script language="JavaScript">
	alert("메일보내기에 실패하였습니다");
        self.close();
</script>
<?
}
?>
