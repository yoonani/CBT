<?
/* =============================================================================
File : home/conf.php
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
require_once("../../include/conf.php");
if( $fnc->checkLevel($_SESSION["Level"], array("A", "B", "D", "F")) ){
        switch($_SESSION["Level"]) {
                case "A" :
			$levelInfo = 	array(	"univ" => "&nbsp",
						"position" => "��ü������",
						"menu" => array(
								"<a href=\"#\">��ü ��������</a>",
								"<a href=\"#\">���蹮�� ����</a>",
								"<a href=\"#\">�б��� ����</a>",
								"<a href=\"#\">��������</a>",
								"<a href=\"#\">Q&A</a>"
								)
					);
                        break;
                case "F" :
                        $query = "select u.mytitle from staffinfo s join univinfo u on s.myscode = u.mycode where s.myid = '".trim($_SESSION['ID'])."'";
                        if(!$DB->query($query)) {
                                echo $DB->error();
                                $fnc->alertBack("����� ������ �ҷ��� �� �����ϴ�.\\n����� �ٽ� �������ּ���");
                        }
                        $myUniv = $DB->getResult(0,0);
			$levelInfo = 	array(	"univ" => trim($myUniv),
						"position" => "�б��� ������",
						"menu" => array(
								"<a href=\"".URL_ROOT."eachUniv/\">�л� ����</a>",
								"<a href=\"#\">���躰 �����û</a>",
								"<a href=\"#\">��������</a>",
								"<a href=\"#\">Q&A</a>"
								)
					);
                        break;
                case "D" :
                        $query = "select u.mytitle from staffinfo s join univinfo u on s.myscode = u.mycode where s.myid = '".trim($_SESSION['ID'])."'";
                        if(!$DB->query($query)) {
                                echo $DB->error();
                                $fnc->alertBack("����� ������ �ҷ��� �� �����ϴ�.\\n����� �ٽ� �������ּ���");
                        }
                        $myUniv = $DB->getResult(0,0);
			$levelInfo = 	array(	"univ" => trim($myUniv),
						"position" => "�����Է���",
						"menu" => array(
								"<a href=\"#\">���蹮�� ����</a>"
								)
					);
                        break;
                default :
                        $query = "select u.mytitle from userinfo ui join univinfo u on ui.univid = u.mycode where ui.myid = '".trim($_SESSION['ID'])."'";
                        if(!$DB->query($query)) {
                                echo $DB->error();
                                $fnc->alertBack("����� ������ �ҷ��� �� �����ϴ�.\\n����� �ٽ� �������ּ���");
                        }
                        $myUniv = $DB->getResult(0,0);
			$levelInfo = 	array(	"univ" => trim($myUniv),
						"position" => "�л�",
						"menu" => array(
								"<a href=\"#\">��������</a>",
								"<a href=\"#\">����Ȯ��</a>",
								"<a href=\"#\">��������</a>",
								"<a href=\"#\">Q&A</a>"
								)
					);
                        break;
        }
}else{
        echo "Invalid!";
}
?>
