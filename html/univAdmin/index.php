<?
/* =============================================================================
File : univAdmin/index.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 06
================================================================================
Desc.
	학교 목록 페이지
	전체 관리자(A) 접근 가능
	학교 추가, 수정
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
//
// 권한 제어 $_SESSION['Level'] 이 'A' or 'F' 만 접근할 수 있다
//
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
        $fnc->alertBack("접근할 수 없는 권한입니다.");
        exit;
}

require_once (MY_INCLUDE . "header.php");

//
// 검색어 변수 GET방식으로 전달 받음
//
$key = $_GET['key'];
$keyfield = $_GET['keyfield'];

//
// 검색변수의 유무에 따라 학생의 총 인원수 추출 쿼리 -------> 1번
//
if( strlen(trim($key)) > 0 && strlen(trim($keyfield)) > 0 ){
	$sql = "select u.mytitle from univinfo u left join staffinfo s on u.myCode = s.myScode where $keyfield like '%$key%' and (s.myLevel = 'F' or s.myLevel is null)";
	
}else{
	$sql = "select u.mytitle from univinfo u left join staffinfo s on u.myCode = s.myScode where (s.myLevel = 'F'  or s.myLevel is null)";
}

//
// 상단의 1번 쿼리 DB실행
//
if(!$DB->query($sql)) {
        echo $fnc->alertBack("학교정보를 불러올 수 없습니다. 잠시 후 다시 시도 하세요");
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
	$sql = "select u.myCode, u.mytitle, s.myName ,s.myID, s.myEmail from univinfo u left join staffinfo s on u.myCode = s.myScode where (s.myLevel = 'F' or s.myLevel is null) and $keyfield like '%$key%' order by u.mytitle ASC limit $pgSize offset $pgStart";
}else{
	$sql = "select u.myCode, u.mytitle, s.myName ,s.myID, s.myEmail from univinfo u left join staffinfo s on u.myCode = s.myScode where (s.myLevel = 'F' or s.myLevel is null) order by u.mytitle ASC limit $pgSize offset $pgStart";
}

//
// 상단의 2번 쿼리 DB 실행
//
if(!$DB->query($sql)) {
        echo $fnc->alertBack("학교정보를 불러올 수 없습니다. 잠시 후 다시 시도 하세요");
        exit;
}

?>
<SCRIPT LANGUAGE="JavaScript">
function uModi(string) {
	if(confirm("해당 학교의 주요 정보를 수정합니다. 정말 수정하시겠습까?")) {
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
// 검색 유무에 따른 화면 출력
//
if( strlen(trim($key)) > 0 && strlen(trim($keyfield)) > 0 ){
?>
	<TD COLSPAN="6" ALIGN="RIGHT">검색된 학교 <?=$total?>개</TD>
<?
}else{
?>
	<TD COLSPAN="6" ALIGN="RIGHT">총 <?=$total?>개</TD>
<?
}
?>
</TR>
<TR height="20">
	<TD width="60" class="title" align="center">학교코드</TD>
	<TD width="120" class="title" align="center">학교명</TD>
	<TD width="100" class="title" align="center">관리자 이름</TD>
	<TD width="100" class="title" align="center">관리자 ID</TD>
	<TD width="160" class="title" align="center">관리자 E-mail</TD>
	<TD width="50" class="title" align="center">수정</TD>
</TR>
<?
//
// 총 학교가 0명일 경우 화면 출력
//
if($total < 1){
?>
<TR>
	<TD COLSPAN="6" ALIGN="CENTER" class="hbottom">학교가 없습니다</TD>
</TR>
<?
//
// 총 학교가 0명 이상일 경우 학교 목록 화면출력
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
	echo "<FONT COLOR='RED'>미등록</FONT>";
}
?>
	</TD>
	<TD class="ccenter" align="center">
<?
if(isset($row[3])){
	echo trim($row[3]);
}else{
	echo "<FONT COLOR='RED'>미등록</FONT>";
}
?>
	</TD>
	<TD class="ccenter" align="center">
<?
if(isset($row[4])){
	echo trim($row[4]);
}else{
	echo "<FONT COLOR='RED'>미등록</FONT>";
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
// page, Next Page, Pre Page 화면 출력
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
        	<OPTION VALUE="" CHECKED>선택</OPTION>
        	<OPTION VALUE="u.myTitle">학교명</OPTION>
        	<OPTION VALUE="s.myName">관리자 이름</OPTION>
        	<OPTION VALUE="s.myID">관리자 ID</OPTION>
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
