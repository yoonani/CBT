<?
/* =============================================================================
File : include/MUInfo.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 06
================================================================================
Desc.
	�α��� �� ������ ���� ������������ �̵��� �� �� ������ ���� �����κ�
	$levelInfo �迭�� ����(univ, position, menu) �� ������ ����
	$levelInfo['univ'] : �ش� ������ ���� �ִ� �б�
	$levelInfo['position'] : �ش� ������ String
	$levelInfo['menu'] : �ش� ������ ���� �޴� �迭
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
						"position" => "��ü������",
						"menu" => array(
								"<a href=\"" . URL_ROOT. "home/index.php\">Home</a>",
								"<a href=\"" . URL_ROOT. "exam/examList.php". "#\">������</a>",
								"<a href=\"" . URL_ROOT. "univAdmin\">�б��� ����</a>",
								"<a href=\"" . URL_ROOT . "viewResult/index.php" . "\">�������</a>"
								)
					);
                        break;
                case "F" :
                        $query = "select u.mytitle from staffinfo s join univinfo u on s.myscode = u.mycode where s.myid = '".trim($_SESSION['ID'])."'";
                        if(!$DBI->query($query)) {
                                echo $DBI->error();
                                $fnc->alertBack("����� ������ �ҷ��� �� �����ϴ�.\\n����� �ٽ� �������ּ���");
                        }
                        $myUniv = $DBI->getResult(0,0);
			$levelInfo = 	array(	"univ" => trim($myUniv),
						"position" => "�б��� ������",
						"menu" => array(
								"<a href=\"" . URL_ROOT. "home/index.php\">Home</a>",
								"<a href=\"".URL_ROOT."eachUniv/\">�л� ����</a>",
								"<a href=\"" . URL_ROOT . "exam/examList.php" . "\">���躰 �����û</a>",
								"<a href=\"" . URL_ROOT . "viewResult/index.php" . "\">�������</a>"
								)
					);
                        break;
                case "D" :
                        $query = "select u.mytitle from staffinfo s join univinfo u on s.myscode = u.mycode where s.myid = '".trim($_SESSION['ID'])."'";
                        if(!$DBI->query($query)) {
                                echo $DBI->error();
                                $fnc->alertBack("����� ������ �ҷ��� �� �����ϴ�.\\n����� �ٽ� �������ּ���");
                        }
                        $myUniv = $DBI->getResult(0,0);
			$levelInfo = 	array(	"univ" => trim($myUniv),
						"position" => "�����Է���",
						"menu" => array(
								"<a href=\"" . URL_ROOT. "home/index.php\">Home</a>",
								"<a href=\"#\">���蹮�� ����</a>"
								)
					);
                        break;
                default :
                        $query = "select u.mytitle from userinfo ui join univinfo u on ui.univid = u.mycode where ui.myid = '".trim($_SESSION['ID'])."'";
                        if(!$DBI->query($query)) {
                                echo $DBI->error();
                                $fnc->alertBack("����� ������ �ҷ��� �� �����ϴ�.\\n����� �ٽ� �������ּ���");
                        }
                        $myUniv = $DBI->getResult(0,0);
			$levelInfo = 	array(	"univ" => trim($myUniv),
						"position" => "�л�",
						"menu" => array(
								"<a href=\"" . URL_ROOT. "home/index.php\">Home</a>",
								"<a href=\"" . URL_ROOT . "exam/examList.php" . "\">������</a>",
								"<a href=\"" . URL_ROOT . "viewResult/index.php" . "\">����Ȯ��</a>"
								)
					);
                        break;
        }
}else{
	$fnc->alertBack("������ �����ϴ�");
}
unset($DBI);
?>
