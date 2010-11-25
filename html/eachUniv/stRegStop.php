<?
/* =============================================================================
File : eachUniv/stRegStop.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7.10 
================================================================================
Desc.
	eachUniv/index.php에서 GET방식으로 uid : 학교코드, sno : 학번을 넘겨받아
	졸업 등의 사유로 인하여 해당 학생을 정지 시키는 페이지
	
============================================================================= */
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "frmValid.php");

//
// 직접 접근 제어
//
if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        $fnc->alertBack("접근할 수 없습니다.");
        exit;
}

if(!$fnc->isPOST()){
        $fnc->alertBack("접근할 수 없습니다.");
        exit;
}
//
// 권한 제어 $_SESSION['Level'] 이 'A' or 'F' 만 접근할 수 있다
//
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "F")) ) {
        $fnc->alertBack("접근할 수 없는 권한입니다.");
        exit;
}

//
// eachUniv/index.php에서 넘어온 값 확인
//
if($fv->lengthlt(trim($_POST["univID"]), 1)) {
        $fnc->alertBack("학교정보를 받아올 수 없습니다");
        exit;
}
if($fv->lengthlt(trim($_POST["univSNO"]), 1)) {
        $fnc->alertBack("학번정보를 받아올 수 없습니다");
        exit;
}

//
// 사용자 정지 쿼리
//
$sql = "update UnivStudent set myLevel = 'S' where univID = '".$_POST['univID']."' and univSNO = '".$_POST['univSNO']."'";
if(!$DB->query($sql)){
        $fnc->alertBack("학번 : ".$_POST['univSNO']." 학생을 정지 시킬 수 없습니다.");
        exit;
}else{
	$fnc->alert("학번 : ".$_POST['univSNO']." 학생의 권한을 모두 중지 시켰습니다.");
?>
	<SCRIPT LANGUAGE="JavaScript">
		location.href = '<?=URL_ROOT?>eachUniv/index.php?univID=<?=trim($_POST['univID'])?>';
	</SCRIPT>
<?
}

ob_end_flush();
?>
