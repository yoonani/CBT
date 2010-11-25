<?
/* =============================================================================
File : pgNavigationClass.php
================================================================================
Date : 2004. 1. 5
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Desc :
	게시물의 Page Navigation을 위한 Class
	$recPerPG : 페이지당 게시물수
	$pgPerBLK : 블럭당 페이지수
	$initPtr : 초기 Page
	$myPage : 현재 Page
	$myBLK : 현재 BLK
	$myPage : 최대 Page
	$myBLK : 최대 BLK
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

		// 최대 Page 계산
		$this->maxPage = ceil($this->totalRows / $this->recPerPG);

		// 현재 Block 계산
		$this->myBLK = ceil($this->myPage / $this->pgPerBLK);

		// 최대 Block 계산
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
