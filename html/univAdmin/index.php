<?
/* =============================================================================
File : univAdmin/index.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 06
================================================================================
Desc.
	�б� ��� ������
	��ü ������(A) ���� ����
	�б� �߰�, ����
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
//
// ���� ���� $_SESSION['Level'] �� 'A' or 'F' �� ������ �� �ִ�
//
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
        $fnc->alertBack("������ �� ���� �����Դϴ�.");
        exit;
}

require_once (MY_INCLUDE . "header.php");

//
// �˻��� ���� GET������� ���� ����
//
$key = $_GET['key'];
$keyfield = $_GET['keyfield'];

//
// �˻������� ������ ���� �л��� �� �ο��� ���� ���� -------> 1��
//
if( strlen(trim($key)) > 0 && strlen(trim($keyfield)) > 0 ){
	$sql = "select u.mytitle from univinfo u left join staffinfo s on u.myCode = s.myScode where $keyfield like '%$key%' and (s.myLevel = 'F' or s.myLevel is null)";
	
}else{
	$sql = "select u.mytitle from univinfo u left join staffinfo s on u.myCode = s.myScode where (s.myLevel = 'F'  or s.myLevel is null)";
}

//
// ����� 1�� ���� DB����
//
if(!$DB->query($sql)) {
        echo $fnc->alertBack("�б������� �ҷ��� �� �����ϴ�. ��� �� �ٽ� �õ� �ϼ���");
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
	$sql = "select u.myCode, u.mytitle, s.myName ,s.myID, s.myEmail from univinfo u left join staffinfo s on u.myCode = s.myScode where (s.myLevel = 'F' or s.myLevel is null) and $keyfield like '%$key%' order by u.mytitle ASC limit $pgSize offset $pgStart";
}else{
	$sql = "select u.myCode, u.mytitle, s.myName ,s.myID, s.myEmail from univinfo u left join staffinfo s on u.myCode = s.myScode where (s.myLevel = 'F' or s.myLevel is null) order by u.mytitle ASC limit $pgSize offset $pgStart";
}

//
// ����� 2�� ���� DB ����
//
if(!$DB->query($sql)) {
        echo $fnc->alertBack("�б������� �ҷ��� �� �����ϴ�. ��� �� �ٽ� �õ� �ϼ���");
        exit;
}

?>
<SCRIPT LANGUAGE="JavaScript">
function uModi(string) {
	if(confirm("�ش� �б��� �ֿ� ������ �����մϴ�. ���� �����Ͻðڽ���?")) {
		document.univModi.univID.value = string;
		document.univModi.action = '<?=URL_ROOT?>univAdmin/univModi.php';
		document.univModi.submit();
	} else {
		return;
	}
}
</SCRIPT>
<FORM NAME="univModi" METHOD="post" ACTION="">
<INPUT TYPE="HIDDEN" NAME="univID" VALUE="">
</FORM>
<TABLE BORDER="0" width="590" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="6"><img src="<?=IMG_URL?>site/examadmin/title_14_3.gif"></td>
</tr>
<TR>
<?
//
// �˻� ������ ���� ȭ�� ���
//
if( strlen(trim($key)) > 0 && strlen(trim($keyfield)) > 0 ){
?>
	<TD COLSPAN="6" ALIGN="RIGHT">�˻��� �б� <?=$total?>��</TD>
<?
}else{
?>
	<TD COLSPAN="6" ALIGN="RIGHT">�� <?=$total?>��</TD>
<?
}
?>
</TR>
<TR height="20">
	<TD width="60" class="title" align="center">�б��ڵ�</TD>
	<TD width="120" class="title" align="center">�б���</TD>
	<TD width="100" class="title" align="center">������ �̸�</TD>
	<TD width="100" class="title" align="center">������ ID</TD>
	<TD width="160" class="title" align="center">������ E-mail</TD>
	<TD width="50" class="title" align="center">����</TD>
</TR>
<?
//
// �� �б��� 0���� ��� ȭ�� ���
//
if($total < 1){
?>
<TR>
	<TD COLSPAN="6" ALIGN="CENTER" class="hbottom">�б��� �����ϴ�</TD>
</TR>
<?
//
// �� �б��� 0�� �̻��� ��� �б� ��� ȭ�����
//
}else{

while($row = $DB->fetch()){
?>
<TR height="20">
	<TD class="cleft" align="center"><?=trim($row[0])?></TD>
	<TD class="cleft" align="center"><A HREF="<?=URL_ROOT?>eachUniv/index.php?univID=<?=trim($row[0])?>"><?=trim($row[1])?></A></TD>
	<TD class="ccenter" align="center">
<?
if(isset($row[2])){
	echo trim($row[2]);
}else{
	echo "<FONT COLOR='RED'>�̵��</FONT>";
}
?>
	</TD>
	<TD class="ccenter" align="center">
<?
if(isset($row[3])){
	echo trim($row[3]);
}else{
	echo "<FONT COLOR='RED'>�̵��</FONT>";
}
?>
	</TD>
	<TD class="ccenter" align="center">
<?
if(isset($row[4])){
	echo trim($row[4]);
}else{
	echo "<FONT COLOR='RED'>�̵��</FONT>";
}
?>
	</TD>
	<TD class="ccenter" align="center">
		<?$fnc->imgButton(20, 20, "javascript:uModi('".trim($row[0])."')", IMG_URL . "site/icon/mody_icon.gif");?>
<!--<A HREF="javascript:uModi('<?=trim($row[0])?>')"><img src="<?=IMG_URL?>site/icon/mody_icon.gif" border="0"></A>-->
	</TD>
</TR>
<?
}
?>
<TR>
	<TD COLSPAN="6" ALIGN="CENTER">
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
<?$fnc->imgButton(26, 25, "location.href='".$_SERVER["PHP_SELF"]."?univID=".$UID."&key=".$key."&keyfield=".$keyfield."&myPg=".$pgNav."->prevPG'", IMG_URL . "site/icon/pge_pre.gif");?>
<!--
<A HREF="<?=$_SERVER["PHP_SELF"]?>?uid=<?=$UID?>&key=<?=$key?>&keyfield=<?=$keyfield?>&myPg=<?=$pgNav->prevPG?>"><img src="<?=IMG_URL?>site/icon/pge_pre.gif" border="0" align="absmiddle"></A>
-->
<?
}

$pgNav->prtPage("?univID=$UID&key=$key&keyfield=$keyfield&myPg", "[", "]");

if($pgNav->isNext() ) {
?>
<?$fnc->imgButton(26, 25, "location.href='".$_SERVER["PHP_SELF"]."?univID=".$UID."&key=".$key."&keyfield=".$keyfield."&myPg=".$pgNav."->nextPG'", IMG_URL . "site/icon/pge_next.gif");?>
<!--
<A HREF="<?=$_SERVER["PHP_SELF"]?>?uid=<?=$UID?>&key=<?=$key?>&keyfield=<?=$keyfield?>&myPg=<?=$pgNav->nextPG?>"><img src="<?=IMG_URL?>site/icon/pge_next.gif" border="0" align="absmiddle"></A>
-->
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
<TR>
	<TD COLSPAN="6" ALIGN="RIGHT"><A HREF="<?=URL_ROOT?>univAdmin/univAdd.php"><IMG SRC="<?=IMG_URL?>site/icon/add_user.gif"></A></TD>
</TR>
</TABLE>
<!-- SEARCH Part Start -->
<FORM NAME="search" METHOD="GET" ACTION="<?=$_SERVER['PHP_SELF']?>">
<table border="0" cellpadding="0" cellspacing="0" width="590">
<tr>
	<td align="center">
	<select name="keyfield">
        	<OPTION VALUE="" CHECKED>����</OPTION>
        	<OPTION VALUE="u.myTitle">�б���</OPTION>
        	<OPTION VALUE="s.myName">������ �̸�</OPTION>
        	<OPTION VALUE="s.myID">������ ID</OPTION>
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
