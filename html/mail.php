<?
require_once "../include/conf.php";
require_once "../include/mailClass.php";
require_once(MY_INCLUDE . "frmValid.php");

//
// ���� ���� �˻�
//
if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        $fnc->alertBack("������ �� �����ϴ�.");
        exit;
}

if(!$fnc->isPOST()){
        $fnc->alertBack("������ �� �����ϴ�.");
        exit;
}
$id = trim($_POST['id']);
$name = trim($_POST['name']);
$to = trim($_POST['mail']);
//
// ������ �н�����
//
$newpasswd = microtime();
$newpasswd = substr(microtime(),strrpos(microtime(),".")+1);
$newpasswd = explode(" ",$newpasswd);
$newpasswd = $newpasswd[0];

$from = "cbt\r\n";
$from .= "Content-Type: text/html; charset=euc-kr\r\n";
$title = "�����ǻ���ȸ CBT System : ".$name."���� ���ο� �н����� �Դϴ�.";

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
$mailcontent .= "                               <td style=\"font:bold 12pt;padding-left:30px\">������ȸ CBT ����Դϴ�.</td>";
$mailcontent .= "                       </tr>";
$mailcontent .= "                       <tr>";
$mailcontent .= "                               <td>&nbsp;</td>";
$mailcontent .= "                       </tr>";
$mailcontent .= "                       <tr height=20>";
$mailcontent .= "                               <td style=\"font:10pt;padding-left:40px;\">�����ǻ���ȸ CBT System : ".$name."���� ���ο� �н����� �Դϴ�.</td>";
$mailcontent .= "                       </tr>";
$mailcontent .= "                       <tr height=20>";
$mailcontent .= "                               <td style=\"font:10pt;padding-left:40px;\">".$name." ���� ID�� <B><font color=\"#990202\">'".$id."'</font></B> �̸� �ӽ� �н������ <B><font color=\"#990202\">'".$newpasswd."'</font></B> �Դϴ�</td>";
$mailcontent .= "                       </tr>";
$mailcontent .= "                       <tr height=20>";
$mailcontent .= "                               <td style=\"font:10pt;padding-left:40px;\">�α��� �ϼż� ���������� ���Ͽ� ���ϴ� �н������ �����Ͻñ� �ٶ��ϴ�.</td>";
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




//$mailcontent = $name . "���� ID�� <B>'".$id."'</B> �̸� �ӽ� �н������ <B>'".$newpasswd."'</B> �Դϴ�<BR />";
//$mailcontent .= "�α��� �ϼż� ���������� ���Ͽ� ���ϴ� �н������ �����Ͻñ� �ٶ��ϴ�.<BR>";
//$mailcontent .= "=======================================================================<BR>";
//$mailcontent .= "&nbsp;&nbsp;&nbsp;���� �Ϸ絵 ���� �Ϸ� �����ð�, �Ͻô� �� �ߵǽñ� ���ڽ��ϴ�.<BR><BR>";
//$mailcontent .= "&nbsp;&nbsp;&nbsp;����Ư���� ��걸 ���� 1�� 302-75 �����ǻ���ȸ<BR>";
//$mailcontent .= "&nbsp;&nbsp;&nbsp;Tel : 02-794-2472,  FAX : 02-792-1296<BR>";
//$mailcontent .= "&nbsp;&nbsp;&nbsp;CBT(Computer-Based Testing) SYSTEM ������ �帲<BR>";
//$mailcontent .= "=======================================================================<BR>";

//
// �ش� ����� DB Update
//
$sql = "update userinfo set mypassword = md5('".$newpasswd."') where myID = '".$id."'";
$mail = new baseMail();
$mail->setTo($to);
$mail->setFrom($from);
$mail->setSubject($title);
$mail->setBody($mailcontent);
if($mail->send()) {
	if(!$DB->query($sql)){
		$fnc->alertBack("�н����带 ������ �� �����ϴ�.");
	}
?>
<script language="JavaScript">
	alert("������ ���������� ���½��ϴ�");
	opener.location.href='<?=URL_ROOT?>';
        self.close();
</script>
<?
} else {
?>
<script language="JavaScript">
	alert("���Ϻ����⿡ �����Ͽ����ϴ�");
        self.close();
</script>
<?
}
?>
