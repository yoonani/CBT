<?
/* =============================================================================
File : eachUni/index.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 06
================================================================================
Desc.
	�б��� �л� ��� ������
	�б��� ������(F), ��ü ������(A) ���� ����
	�б��� �л� �߰�, ����, ������ ����
============================================================================= */
$usePGNav = "Y";
require_once("../../include/conf.php");

//
// ���� ���� ����
//
//if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        //$fnc->alert("������ �� �����ϴ�.Here");
        //$fnc->alert(URL_ROOT);
	//$fnc->alert($_SERVER[HTTP_REFERER]);
	//exit;
//}

//
// ���� ���� $_SESSION['Level'] �� 'A' or 'F' �� ������ �� �ִ�
//
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "F")) ) {
        $fnc->alertBack("������ �� ���� �����Դϴ�.");
        exit;
}

require_once (MY_INCLUDE . "header.php");

//
// ��ü ������(A) �� ���� univID�� GET������� ���޹޾� �б��� �л� ����� �����ش�
// �б��� ������(F) �� ��� �α��� �� ������ $_SESSION['UID']�� �б�ID�� �ش� �б��� �л� ����� �����ش�
//
if($_SESSION['Level'] == "A"){
	$UID = $_GET['univID'];
}else{
	$UID = $_SESSION['UID'];
}
?>
<?
//
// ��ü �������� ��츸 �б� �������� ������ ���δ�
//
if($_SESSION['Level'] == 'A'){
	$adminSql = "select u.myCode, u.mytitle, s.myName ,s.myID, s.myEmail from univinfo u left join staffinfo s on u.myCode = s.myScode where u.myCode ='".$UID."' and (s.myLevel = 'F' or s.myLevel is null)";
	if(!$DB->query($adminSql)){
		echo $DB->error();
		exit;
	}
//
// �б� �����ڰ� ������ �ȵǾ��� ��� '�̵��' ���, '����' �� ������� '�߰�' �� �����
// �б� �����ڰ� ������ �Ǿ��� ��� '�߰�' �� ������� '����' �� �����
//
	$myCode = $DB->getResult(0,0);
	$mytitle = $DB->getResult(0,1);
	$myName = $DB->getResult(0,2);
	$myID = $DB->getResult(0,3);
	$myEmail = $DB->getResult(0,4);
?>
<SCRIPT LANGUAGE="JavaScript">
function sModi(string, string2) {
        if(confirm("�ش� �б� �������� �ֿ� ������ �����մϴ�. \n���� �����Ͻðڽ���?")) {
                document.staffModi.univID.value = string;
                document.staffModi.myID.value = string2;
                document.staffModi.action = '<?=URL_ROOT?>univAdmin/uAdminModi.php';
                document.staffModi.submit();
        } else {
                return;
        }
}
</SCRIPT>
<FORM NAME="staffModi" METHOD="post" ACTION="">
<INPUT TYPE="HIDDEN" NAME="univID" VALUE="">
<INPUT TYPE="HIDDEN" NAME="myID" VALUE="">
</FORM>
<TABLE BORDER="0" width="590" cellpadding="0" cellspacing="0"> 
<tr>
	<td colspan="5"><img src="<?=IMG_URL?>site/examadmin/title_15.gif"></td>
</tr>
<TR height="20">
	<TD width="120" class="title" align="center">�б���</TD>
	<TD width="120" class="title" align="center">�����ڸ�</TD>
	<TD width="120" class="title" align="center">������ ID</TD>
	<TD width="160" class="title" align="center">������ E-mail</TD>
	<TD width="70" class="title" align="center">����</TD>
</TR>
<TR height="20">
	<TD class="cleft" align="center"><?=trim($mytitle)?></TD>
	<TD class="ccenter" align="center">
<?
if(isset($myName)){
	echo trim($myName);
}else{
	echo "<FONT COLOR='RED'>�̵��</FONT>";
}
?>
	</TD>
	<TD class="ccenter" align="center">
<?
if(isset($myID)){
	echo trim($myID);
}else{
	echo "<FONT COLOR='RED'>�̵��</FONT>";
}
?>
	</TD>
	<TD class="ccenter" align="center">
<?
if(isset($myEmail)){
	echo trim($myEmail);
	echo "</TD>";
	echo "<TD class='cright' align='center'><A HREF=\"javascript:sModi('".trim($myCode)."','".trim($myID)."');\"><img src='".IMG_URL."site/icon/mody_icon.gif'></A></TD>";
	echo "</TR>";
}else{
	echo "<FONT COLOR='RED'>�̵��</FONT>";
	echo "</TD>";
	echo "<TD class='cright' align='center'>&nbsp;</TD>";
	echo "</TR>";
?>
<tr>
	<td colspan="5">&nbsp;</td>
</tr>
<TR>
	<TD COLSPAN="5" ALIGN="RIGHT"><A HREF="<?=URL_ROOT?>univAdmin/uAdminAdd.php?univID=<?=trim($UID)?>"><img src="<?=IMG_URL?>site/icon/input.gif"></A></TD>
</TR>
<?
}
?>
</TABLE>
<?
}
//
// �б� ������ ���� ���� ��
//



//
// �˻��� ���� GET������� ���� ����
//
$key = $_GET['key'];
$keyfield = $_GET['keyfield'];

//
// �˻������� ������ ���� �л��� �� �ο��� ���� ���� -------> 1��
//
if( strlen(trim($key)) > 0 && strlen(trim($keyfield)) > 0 ){
//	$sql = "select univsno, studName, studdept, studgrade from univstudent where univid = '".$UID."' and $keyfield like '%$key%'";
	$sql = "select univSNO from UnivStudent where univid = '".$UID."' and $keyfield like '%$key%' and  myLevel != 'S'";
}else{
//	$sql = "select univsno, studName, studdept, studgrade from univstudent where univid = '".$UID."'";
	$sql = "select univSNO from UnivStudent where univid = '".$UID."' and myLevel != 'S'";
}

//
// ����� 1�� ���� DB����
//
if(!$DB->query($sql)) {
        echo $fnc->alertBack("�л������� �ҷ��� �� �����ϴ�. ��� �� �ٽ� �õ� �ϼ���");
        exit;
}

//
// pgNavigation Instance ���� �� ������ ���� �ʱ�ȭ
//
$total = $DB->noRows();
$pgSize = 10;
$blkSize = 5;
$pgNav = new pgNav($pgSize,$blkSize,$total,1);
$pgNav->initStart($_GET["myPg"]);
$pgStart = ($pgNav->myPage - 1) * $pgSize;

// 
// �˻� ������ page�� ���� �л� ��� ���� ���� -------> 2��
//
if( strlen(trim($key)) > 0 && strlen(trim($keyfield)) > 0 ){
//	$sql = "select univsno, studName, studdept, studgrade, univID from univstudent where univid = '".$UID."' and $keyfield like '%$key%' order by studdept ASC, studgrade ASC, univsno ASC, studName ASC limit $pgSize offset $pgStart";

	$sql = "select us.univsno, us.studName, us.studdept, us.studgrade, us.univID, ui.myID from UnivStudent us left join UserInfo ui on us.univid = ui.univid and us.univSNO = ui.univSNO where us.univid = '".$UID."' and us.".$keyfield." like '%$key%' and us.myLevel != 'S' order by us.studdept ASC, us.studgrade ASC, us.univsno ASC, us.studName ASC limit $pgSize offset $pgStart";
}else{
//	$sql = "select univsno, studName, studdept, studgrade, univID from univstudent where univid = '".$UID."' order by studdept ASC, studgrade ASC, univsno ASC, studName ASC limit $pgSize offset $pgStart";
	$sql = "select us.univsno, us.studName, us.studdept, us.studgrade, us.univID, ui.myID from UnivStudent us left join UserInfo ui on us.univid = ui.univid and us.univSNO = ui.univSNO where us.univid = '".$UID."' and us.myLevel != 'S' order by us.studdept ASC, us.studgrade ASC, us.univsno ASC, us.studName ASC limit $pgSize offset $pgStart";
}

//
// ����� 2�� ���� DB ����
//
if(!$DB->query($sql)) {
        echo $fnc->alertBack("�л������� �ҷ��� �� �����ϴ�. ��� �� �ٽ� �õ� �ϼ���");
        exit;
}

?>
<SCRIPT LANGUAGE="JavaScript">
function stStop(string,string2) {
	if(confirm("�ش��л��� ��� ������ �����˴ϴ�. ���� �����Ͻðڽ���?")) {
		document.stRegStop.univID.value = string;
		document.stRegStop.univSNO.value = string2;
		document.stRegStop.action = '<?=URL_ROOT?>eachUniv/stRegStop.php';
		document.stRegStop.submit();
	} else {
		return;
	}
}
function stModi(string,string2) {
	if(confirm("�ش��л��� �ֿ� ������ �����մϴ�. ���� �����Ͻðڽ���?")) {
		document.stRegModi.univID.value = string;
		document.stRegModi.univSNO.value = string2;
		document.stRegModi.action = '<?=URL_ROOT?>eachUniv/stRegModi.php';
		document.stRegModi.submit();
	} else {
		return;
	}
}
function sInfo(string,string2){
	var form = document.stInfo;
	form.univID.value = string;
	form.univSNO.value = string2;
	form.target = "stInfo";
	window.open('','stInfo','height=300,width=350 resizable=no');
	form.submit();
}

</SCRIPT>
<FORM NAME="stInfo" METHOD="post" ACTION="stInfo.php">
<INPUT TYPE="HIDDEN" NAME="univID" VALUE="">
<INPUT TYPE="HIDDEN" NAME="univSNO" VALUE="">
</FORM>
<FORM NAME="stRegStop" METHOD="post" ACTION="">
<INPUT TYPE="HIDDEN" NAME="univID" VALUE="">
<INPUT TYPE="HIDDEN" NAME="univSNO" VALUE="">
</FORM>
<FORM NAME="stRegModi" METHOD="post" ACTION="">
<INPUT TYPE="HIDDEN" NAME="univID" VALUE="">
<INPUT TYPE="HIDDEN" NAME="univSNO" VALUE="">
</FORM>
<TABLE BORDER="0" width="590" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="7"><img src="<?=IMG_URL?>site/examadmin/title_14.gif" border="0"></td>
</tr>
<TR>
<?
//
// �˻� ������ ���� ȭ�� ���
//
if( strlen(trim($key)) > 0 && strlen(trim($keyfield)) > 0 ){
?>
	<TD COLSPAN="7" ALIGN="RIGHT">�˻��� �л� <?=$total?>��</TD>
<?
}else{
?>
	<TD COLSPAN="7" ALIGN="RIGHT">�� <?=$total?>��</TD>
<?
}
?>
</TR>
<TR height="20">
	<TD width="100" class="title" align="center">�а�</TD>
	<TD width="120" class="title" align="center">�й�</TD>
	<TD width="80" class="title" align="center">�̸�</TD>
	<TD width="100" class="title" align="center">ID</TD>
	<TD width="90" class="title" align="center">�г�</TD>
	<TD width="50" class="title" align="center">����</TD>
	<TD width="50" class="title" align="center">����</TD>
</TR>
<?
//
// �� �л� �ο����� 0���� ��� ȭ�� ���
//
if($total < 1){
?>
<TR>
	<TD COLSPAN="7" ALIGN="CENTER" class="cside" >�л��� �����ϴ�</TD>
</TR>
<?
//
// �� �л� �ο����� 0�� �̻��� ��� �л� ��� ȭ�����
//
}else{

while($row = $DB->fetch()){
?>
<TR height="25">
	<TD class="cleft" align="center"><?=trim($row[2])?></TD>
	<TD class="ccenter" align="center"><?=trim($row[0])?></TD>
	<TD class="ccenter" align="center"><?=trim($row[1])?></TD>
	<TD class="ccenter" align="center">
<?
if(isset($row[5])){
	echo "<A HREF=\"javascript:sInfo('".trim($row[4])."','".trim($row[0])."')\">".trim($row[5])."</A>";
}else{
	echo "<FONT COLOR='RED'>�̵��</FONT>";
}
?>
	</TD>
	<TD class="ccenter" align="center"><?=trim($row[3])?></TD>
	<TD class="ccenter" align="center"><A HREF="javascript:stModi('<?=trim($row[4])?>','<?=trim($row[0])?>')"><img src="<?=IMG_URL?>site/icon/mody_icon.gif" border="0"></A></TD>
	<TD class="cright" align="center"><A HREF="javascript:stStop('<?=trim($row[4])?>','<?=trim($row[0])?>')"><img src="<?=IMG_URL?>site/icon/stop_icon.gif" border="0"></A></TD>
</TR>
<?
}
?>
<TR>
	<TD COLSPAN="7" ALIGN="CENTER">
<?
//
// page, Next Page, Pre Page ȭ�� ���
//

/*if($pgNav->isPrevBLK() ) {
?>
<A HREF="<?=$_SERVER["PHP_SELF"]?>?myPg=<?=$pgNav->prevBLKptr?>">[Pre Block]</A>
<?
}*/

if($pgNav->isPrev() ) {
?>
<A HREF="<?=$_SERVER["PHP_SELF"]?>?univID=<?=$UID?>&key=<?=$key?>&keyfield=<?=$keyfield?>&myPg=<?=$pgNav->prevPG?>"><img src="<?=IMG_URL?>site/icon/pge_pre.gif" border="0" align="absmiddle"></A>
<?
}

$pgNav->prtPage("?univID=$UID&key=$key&keyfield=$keyfield&myPg", "[", "]");

if($pgNav->isNext() ) {
?>
<A HREF="<?=$_SERVER["PHP_SELF"]?>?univID=<?=$UID?>&key=<?=$key?>&keyfield=<?=$keyfield?>&myPg=<?=$pgNav->nextPG?>"><img src="<?=IMG_URL?>site/icon/pge_next.gif" border="0" align="absmiddle"></A>
<?
}

/*if($pgNav->isNextBLK() ) {
?>
<A HREF="<?=$_SERVER["PHP_SELF"]?>?myPg=<?=$pn->nextBLKptr?>">[Next Block]</A>
<?
}*/
?>

	</TD>
</TR>
<?
} // "if($total < 1)"  End
?>
<tr>
	<td colspan="7">&nbsp;</td>
</tr>
<TR>
<?
//
// ��ü �������� ���� GET������� �߰��� �Ѵ�.
// ���� �б��� ����Ʈ���� �л����� �������� �����⶧���� �ٽ� �б��� ����Ʈ�� �̵��ϱ� ���� '�������' ��ũ�� ��������
//
if($_SESSION['Level'] == 'A'){
?>
	<TD COLSPAN="7" ALIGN="RIGHT"><A HREF="<?=URL_ROOT?>univAdmin"><img src="<?=IMG_URL?>site/icon/list.gif"></A> <A HREF="<?=URL_ROOT?>eachUniv/stReg.php?univID=<?=trim($UID)?>"><img src="<?=IMG_URL?>site/icon/add_user.gif"></A></TD>
<?
}else{
?>
	<TD COLSPAN="7" ALIGN="RIGHT"><A HREF="<?=URL_ROOT?>eachUniv/stReg.php"><img src="<?=IMG_URL?>site/icon/add_user.gif"></A></TD>
<?
}
?>
</TR>
</TABLE>
<!-- SEARCH Part Start -->
<FORM NAME="search" METHOD="GET" ACTION="<?=$_SERVER['PHP_SELF']?>">
<table border="0" cellpadding="0" cellspacing="0" width="590">
<tr>
	<td align="center">
	<select name="keyfield">
        	<OPTION VALUE="" CHECKED>����</OPTION>
        	<OPTION VALUE="UNIVSNO">�й�</OPTION>
        	<OPTION VALUE="STUDNAME">�̸�</OPTION>
        	<OPTION VALUE="STUDDEPT">�а�</OPTION>
        	<OPTION VALUE="STUDGRADE">�г�</OPTION>
	</SELECT>
<INPUT TYPE="TEXT" NAME="key" SIZE=20>
<INPUT TYPE="SUBMIT" VALUE="�˻�">
	</td>
</tr>
</table>
</form>
<!-- SEARCH Part End -->

<?
require_once (MY_INCLUDE . "closing.php");
?>
