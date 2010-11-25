<?
/* =============================================================================
File : include/MUInfo.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 06
================================================================================
Desc.
	로그인 후 레벨에 따라 메인페이지로 이동될 때 각 레벨에 따른 설정부분
	$levelInfo 배열의 원소(univ, position, menu) 에 설정값 지정
	$levelInfo['univ'] : 해당 레벨이 속해 있는 학교
	$levelInfo['position'] : 해당 레벨의 String
	$levelInfo['menu'] : 해당 레벨의 메인 메뉴 배열
============================================================================= */
if($noDBI > 1) {
	$DBI = $DB[0];
} else {
	$DBI = $DB;
}

if( $fnc->checkLevel($_SESSION["Level"], array("A", "B", "D", "F")) ){
        switch($_SESSION["Level"]) {
                case "A" :
			$levelInfo = 	array(	"univ" => "&nbsp",
						"position" => "전체관리자",
						"menu" => array(
								"<a href=\"" . URL_ROOT. "home/index.php\">Home</a>",
								"<a href=\"" . URL_ROOT. "exam/examList.php". "#\">시험목록</a>",
								"<a href=\"" . URL_ROOT. "univAdmin\">학교별 관리</a>",
								"<a href=\"" . URL_ROOT . "viewResult/index.php" . "\">결과보기</a>"
								)
					);
                        break;
                case "F" :
                        $query = "select u.mytitle from staffinfo s join univinfo u on s.myscode = u.mycode where s.myid = '".trim($_SESSION['ID'])."'";
                        if(!$DBI->query($query)) {
                                echo $DBI->error();
                                $fnc->alertBack("사용자 정보를 불러올 수 없습니다.\\n잠시후 다시 접속해주세요");
                        }
                        $myUniv = $DBI->getResult(0,0);
			$levelInfo = 	array(	"univ" => trim($myUniv),
						"position" => "학교별 관리자",
						"menu" => array(
								"<a href=\"" . URL_ROOT. "home/index.php\">Home</a>",
								"<a href=\"".URL_ROOT."eachUniv/\">학생 관리</a>",
								"<a href=\"" . URL_ROOT . "exam/examList.php" . "\">시험별 시험신청</a>",
								"<a href=\"" . URL_ROOT . "viewResult/index.php" . "\">결과보기</a>"
								)
					);
                        break;
                case "D" :
                        $query = "select u.mytitle from staffinfo s join univinfo u on s.myscode = u.mycode where s.myid = '".trim($_SESSION['ID'])."'";
                        if(!$DBI->query($query)) {
                                echo $DBI->error();
                                $fnc->alertBack("사용자 정보를 불러올 수 없습니다.\\n잠시후 다시 접속해주세요");
                        }
                        $myUniv = $DBI->getResult(0,0);
			$levelInfo = 	array(	"univ" => trim($myUniv),
						"position" => "문항입력자",
						"menu" => array(
								"<a href=\"" . URL_ROOT. "home/index.php\">Home</a>",
								"<a href=\"#\">시험문제 출제</a>"
								)
					);
                        break;
                default :
                        $query = "select u.mytitle from userinfo ui join univinfo u on ui.univid = u.mycode where ui.myid = '".trim($_SESSION['ID'])."'";
                        if(!$DBI->query($query)) {
                                echo $DBI->error();
                                $fnc->alertBack("사용자 정보를 불러올 수 없습니다.\\n잠시후 다시 접속해주세요");
                        }
                        $myUniv = $DBI->getResult(0,0);
			$levelInfo = 	array(	"univ" => trim($myUniv),
						"position" => "학생",
						"menu" => array(
								"<a href=\"" . URL_ROOT. "home/index.php\">Home</a>",
								"<a href=\"" . URL_ROOT . "exam/examList.php" . "\">시험목록</a>",
								"<a href=\"" . URL_ROOT . "viewResult/index.php" . "\">성적확인</a>"
								)
					);
                        break;
        }
}else{
	$fnc->alertBack("권한이 없습니다");
}
unset($DBI);
?>
