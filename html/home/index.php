<?php
/* =============================================================================
File : home/index.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Desc.
	Log In 후 처음으로 이동하는 Page
	Session Level : A 전체관리자, B 학생, D 시험입력자, F : 학교별 관리자
	현재 Page는 전체 등급 접근가능
============================================================================= */
require_once ("../../include/conf.php");

require_once (MY_INCLUDE . "header.php");

switch($_SESSION["Level"]) {
	case "A" :
		include "./admin.php";
		break;
	case "B" :
		include "./student.php";
		break;
	case "D" :
		include "./alba.php";
		break;
	case "F" :
		include "./uadmin.php";
		break;
}

require_once (MY_INCLUDE . "closing.php");
?>
