<?
/* =============================================================================
File : eachUni/index.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 06
================================================================================
Desc.
	학교별 학생 목록 페이지
	학교별 관리자(F), 전체 관리자(A) 접근 가능
	학교별 학생 추가, 수정, 삭제로 연결
============================================================================= */
$usePGNav = "Y";
require_once("../../include/conf.php");

//
// 직접 접근 제어
//
//if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        //$fnc->alert("접근할 수 없습니다.Here");
        //$fnc->alert(URL_ROOT);
	//$fnc->alert($_SERVER[HTTP_REFERER]);
	//exit;
//}

//
// 권한 제어 $_SESSION['Level'] 이 'A' or 'F' 만 접근할 수 있다
//
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "F")) ) {
        $fnc->alertBack("접근할 수 없는 권한입니다.");
        exit;
}

require_once (MY_INCLUDE . "header.php");

//
// 전체 관리자(A) 인 경우는 univID를 GET방식으로 전달받아 학교별 학생 목록을 보여준다
// 학교별 관리자(F) 인 경우 로그인 시 생성된 $_SESSION['UID']의 학교ID로 해당 학교의 학생 목록을 보여준다
//
if($_SESSION['Level'] == "A"){
	$UID = $_GET['univID'];
}else{
	$UID = $_SESSION['UID'];
}
?>
<?
//
// 전체 관리자일 경우만 학교 관리자의 정보가 보인다
//
if($_SESSION['Level'] == 'A'){
	$adminSql = "select u.myCode, u.mytitle, s.myName ,s.myID, s.myEmail from univinfo u left join staffinfo s on u.myCode = s.myScode where u.myCode ='".$UID."' and (s.myLevel = 'F' or s.myLevel is null)";
	if(!$DB->query($adminSql)){
		echo $DB->error();
		exit;
	}
//
// 학교 관리자가 지정이 안되었을 경우 '미등록' 출력, '수정' 이 사라지고 '추가' 가 생긴다
// 학교 관리자가 지정이 되었을 경우 '추가' 가 사라지고 '수정' 이 생긴다
//
	$myCode = $DB->getResult(0,0);
	$mytitle = $DB->getResult(0,1);
	$myName = $DB->getResult(0,2);
	$myID = $DB->getResult(0,3);
	$myEmail = $DB->getResult(0,4);
?>
<SCRIPT LANGUAGE="JavaScript">
function sModi(string, string2) {
        if(confirm("해당 학교 관리자의 주요 정보를 수정합니다. \n정말 수정하시겠습까?")) {
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
	<TD width="120" class="title" align="center">학교명</TD>
	<TD width="120" class="title" align="center">관리자명</TD>
	<TD width="120" class="title" align="center">관리자 ID</TD>
	<TD width="160" class="title" align="center">관리자 E-mail</TD>
	<TD width="70" class="title" align="center">수정</TD>
</TR>
<TR height="20">
	<TD class="cleft" align="center"><?=trim($mytitle)?></TD>
	<TD class="ccenter" align="center">
<?
if(isset($myName)){
	echo trim($myName);
}else{
	echo "<FONT COLOR='RED'>미등록</FONT>";
}
?>
	</TD>
	<TD class="ccenter" align="center">
<?
if(isset($myID)){
	echo trim($myID);
}else{
	echo "<FONT COLOR='RED'>미등록</FONT>";
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
	echo "<FONT COLOR='RED'>미등록</FONT>";
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
// 학교 관리자 정보 보기 끝
//



//
// 검색어 변수 GET방식으로 전달 받음
//
$key = $_GET['key'];
$keyfield = $_GET['keyfield'];

//
// 검색변수의 유무에 따라 학생의 총 인원수 추출 쿼리 -------> 1번
//
if( strlen(trim($key)) > 0 && strlen(trim($keyfield)) > 0 ){
//	$sql = "select univsno, studName, studdept, studgrade from univstudent where univid = '".$UID."' and $keyfield like '%$key%'";
	$sql = "select univSNO from UnivStudent where univid = '".$UID."' and $keyfield like '%$key%' and  myLevel != 'S'";
}else{
//	$sql = "select univsno, studName, studdept, studgrade from univstudent where univid = '".$UID."'";
	$sql = "select univSNO from UnivStudent where univid = '".$UID."' and myLevel != 'S'";
}

//
// 상단의 1번 쿼리 DB실행
//
if(!$DB->query($sql)) {
        echo $fnc->alertBack("학생정보를 불러올 수 없습니다. 잠시 후 다시 시도 하세요");
        exit;
}

//
// pgNavigation Instance 생성 및 페이지 변수 초기화
//
$total = $DB->noRows();
$pgSize = 10;
$blkSize = 5;
$pgNav = new pgNav($pgSize,$blkSize,$total,1);
$pgNav->initStart($_GET["myPg"]);
$pgStart = ($pgNav->myPage - 1) * $pgSize;

// 
// 검색 유무와 page에 따른 학생 목록 추출 쿼리 -------> 2번
//
if( strlen(trim($key)) > 0 && strlen(trim($keyfield)) > 0 ){
//	$sql = "select univsno, studName, studdept, studgrade, univID from univstudent where univid = '".$UID."' and $keyfield like '%$key%' order by studdept ASC, studgrade ASC, univsno ASC, studName ASC limit $pgSize offset $pgStart";

	$sql = "select us.univsno, us.studName, us.studdept, us.studgrade, us.univID, ui.myID from UnivStudent us left join UserInfo ui on us.univid = ui.univid and us.univSNO = ui.univSNO where us.univid = '".$UID."' and us.".$keyfield." like '%$key%' and us.myLevel != 'S' order by us.studdept ASC, us.studgrade ASC, us.univsno ASC, us.studName ASC limit $pgSize offset $pgStart";
}else{
//	$sql = "select univsno, studName, studdept, studgrade, univID from univstudent where univid = '".$UID."' order by studdept ASC, studgrade ASC, univsno ASC, studName ASC limit $pgSize offset $pgStart";
	$sql = "select us.univsno, us.studName, us.studdept, us.studgrade, us.univID, ui.myID from UnivStudent us left join UserInfo ui on us.univid = ui.univid and us.univSNO = ui.univSNO where us.univid = '".$UID."' and us.myLevel != 'S' order by us.studdept ASC, us.studgrade ASC, us.univsno ASC, us.studName ASC limit $pgSize offset $pgStart";
}

//
// 상단의 2번 쿼리 DB 실행
//
if(!$DB->query($sql)) {
        echo $fnc->alertBack("학생정보를 불러올 수 없습니다. 잠시 후 다시 시도 하세요");
        exit;
}

?>
<SCRIPT LANGUAGE="JavaScript">
function stStop(string,string2) {
	if(confirm("해당학생의 모든 권한이 삭제됩니다. 정말 삭제하시겠습까?")) {
		document.stRegStop.univID.value = string;
		document.stRegStop.univSNO.value = string2;
		document.stRegStop.action = '<?=URL_ROOT?>eachUniv/stRegStop.php';
		document.stRegStop.submit();
	} else {
		return;
	}
}
function stModi(string,string2) {
	if(confirm("해당학생의 주요 정보를 수정합니다. 정말 수정하시겠습까?")) {
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
// 검색 유무에 따른 화면 출력
//
if( strlen(trim($key)) > 0 && strlen(trim($keyfield)) > 0 ){
?>
	<TD COLSPAN="7" ALIGN="RIGHT">검색된 학생 <?=$total?>명</TD>
<?
}else{
?>
	<TD COLSPAN="7" ALIGN="RIGHT">총 <?=$total?>명</TD>
<?
}
?>
</TR>
<TR height="20">
	<TD width="100" class="title" align="center">학과</TD>
	<TD width="120" class="title" align="center">학번</TD>
	<TD width="80" class="title" align="center">이름</TD>
	<TD width="100" class="title" align="center">ID</TD>
	<TD width="90" class="title" align="center">학년</TD>
	<TD width="50" class="title" align="center">수정</TD>
	<TD width="50" class="title" align="center">정지</TD>
</TR>
<?
//
// 총 학생 인원수가 0명일 경우 화면 출력
//
if($total < 1){
?>
<TR>
	<TD COLSPAN="7" ALIGN="CENTER" class="cside" >학생이 없습니다</TD>
</TR>
<?
//
// 총 학생 인원수가 0명 이상일 경우 학생 목록 화면출력
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
	echo "<FONT COLOR='RED'>미등록</FONT>";
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
// page, Next Page, Pre Page 화면 출력
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
// 전체 관리자의 경우는 GET방식으로 추가를 한다.
// 또한 학교별 리스트에서 학생관리 페이지로 들어오기때문에 다시 학교별 리스트로 이동하기 위한 '목록으로' 링크가 보여진다
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
        	<OPTION VALUE="" CHECKED>선택</OPTION>
        	<OPTION VALUE="UNIVSNO">학번</OPTION>
        	<OPTION VALUE="STUDNAME">이름</OPTION>
        	<OPTION VALUE="STUDDEPT">학과</OPTION>
        	<OPTION VALUE="STUDGRADE">학년</OPTION>
	</SELECT>
<INPUT TYPE="TEXT" NAME="key" SIZE=20>
<INPUT TYPE="SUBMIT" VALUE="검색">
	</td>
</tr>
</table>
</form>
<!-- SEARCH Part End -->

<?
require_once (MY_INCLUDE . "closing.php");
?>
