<?php
/* =============================================================================
File : takeExam/endExam.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	접근가능 : A, B, F
	시험 종료
============================================================================= */
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "B", "F")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
	exit;
}
?>
<SCRIPT LANGUAGE="JavaScript">
	opener.location.href='<?=URL_ROOT?>exam/examList.php';
	window.close();
</SCRIPT>
<?
ob_end_flush();
?>
