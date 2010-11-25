<?php
/* =============================================================================
File : ../include/confnoBuf.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 17
================================================================================
Desc : 전체 Page에서 Include 되는 기본 설정 File
	$useItemInfo == "Y" : 문항의 정보 배열을 읽어온다.
	$usePGNav == "Y" : Page Navigation Include
	$noDB == "Y" : DB 사용하지 않음
	$noSESS == "Y" : SESSION 사용하지 않음
============================================================================= */

define("MY_INCLUDE", "/usr/local/wbt/include/");

// Session Lifetime
// 60*60*2=7200(2시간)으로 함
define("SESS_LIFETIME", 7200);

if(!file_exists(MY_INCLUDE . "setting.php")) {
	echo "
	<SCRIPT LANGUAGE='JavaScript'>
		alert('설정파일을 찾을 수 없습니다.');
	</SCRIPT>	
	";
	ob_flush();
	exit;
} else {
	$conf = file(MY_INCLUDE . "setting.php");
}

/* setting.php
[1]TITLE : [기초의학평가-CBT]
[2]PAGE_ROOT : /work/webDoc/medcbt/
[3]URL_ROOT : http://galton.hallym.ac.kr/medcbt/
[4]DOC_ROOT : /work/webDoc/medcbt/html/
[5]INC_ROOT : /work/webDoc/medcbt/include/
[6]MAX_UPLOAD : 512(Byte단위)
[7]PHP_VERSION : 5
*/

// setting.php로부터 가져온 설정
define("TITLE", trim($conf[1]));
define("PAGE_ROOT", trim($conf[2]));
define("URL_ROOT", trim($conf[3]));
define("DOC_ROOT", trim($conf[4]));
define("INC_ROOT", trim($conf[5]));
define("MAX_UPLOAD", intval(trim($conf[6]))*1024);

// setting.php로부터 가져온 설정을 이용한 부가 설정
define("IMG_URL", URL_ROOT . "images/");
define("IMG_PATH", DOC_ROOT . "images/");
define("DATA_PATH", PAGE_ROOT . "data/");


// 시험 정보 파일 
define("EXAM_INFO_PATH", DATA_PATH . "exam/");

// $noDB = "Y" 인 경우를 제외하고
// 전 Page에 걸쳐 DB Instancae를 생성한다.
if($noDB != "Y") {
	if(isset($noDBI)) {
		$noDBI = $noDBI;
	} else {
		$noDBI = 1;
	}	

	define("USE_DB", "Y");

	// DB 정보를 dbSet.php로부터 가져온다.
	$dbInfo = file(MY_INCLUDE . "dbSet.php");
	$dbHost = trim($dbInfo[0]);
	$dbUser = trim($dbInfo[1]);
	$dbPwd = trim($dbInfo[2]);
	$dbName = trim($dbInfo[3]);
	
	$myPHPV = substr(phpversion(), 0, 1);

	switch($myPHPV) {
		case "4" :
			echo "2";
			require_once(MY_INCLUDE . "PostgreSQLclass.php");
			break;
		case "5" :
			require_once(MY_INCLUDE . "PostgreSQLclass5.php");
			break;
	}	

	if($noDBI > 1) {
		for($i=0; $i < $noDBI; $i++) {
			$DB[$i] = new PostgreSQL($dbHost, $dbUser, $dbPwd, $dbName);
			$DB[$i]->connect();
		}
	} else {
		$DB = new PostgreSQL($dbHost, $dbUser, $dbPwd, $dbName);
		$DB->connect();
	}

	if($noSESS != "Y") {
	        if(getenv("session.save_handler") != "user") {
			ini_set("session.save_handler", "user");
		}

		// 유효시간 설정. cookie와 session을 동일하게
		// session_set_cookie_params(SESS_LIFETIME);
		ini_set(session.gc_maxlifetime, SESS_LIFETIME);
		ini_set(session.gc_probability, 1);

		$sessDB = new PostgreSQL($dbHost, $dbUser, $dbPwd, $dbName);
		require_once(MY_INCLUDE . "session_manager.php");
	}
}

require_once(MY_INCLUDE . "useful.php");

// conf.php include 전에 $usePGNav = "Y";
// 상수 RECPERPG : 페이지당 게시물 수
// 상수 PGPERBLK : 블럭당 페이지 수
if($usePGNav == "Y") {
	require_once(MY_INCLUDE . "pgNavigationClass.php");
	$RECPERPG = 15;
	$PGPERBLK = 15;
}

if($useItemInfo == "Y") {
	require_once(MY_INCLUDE . "itemInfo.php");
}
?>
