<?
/* =============================================================================
File : mailClass.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2005. 7. 27
================================================================================
Desc : 
	PHP 5이상에서 사용 가능한 Mail Class(Professinal PHP Programming 참조)
	class baseMail : mail을 위한 기반 class
============================================================================= */

class baseMail {
	var	$to;
	var	$from;
	var	$reply_to;
	var	$cc;
	var	$bcc;
	var	$subject;
	var	$body;
	var	$validate_email;
	var	$rigorous_emailCheck;
	var	$allow_empty_subject;
	var	$allow_empty_body;
	var	$headers;
	var	$extraHeader;
	var	$ERROR_MSG;
	var 	$ERROR_MSG_ARR;
	
	function __construct($validate_email=true, $rigorous_emailCheck=false, $allow_empty_subject=false, $allow_empty_body=false) {
		$this->validate_email = $validate_email;
		$this->rigorous_emailCheck = $rigorous_emailCheck;
		$this->allow_empty_subject = $allow_empty_subject;
		$this->allow_empty_body = $allow_empty_body;
		$this->ERROR_MSG_ARR = array(
		"0" => "수신인 항목이 비어있습니다.",
		"1" => "메일 제목을 써 주세요",
		"2" => "메일 내용을 써 주세요",
		"3" => "메일 발송중에 에러가 발생했습니다.",
		"4" => "받는 사람의 메일 주소가 잘못되었습니다.",
		"5" => "참조인의 메일 주소가 잘못되었습니다.",
		"6" => "비밀 참조인의 메일주소가 잘못되었습니다.",
		"7" => "발생한 오류가 없습니다."
		);
	} 

	function setTo($to) {
		$this->to = $to;
	}

	function setFrom($from) {
		$this->from = $from;
	}

	function setCc($cc) {
		$this->cc = $cc;
	}

	function setBcc($bcc) {
		$this->bcc = $bcc;
	}

	function setSubject($subject) {
		$this->subject = $subject;
	}

	function setBody($body) {
		$this->body = $body;
	}


	function checkFields() {
		if(empty($this->to)) {
			$this->ERROR_MSG = $this->ERROR_MSG_ARR[0];
			return false;
		} 

		if(!$this->allow_empty_subject && empty($this->subject)) {
			$this->ERROR_MSG = $this->ERROR_MSG_ARR[1];
			return false;
		}

		
		if(!$this->allow_empty_body && empty($this->body)) {
			$this->ERROR_MSG = $this->ERROR_MSG_ARR[2];
			return false;
		}

		if($this->validate_email) {
			$to_emails = explode(",", $this->to);
			if(!empty($this->cc)) {
				$cc_emails = explode(",", $this->cc);
			}
			if(!empty($this->bcc)) {
				$bcc_emails = explode(",", $this->bcc);
			}
			
			if($this->rigorous_emailCheck) {
				if($this->rigorousEmailCheck($to_emails)) {
					$this->ERROR_MSG = $this->ERROR_MSG_ARR[4];
					return false;
				} else if(is_array($cc_emails) && !$this->rigorousEmailCheck($cc_emails)) {
					$this->ERROR_MSG = $this->ERROR_MSG_ARR[5];
					return false;
				} else if(is_array($bcc_emails) && !$this->rigorousEmailCheck($bcc_emails)) {
					$this->ERROR_MSG = $this->ERROR_MSG_ARR[6];
					return false;
				}
			} else {
				if(!$this->emailCheck($to_emails)) {
					$this->ERROR_MSG = $this->ERROR_MSG_ARR[4];
					return false;
				} else if(is_array($cc_emails) && !$this->emailCheck($cc_emails)) {
					$this->ERROR_MSG = $this->ERROR_MSG_ARR[5];
					return false;
				} else if(is_array($bcc_emails) && !$this->emailCheck($bcc_emails)) {
					$this->ERROR_MSG = $this->ERROR_MSG_ARR[6];
					return false;
				}
			}

		}

		return true;
	}

	function emailCheck($emails) {
		foreach($emails as $email) {
			if(eregi("<(.+)>", $email, $match)) {
				$email = $match[1];
			}
			$emPat = "^[_\-\.0-9a-zA-Z]+@([0-9a-z][_0-9a-zA-Z\.]+)\.([a-z]{2,4}$)";
			if(!eregi($emPat, $email)) {
				return false;
			}
		}
		return true;
	}

	function rigorousEmailChck($emails) {
		if(!$this->emailCheck($emails)) return false;

		foreach($emails as $email) {
			list($user, $domain) = split("@", $email, 2);
			if(!checkdnserr($domain, "ANY")) return false;
		}
		return true;
	}

	function buildHeaders() {
		$this->extraHeader = "";
		if(!empty($this->from)) {
			$this->extraHeader .= "From: " . $this->from . "\r\n";
		}
		if(!empty($this->cc)) {
			$this->extraHeader .= "Cc: " . $this->cc . "\r\n";
		}
		if(!empty($this->bcc)) {
			$this->extraHeader .= "Bcc: " . $this->bcc . "\r\n";
		}
	}

	function viewMsg() {
		if(!$this->checkFields()) 
			return false;
		
		$this->headers = array();
		$this->buildHeaders();

		$this->headers[] = "From: " . $this->from;
		$this->headers[] = "To: " . $this->to;
		$this->headers[] = "Subject: " . $this->subject;

		$msg = implode("\r\n", $this->headers);
		$msg .= "\r\n\r\n";
		$msg .= $this->body;

		return $msg;
	}

	function send() {
		if(!$this->checkFields()) return true;
		$this->headers = array();
		$this->buildHeaders();

//		if(mail($this->to, stripslashes(trim($this->subject)), stripslashes($this->body), "From: kba@scv.hallym.ac.kr\\r\\n")) {
		if(mail($this->to, stripslashes(trim($this->subject)), stripslashes($this->body), $this->extraHeader)) {
			return true;
		} else {
			$this->ERROR_MSG = $this->ERROR_MSG_ARR[3];
			return false;
		}
	}

	function errorMsg() {
		if(empty($this->ERROR_MSG))
			return $this->ERROR_MSG_ARR[7];
		return $this->ERROR_MSG;
	}
}
?>
