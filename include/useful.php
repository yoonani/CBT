<?
/* =============================================================================
File : useful.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5.
================================================================================
Desc.
	자주 사용하는 Function 들을 class로 묶어 놓음

	Method =
	alert($str)
 	: 주어진 문자열을 Java Script의 Alert를 통해 보여준다.
	isPOST
	: POST방식에 의한 전달이면 true를 그렇지 않으면 false 반환	
	checkLevel($sessName, $lvlArr)
	: 주어진 세션명($sessName)에 값이 $lvlArr 배열안에 존재하면 true, 아니면
	  false 반환 
	useMD5()
	: md5로 암호화된 문자열을 반환하는 JavaScript 함수를 사용하기 위해
	  JavaScript 파일을 Include 한다.
	beginTrans(&$DB)
	: Transaction 시작, DB Instance를 참조정보로 보낸다. 반환값은 T/F
	rollbackTrans(&$DB)
	: Rollback, DB Instance를 참조정보로 보낸다. 반환값은 T/F
	commitTrans(&$DB) 
	: Commit, DB Instance를 참조정보로 보낸다. 반환값은 T/F
	psqlTime2UT($psqlTime) 
	: PostgreSQL에서 나온 Time($psqlTime)으로부터 Unix TimeStamp값으로 
	  반환한다.

	사용예 = 
============================================================================= */


class myFnc {
	function alert($str) {
		echo "<SCRIPT LANGUAGE=\"JavaScript\">";
		echo "	alert('" . $str . "');";
		echo "</SCRIPT>";
	}

	function alertBack($str) {
		echo "<SCRIPT LANGUAGE=\"JavaScript\">";
		echo "	alert('" . $str . "');";
		echo "	history.go(-1);";
		echo "</SCRIPT>";
	}

	function isPOST() {
		if($_SERVER["REQUEST_METHOD"] == "POST") 
			return true;
		else 
			return false;
	}

	function checkLevel($sessName, $lvlArr) {
		if(in_array($sessName, $lvlArr)) 
			return true;
		else
			return false;
	}

	function useMD5() {
		echo "<SCRIPT SRC=\"". URL_ROOT . "include/md5.js\"></SCRIPT>";
	}

	function beginTrans(&$DB) {
		$sql = "BEGIN";
		if(!$DB->query($sql)) {
			return false;
		} else {
			return true;
		}
	}

	function rollbackTrans(&$DB) {
		$sql = "ROLLBACK";
		if(!$DB->query($sql)) {
			return false;
		} else {
			return true;
		}
	}

	function commitTrans(&$DB) {
		$sql = "COMMIT";
		if(!$DB->query($sql)) {
			return false;
		} else {
			return true;
		}
	}
	
	function psqlTime2UT($psqlTime) {
		$tmp = explode(" ", $psqlTime);
		if(count($tmp) > 1) {
			$tmp2 = explode("-", $tmp[0]);
			$tmp3 = explode(":", $tmp[1]);
			return mktime($tmp3[0], $tmp3[1], $tmp3[2], $tmp2[1], $tmp2[2], $tmp2[0]); 
		} else {
			$tmp2 = explode("-", $tmp[0]);
			return mktime(0, 0, 0, $tmp2[1], $tmp2[2], $tmp2[0]); 
		}
	}

	function imgButton($width, $height, $OnClick, $imgPath) {
		echo "<button style=\"border:0;width:" . $width . ";height:" . $height . "\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"" . $OnClick. "\"><img src=\"" . $imgPath. "\"></button>";

	}
	
	function imgButtonNE($width, $height, $OnClick, $imgPath) {
		return "<button style=\"border:0;width:" . $width . ";height:" . $height . "\" onmouseover=\"this.style.cursor='pointer'\" onclick=\"" . $OnClick. "\"><img src=\"" . $imgPath. "\"></button>";

	}
	
	function alertDiffWin($winName, $msg, $close=true) {
		$str = "
	<SCRIPT LANGUAGE=\"JavaScript\">
		if(window.name != '" . $winName . "') {
			alert('" . $msg . "');
		";
		if($close) {
			$str .= "			window.close();\n";
		} else {
			$start .= "			history.go(-1);\n";
		}
		$str .= "		}\n";
		$str .= "	</SCRIPT>";	
		echo $str;
	}

	function getAVG($arr, $n=-1) {
		if($n == -1) {
			$n = count($arr);
		}
		$sum = 0;
		for($i=0; $i < count($arr); $i++) {
			$sum = $sum + $arr[$i];
		}
		return (@($sum / $n));
	}

	function getVAR($arr, $n=-1) {
		if($n == -1) {
			$n = count($arr);
		}
		$sum = 0;
		$xbar = $this->getAVG($arr);
		for($i=0; $i < count($arr); $i++) {
			$sum = $sum + pow(($arr[$i] - $xbar), 2);
		}
		return (@($sum / $n));
	}

	function getSD($arr, $n=-1) {
		if($n == -1) {
			$n = count($arr);
		}
		return sqrt($this->getVAR($arr, $n));
	}
}

$fnc = new myFnc();
?>
