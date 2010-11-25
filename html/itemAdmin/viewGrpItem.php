<?php
/* =============================================================================
File : itemAdmin/viewGrpItem.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	접근가능 : A
	문항 Group을 보여준다.
	itemIndex로 부터 다음의 변수를 받는다.
	- $_GET["examID"] : 시험 ID
	- $_GET["myPg"] : itemIndex의 Page
	- $_GET["myGrpID"] : 문항 Group의 ID
	- $_GET["isIndep"] : 독립문항여부
	  > 독립문항(Y)이면 일반 문항처럼 보여준다.(현재 아예 일반문항 페이지로
            이동시킬 것인지 고민중
	  > 문항Group에 속한 경우(N)에는 하위 문항들과 문항추가 Button이 
	    생성된다.
============================================================================= */
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
<!--
//
//정호가 css test 한거
//
-->

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
<br>
<?
// 해당 문항 Group의 문항을 가져온다.
$sql = "SELECT mytext FROM ItemGroupTable WHERE myID = " . $_GET["myGrpID"] ;
if(!$DB->query($sql)) {
//	echo $sql;
	$fnc->alertBack("Group의 문항 정보를 가져올 수 없습니다.");
        exit;
}
$itemGrpText = $DB->fetch();
?>
<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
	<tr>
		<td><?=$itemGrpText[0]?></td>
	</tr>
</TABLE>
<br>
<br>
<?
// 해당 문항 Group의 문항을 가져온다.
$sql = "SELECT it.myTest, myCorrect, myScore, isObject, it.myID FROM (ItemGroupTable as igt JOIN  ItemGInfoTable as iit ON(igt.myID = iit.groupID)) JOIN ItemTable as it ON (iit.itemID = it.myID) WHERE igt.myID = " . $_GET["myGrpID"]. " ORDER BY iit.groupOrder ";
if(!$DB->query($sql)) {
//	echo $sql;
	$fnc->alertBack("Group의 문항 정보를 가져올 수 없습니다.");
        exit;
}
?>
<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="4"><img src="<?=IMG_URL?>site/examadmin/title_6.gif" border="0"></td>
	</tr>
	<TR height="20">
		<TD class="title" align="center">문제</TD>
		<TD class="title" align="center">정답</TD>
		<TD class="title" align="center">배점</TD>
		<TD class="title" align="center">외부파일</TD>
	</TR>
<?
while($itemInfo = $DB->fetch() ) {
?>
	<TR>
		<TD class="cleft" style="padding-left:15px"><A HREF="<?=URL_ROOT?>itemAdmin/viewItem.php?examID=<?=$_GET["examID"]?>&itemID=<?=$itemInfo[4]?>&isIndep=<?=$_GET["isIndep"]?>&myGrpID=<?=$_GET["myGrpID"]?>&myPg=<?=$_GET["myPg"]?>"><?=substr(strip_tags($itemInfo[0]), 0, 15)?></A></TD>
		<TD class="ccenter" align="center"><?=$itemInfo[1]?></TD>
		<TD class="ccenter" align="center"><?=$itemInfo[2]?></TD>
		<TD class="cright" align="center"><?=$itemInfo[3]?></TD>
	</TR>
<?
}
?>
	<Tr>
		<td colspan="4">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" align="right">
			<A HREF="<?=URL_ROOT?>itemAdmin/addItem.php?examID=<?=$_GET["examID"]?>&myGrpID=<?=$_GET["myGrpID"]?>&isIndep=<?=$_GET["isIndep"]?>&myPg=<?=$_GET["myPg"]?>"><img src="<?=IMG_URL?>site/icon/add_group.gif" border="0"></A><A HREF="<?=URL_ROOT?>itemAdmin/itemIndex.php?examID=<?=$_GET["examID"]?>&myPg=<?=$_GET["myPg"]?>"><img src="<?=IMG_URL?>site/icon/ques_list.gif" border="0"></A>
		</td>
	</tr>
</TABLE>
<?
require_once (MY_INCLUDE . "closing.php");
?>
