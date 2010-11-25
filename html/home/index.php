<?php
/* =============================================================================
File : home/index.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Desc.
	Log In �� ó������ �̵��ϴ� Page
	Session Level : A ��ü������, B �л�, D �����Է���, F : �б��� ������
	���� Page�� ��ü ��� ���ٰ���
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
