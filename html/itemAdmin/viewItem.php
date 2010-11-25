<?php
/* =============================================================================
File : itemAdmin/viewItem.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	접근가능 : A
	문항세부 내용을 보여준다.
	itemIndex부터 오는 것은 개별문항.
	viewGrpItem으로부터 오는 것은 Group내 문항
	- $_GET["examID"] : 시험 ID
	- $_GET["myPg"] : itemIndex의 Page
	- $_GET["myGrpID"] : 문항 Group의 ID
	- $_GET["isIndep"] : 독립문항여부
	  > 독립문항(Y)이면 일반 문항처럼 보여준다.(현재 아예 일반문항 페이지로
            이동시킬 것인지 고민중
	  > 문항Group에 속한 경우(N)에는 하위 문항들과 문항추가 Button이 
	    생성된다.
	- $_GET["itemID"] : 문항 ID
============================================================================= */
$useItemInfo = "Y";
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "header.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
	exit;
}
$sql = "SELECT myTitle, myStart, myEnd FROM examAdmin WHERE myID = " . $_GET["examID"];
if(!$DB->query($sql)) {
        $fnc->alertBack("Query를 수행할 수 없습니다.");
        exit;
}
$result = $DB->fetch();
?>
<H3>문항 보기</H3>
<table border="0" width="590" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2" width="590">
			<img src="<?=IMG_URL?>site/examadmin/title_2.gif" border="0">
		</td>
	</tr>
	<tr>
        	<td width"" class="htitle" align="center">시험명</td>
		<td width="" class="htop"><?=$result[0]?></td>
	</tr>
	<tr>
		<td class="htitle" align="center">시작시간</td>
		<td class="hmiddle"><?=$result[1]?></td>
	</tr>
	<tr>
        	<td class="htitle" align="center">종료시간</td>
		<td class="hbottom"><?=$result[2]?></td>
	</tr>
</table>
<br>
<!-- Group문항 정보보기 //-->
<?
if($_GET["isIndep"] == "N") {
?>
<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
<tr>
	<td><img src="<?=IMG_URL?>site/examadmin/title_18.gif">
</tr>
<TR>
<?
	$sql = "SELECT myText FROM ItemGroupTable WHERE myID = " . $_GET["myGrpID"];
	if(!$DB->query($sql)) {
		$fnc->alertBack("머릿 문항정보를 가져올 수 없습니다.");
       	 	exit;
	}
	$result = $DB->fetch();
?>
	<TD COLSPAN="4" class="hbottom_bold"><?=trim($result[0])?></TD>
</TR>
</TABLE>
<br />
<?
}

// 해당 문항의 정보를 가져온다.
// 독립 문항의 경우 문제는 ItemGroupTable에 있으므로 Query를 두개로 나눈다.
if($_GET["isIndep"] == "Y") {
	$sql = "SELECT c.myText, a.myCorrect, a.myScore, a.isObject, d.myFormat, d.myCategory,  d.myLevel, d.mySubject, d.myMeSH, d.myCase, d.myType FROM (ItemTable as a JOIN ItemAInfoTable as d ON (a.myID = d.itemID)) , ItemGInfoTable as b, ItemGroupTable as c WHERE a.myID = b.itemID AND b.groupID = c.myID AND a.myID = " . $_GET["itemID"];
} else {
	$sql = "SELECT a.myTest, a.myCorrect, a.myScore, a.isObject, d.myFormat, d.myCategory,  d.myLevel, d.mySubject, d.myMeSH, d.myCase, d.myType FROM ItemTable as a JOIN ItemAInfoTable as d ON (a.myID = d.itemID) WHERE a.myID = " . $_GET["itemID"];
}
if(!$DB->query($sql)) {
//	echo $sql;
	$fnc->alertBack("문항 정보를 가져올 수 없습니다.");
        exit;
}
$result = $DB->fetch();
?>
<!-- 문항정보 보기 //-->
<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="4"><img src="<?=IMG_URL?>site/examadmin/title_4.gif"></td>
	</tr>
	<tr>
		<td WIDTH="70" HEIGHT="20" ALIGN="CENTER" CLASS="htitle">Subject</td>
		<td WIDTH="225" ALIGN="CENTER" CLASS="htop"><?=ucfirst($mySubject[trim($result[7])])?></td>
		<td WIDTH="70" ALIGN="CENTER" CLASS="htitle">Format</td>
		<td WIDTH="225" ALIGN="CENTER" CLASS="htop"><?=ucfirst($myFormat[trim($result[4])])?></td>
	</tr>
	<tr>
		<td HEIGHT="20" ALIGN="CENTER" CLASS="htitle">Category</td>
		<td ALIGN="CENTER" CLASS="hmiddle"><?=ucfirst($myCategory[trim($result[5])])?></td>
		<td ALIGN="CENTER" CLASS="htitle">Item Type</td>
		<td ALIGN="CENTER" CLASS="hmiddle"><?=ucfirst($myItemType[trim($result[6])])?></td>
	</tr>
	<tr>
		<td HEIGHT="20" ALIGN="CENTER" CLASS="htitle">MeSH</td>
		<td COLSPAN="3" CLASS="hbottom">&nbsp;<?=trim($result[8])?></td>
	</tr>
</TABLE>
<br>
<!-- 문항 보기 //-->
<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
	<tr>
		<td WIDTH="590" COLSPAN="2"><img src="<?=IMG_URL?>site/examadmin/title_19.gif"></td>
	</tr>
<?
if($result[3] == "Y") {
	// 외부파일에 의한 문항일때
	$sql = "SELECT myFile FROM ObjectTable WHERE itemID = " . $_GET["itemID"];
	if(!$DB->query($sql)) {
	//	echo $sql;
		$fnc->alertBack("문항 정보를 가져올 수 없습니다.");
       	 	exit;
	}
	$object = $DB->getResult(0, 0);
	// 외부 파일 문항보기
	// File을 가져와서 보여주어야 한다.
	// FLASH등을 위한 JavaScript
?>
	<TR>
		<TD WIDTH="590" class="pad15"><?=$object[0]?></TD>
	</TR>
	<tr>
		<td CLASS="hbottom_bold2">&nbsp;</td>
	</tr>
</TABLE>
<?
} else {
?>
	<TR>
		<TD WIDTH="590" class="pad15"><?=$result[0]?></TD>
	</TR>
	<tr>
		<td CLASS="hbottom_bold2">&nbsp;</td>
	</tr>
</TABLE>
<br />
<TABLE border="0" width="590" cellpadding="0" cellspacing="0" style="border-top:solid 1px #9EB1DB">
<?
	// FORM에 의해서 생성된 문항일때
	// 보기 정보를 가져온다.
	$sql = "SELECT myText, myImage FROM OptionTable WHERE itemID = " . $_GET["itemID"] . " ORDER BY myOrder ASC";
	if(!$DB->query($sql)) {
		$fnc->alertBack("보기 정보를 가져올 수 없습니다.");
       	 	exit;
	}
	$idx = 1;
	while($options = $DB->fetch()) {
		if($result[10] != "A") {
			$imgStr =  "<IMG SRC=\"" . IMG_URL . "testimages/" . $_GET["examID"] . "/" . $options[1] . "\">&nbsp;";
		}
		// FORM에 의해 생성된 문항보기
?>
		<TR>
			<TD WIDTH="90" ALIGN="CENTER" CLASS="htitle2">보기 <?=$idx?></TD>
			<TD WIDTH="500" class="hbottom2"><?=$imgStr?><?=$options[0]?></TD>
		</TR>
<?
		$idx++;
	}
}
?>
</TABLE>
<br />
<TABLE border="0" width="590" cellpadding="0" cellspacing="0" style="border-top:solid 1px #9EB1DB">
	<TR>
		<TD ALIGN="CENTER" WIDTH="45" HEIGHT="20" style="background:#9EB1DB;color:white;">정답</TD>
		<TD ALIGN="CENTER" WIDTH="250" class="hbottom2"><?=$result[1]?></TD>
		<TD ALIGN="CENTER" WIDTH="45" HEIGHT="20" style="background:#9EB1DB;color:white;">배점</TD>
		<TD ALIGN="CENTER" WIDTH="250" class="hbottom2"><?=$result[2]?>점</TD>
	</TR>
</TABLE>
	
<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
	<tr>
		<td align="right">[<A HREF="<?=URL_ROOT?>itemAdmin/itemIndex.php?examID=<?=$_GET["examID"]?>&myPg=<?=$_GET["myPg"]?>">앞으로</A>][<A HREF="<?=URL_ROOT?>/itemAdmin/modiItem.php?examID=<?=$_GET['examID']?>&myPg=<?=$_GET['myPg']?>&itemID=<?=$_GET['itemID']?>&myGrpID=<?=$_GET['myGrpID']?>&isIndep=<?=$_GET['isIndep']?>">수정</A>][삭제]</td>
	</tr>
</TABLE>
<?
// Group 문항의 경우 Group내의 문항들도 모두 보여준다.
if($_GET["isIndep"] == "N") {
?>
<br />
<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
<TR>
	<TD CLASS="htitle" ALIGN="CENTER" HEIGHT="20" WIDTH="40">No</TD>
	<TD CLASS="htitle" ALIGN="CENTER" WIDTH="350">문항</TD>
	<TD CLASS="htitle" ALIGN="CENTER" WIDTH="100">정답</TD>
	<TD CLASS="htitle" ALIGN="CENTER" WIDTH="100">배점</TD>
</TR>
<!-- 하위 문항들 //-->
<?
	unset($result);
	$sql = "SELECT a.myID, a.myTest, a.myCorrect, a.myScore, a.isObject FROM ItemTable as a JOIN ItemGInfoTable as b ON (a.myID = b.itemID) WHERE b.groupID = " . $_GET["myGrpID"] . " ORDER BY a.myID ASC";	
	if(!$DB->query($sql)) {
		$fnc->alertBack("문항 Group내의 문항정보를 가져올 수 없습니다.");
       	 	exit;
	}
	$idx = 1;
	while($result = $DB->fetch()) {
		if($result[4] == "Y") {
			$result[1] = "외부파일";
		}
?>
<TR>
	<TD ALIGN="CENTER" WIDTH="30" HEIGHT="20" CLASS="hleft">
<?
// 현재 문항이면 화살표 Icon 출력 아니면 $idx 값 표시
		if($result[0] == $_GET["itemID"]) {
			echo "<IMG SRC=\"" . IMG_URL . "site/icon/now.gif\" ALIGN=\"absmiddle\">";
		} else {
			echo $idx;
		}
?>
	</TD>
	<TD CLASS="hcenter">&nbsp;<A HREF="<?=URL_ROOT?>itemAdmin/viewItem.php?examID=<?=$_GET["examID"]?>&itemID=<?=$result[0]?>&isIndep=N&myGrpID=<?=$_GET["myGrpID"]?>&myPg=<?=$_GET["myPg"]?>"><?=substr(strip_tags(trim($result[1])), 0, 25)?></A></TD>
	<TD ALIGN="CENTER" CLASS="hcenter"><?=substr(strip_tags(trim($result[2])), 0, 10)?></TD>
	<TD ALIGN="CENTER" CLASS="hright"><?=$result[3]?></TD>
</TR>
<?
		$idx++;
	}
?>
</TABLE>
<?
}
require_once (MY_INCLUDE . "closing.php");
?>
