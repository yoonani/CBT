<?php
/* =============================================================================
File : takeExam/endExam.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	���ٰ��� : A, B, F
	���� ����
============================================================================= */
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "B", "F")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
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
