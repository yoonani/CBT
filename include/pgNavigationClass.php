<?
/* =============================================================================
File : pgNavigationClass.php
================================================================================
Date : 2004. 1. 5
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Desc :
	�Խù��� Page Navigation�� ���� Class
	$recPerPG : �������� �Խù���
	$pgPerBLK : ���� ��������
	$initPtr : �ʱ� Page
	$myPage : ���� Page
	$myBLK : ���� BLK
	$myPage : �ִ� Page
	$myBLK : �ִ� BLK
============================================================================= */

class pgNav {
	var $recPerPG;
	var $pgPerBLK;
	var $totalRows;
	var $initPtr;
	var $myPage;
	var $myBLK;
	var $maxPage;
	var $maxBLK;
	var $prevPG;
	var $nextPG;
	var $prevBLK;
	var $nextBLK;
	var $prevBLKptr;
	var $nextBLKptr;

	function __construct($recPerPG, $pgPerBLK, $totalRows, $initPtr = 1) {
		$this->recPerPG = $recPerPG;
		$this->pgPerBLK = $pgPerBLK;
		$this->totalRows = $totalRows;
		$this->initPtr = $initPtr;
	}

	function initStart($getPage) {
		if(isset($getPage) || !empty($getPage)) {
			$this->myPage = $getPage;
		} else {
			$this->myPage = $this->initPtr;
		}

		// �ִ� Page ���
		$this->maxPage = ceil($this->totalRows / $this->recPerPG);

		// ���� Block ���
		$this->myBLK = ceil($this->myPage / $this->pgPerBLK);

		// �ִ� Block ���
		$this->maxBLK = ceil($this->totalRows / ($this->recPerPG * $this->pgPerBLK));
	}

	function isPrev() {
		if($this->myPage - 1 > 0) {
			$this->prevPG = $this->myPage - 1;
			return 1;
		} else {
			return 0;
		}
	}

	function isNext() {
		if($this->myPage < $this->maxPage) {
			$this->nextPG = $this->myPage + 1;
			return 1;
		} else {
			return 0;
		}
	}

	function isPrevBLK() {
		if($this->myBLK - 1 > 0) {
			$this->prevBLK = $this->myBLK - 1;
			$this->prevBLKptr = (($this->myBLK - 1) * $this->pgPerBLK + 1) - $this->pgPerBLK;
			return 1;
		} else {
			return 0;
		}	
	}

	function isNextBLK() {
		if($this->myBLK < $this->maxBLK) {
			$this->nextBLK = $this->myBLK + 1;
			$this->nextBLKptr = (($this->myBLK - 1) * $this->pgPerBLK + 1) + $this->pgPerBLK;
			return 1;
		} else {
			return 0;
		} 
	}


	function prtPage($linkVar, $open, $close, $pageCOLOR="BLUE") {
		$myStart = (($this->myBLK - 1) * $this->pgPerBLK) + 1;
		for($i=$myStart; $i < $myStart + $this->pgPerBLK; $i++) {
			if($i <= $this->maxPage) {
				if($i == $this->myPage) {
					$mystr = $open . "<FONT COLOR='" . $pageCOLOR . "'><B>" . $i . "</B></FONT>" . $close;
				} else {
					$mystr = $open . "<A HREF='" . $_SERVER["PHP_SELF"] . $linkVar . "=" . $i."'>" .  $i . "</A>" . $close;
				}
				echo $mystr;
			}
		}
	}	

	function prtPage2($linkVar, $open, $close, $otherCOLOR = "BLACK", $pageCOLOR="BLUE") {
		$myStart = (($this->myBLK - 1) * $this->pgPerBLK) + 1;
		for($i=$myStart; $i < $myStart + $this->pgPerBLK; $i++) {
			if($i <= $this->maxPage) {
				if($i == $this->myPage) {
					$mystr = $open . "<FONT COLOR='" . $pageCOLOR . "'><B>" . $i . "</B></FONT>" . $close;
				} else {
					$mystr = "<A HREF='#' OnClick='JavaScript:" . $linkVar . "=" . $i . "');'>" .  $open . "<FONT COLOR='" . $otherCOLOR . "'>" . $i . "</FONT></A>" . $close;
				}
				echo $mystr;
			}
		}
	}	
}
