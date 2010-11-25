<?
/*==============================================================================
File : univAdmin/uAdminAddProc.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7.11
================================================================================
Desc.
        학교별 관리자 추가 페이지
        전체 관리자가 학교별 관리에 학교를 선택하여 접근하면, 학교 관리자가
        등록되어 있지 않은 경우 전체 관리자가 학교 관리자를 등록시킬 수 있는
        페이지이다.
	uAdminAdd.php에서 학교별 관리자의 정보를 받아와 DB에 저장한다.
============================================================================= */
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "frmValid.php");


//
// 직접 접근 검사
//
if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        $fnc->alertBack("접근할 수 없습니다.");
        exit;
}
//
// 권한 제어 $_SESSION['Level'] 이 'A' or 'F' 만 접근할 수 있다
//
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
        $fnc->alertBack("접근할 수 없는 권한입니다.");
        exit;
}
//
// uAdminAdd.php에서 전달받은 값이 올바른지 검사
//
if($fv->lengthlt(trim($_POST["univID"]), 1)) {
        $fnc->alertBack("학교정보를 받아올 수 없습니다");
        exit;
}
if($fv->lengthlt(trim($_POST["id"]), 1)) {
        $fnc->alertBack("ID를 받아올 수 없습니다");
        exit;
}
if($fv->lengthlt(trim($_POST["name"]), 1)) {
        $fnc->alertBack("이름을 받아올 수 없습니다");
        exit;
}
if($fv->lengthlt(trim($_POST["passwd1"]), 1)) {
        $fnc->alertBack("PASSWORD 를 받아올 수 없습니다");
        exit;
}
if($fv->lengthlt(trim($_POST["passwd2"]), 1)) {
        $fnc->alertBack("PASSWORD 를 받아올 수 없습니다");
        exit;
}
if($fv->lengthlt(trim($_POST["email"]), 1)) {
        $fnc->alertBack("E-mail 을 받아올 수 없습니다");
        exit;
}
if($fv->lengthlt(trim($_POST["rno"]), 1)) {
        $fnc->alertBack("주민번호 앞자리를 받아올 수 없습니다");
        exit;
}
if(trim($_POST["passwd1"]) != trim($_POST["passwd2"])){
        $fnc->alertBack("비밀번호와 비밀번호확인이 틀립니다 다시 입력하여 주세요");
        exit;
}

//
// 등록된 ID 점검
//
$sql = "select myID from staffinfo where myScode = '".trim($_POST["univID"])."' and myID = '".trim($_POST["id"])."'";
if(!$DB->query($sql)){
        echo $DB->error();
        exit;
}
if($DB->noRows()){
        $fnc->alertBack(trim($_POST["id"])." 은 이미 등록되어 있습니다.");
        exit;
}

$sql = "insert into StaffInfo(myID, myPassword, myLevel, myScode, myName, myPosition, myRNO1, myEmail) values ('".trim($_POST["id"])."','".trim($_POST["passwd1"])."','F' ,'".trim($_POST["univID"])."','".trim($_POST["name"])."', '학교별 관리자' ,'".trim($_POST["rno"])."','".trim($_POST["email"])."')";
if(!$DB->query($sql)){
        echo $DB->error();
        exit;
}else{
?>
<SCRIPT LANGUAGE="JavaScript">
        alert("'<?=$_POST['name']?>'을 등록하였습니다.");
</SCRIPT>
<META HTTP-EQUIV='Refresh' CONTENT='0 ; URL="<?=URL_ROOT?>eachUniv/index.php?univID=<?=trim($_POST["univID"])?>"'>
<?
}
?>
