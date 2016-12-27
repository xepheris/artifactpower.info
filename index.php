<?php

include('m/d.php');

function timeconversion() {
	global $info;

	$info['lu'] = time('now')-$info['lu'];
				
	if($info['lu'] < '60') {
		$info['lu'] = '<1 minute';
	}
	elseif($info['lu'] >= '60' && $info['lu'] < '3600') {
		if($info['lu'] < '90') {
			$info['lu'] = '' .round($info['lu']/60, 0). ' minute';
		}
		elseif($info['lu'] >= '90') {
			$info['lu'] = '' .round($info['lu']/60, 0). ' minutes';
		}
	}
	elseif($info['lu'] >= '3600' && $info['lu'] < '86400') {
		if($info['lu'] < '5400') {
			$info['lu'] = '' .round($info['lu']/3600, 0). ' hour';
		}
		elseif($info['lu'] >= '5400') {
			$info['lu'] = '' .round($info['lu']/3600, 0). ' hours';
		}
	}
	elseif($info['lu'] >= '86400' && $info['lu'] < '604800') {
		if( $info['lu'] < '129600') {
			$info['lu'] = '' .round($info['lu']/86400, 0). ' day';
		}
		elseif($info['lu'] >= '129600') {
			$info['lu'] = '' .round($info['lu']/86400, 0). ' days';
		}
	}
	elseif($info['lu'] >= '604800') {
		$info['lu'] = '> 1 week';
	}
}

echo '<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="reddit.com/u/mata-hari_dh" />
<meta name="description" content="artifact power ranking">
<meta name="publisher" content="reddit.com/u/mata-hari_dh" />
<meta name="keywords" lang="en" content="artifact power, world of warcraft, legion, wow, 2016, expansion, addon, tool, calc, ap, toplist" />
<meta name="robots" content="index, follow" />
<meta name="language" content="en" />
<meta name="publisher" content="reddit.com/u/mata-hari_dh" />
<meta name="distribution" content="global" />
<meta name="reply-to" content="xepheris.dh.tank@gmail.com" />
<meta name="revisit-after" content="7 days" />
<meta name="page-topic" content="artifact power tool" />
<meta name="copyright" content="MIT License reddit.com/u/mata-hari_dh" />
<link rel="apple-touch-icon" href="apple-touch-icon.png"/>
<link rel="apple-touch-icon-precomposed" href="apple-touch-icon-precomposed.png" />
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
<title>Artifact Power Ranking</title>
<link rel="stylesheet" href="c/s.css">
<script type="text/javascript" src="j/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
function queue(str) {
	document.getElementById("refresh"+str).style.display = "none";
	document.getElementById("load"+str).style.display = "block";
    $.get("m/q.php?id="+str, function(data) {
		$( "#upd"+str).html(data);
		document.getElementById("load"+str).style.display = "none";
	});	
}
</script>
<script type="text/javascript">
jQuery.fn.preventDoubleSubmission = function() {
  $(this).on("submit",function(e){
    var $form = $(this);

    if ($form.data("submitted") === true) {
      e.preventDefault();
    } else {
      $form.data("submitted", true);
    }
  });

  return false;
};
</script>
<script type="text/javascript">
function queuefirst() {
	$("#form").preventDoubleSubmission();
	document.getElementById("load").style.display = "block";
	var cg = $("#cg").val();
	var r = $("#r").val();
	var s = $("#s").val();
	
	$.ajax({
		type: "GET",
		dataType: "html",
		url: "m/q.php",
		data: { cg: cg,
			r: r,
			s: s
			},
		success: function(data) {
			$( "#queuefirst").html(data);
			document.getElementById("queuefirst").style.display = "block";
			document.getElementById("load").style.display = "none";
		}
	});
}
</script>
</head>
<body>
<div id="c">
<p class="head"><a href="http://artifactpower.info/" style="color: #C0B283 !important;">Artifact Power Ranking</a></p>';

$apsum = mysqli_fetch_array(mysqli_query($stream, "SELECT SUM(`ap`) AS `sum` FROM `ap`"));
$apsum = $apsum['sum'];

$usersum = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`c`) AS `sum` FROM `ap`"));
$usersum = $usersum['sum'];

$mythic10sum = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `sum` FROM `ap` WHERE `mp` >= '10' AND `ie` >= '830'"));
$mythic10sum = $mythic10sum['sum'];

$mythic15sum = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `sum` FROM `ap` WHERE `mp` >= '15' AND `ie` >= '860'"));
$mythic15sum = $mythic15sum['sum'];

echo '<p id="total" >Total AP collected: ' .number_format($apsum). ' || Current users: ' .number_format($usersum). '<br />
~' .(round($mythic10sum/$usersum*100, 3)). '% have Mythic+10 or higher cleared || ~' .(round($mythic15sum/$usersum*100, 3)). '% have Mythic+15 or higher cleared</p>';

include('m/b.php');

function insert_form() {
	echo '<div id="imp"><br />
	Choose character or guild (guild imports all members - be patient)<br />

	<p>
	<input type="text" id="cg" maxlength="20" selected placeholder="name case sensitive" />
	<select id="r">
	<option value="EU">EU</option>
	<option value="US">US</option>
	</select>
	<select id="s">
	<option selected disabled>select server</option>
	</select>
	<button type="submit" onclick="queuefirst();" style="text-align: center;">Retrieve</button>
	<center><img src="i/load.gif" id="load" alt="404" width="16px" style="display: none; padding-bot: 5px;" /></center>
	</p>
	<p id="queuefirst"></p>
	</div>';
}
	
if(!isset($_GET['s']) && !isset($_GET['r']) && !isset($_GET['c']) && !isset($_GET['cl'])) {
	
	insert_form();

	?>
	<script type="text/javascript">
		$(document).ready(function(){
			setInterval(function(){
				$.ajax({
					url: "m/t.php",
					dataType: "html",
					success: function(response) {
						$( "#total").html(response);
					}
				});
			}, 15000);
		});
	</script>
	<?php
		
	$tripletable = array('all', 'EU', 'US');
	
	foreach($tripletable as $table) {
		if($table == 'all') {
			$size = 'WEST';
			if(isset($_GET['sp'])) {
				$role = explode('-', $_GET['sp']);
				$class = $role['0'];
				$specc = $role['1'];
				$amount = '150';
				$data = mysqli_query($stream, "SELECT * FROM `ap` WHERE `sp` = '" .$specc. "' AND `cl` = '" .$class. "' ORDER BY `ap` DESC LIMIT " .$amount. "");
				$classname = mysqli_fetch_array(mysqli_query($stream, "SELECT `class` FROM `classes` WHERE `id` = '" .$class. "'"));
				$size.= ' ' .$specc. ' ' .$classname['class']. 's';
			}
			elseif(!isset($_GET['sp'])) {
				$amount = '25';
				$data = mysqli_query($stream, "SELECT * FROM `ap` ORDER BY `ap` DESC LIMIT " .$amount. "");
			}
		}
		else {
			$style = 'style="display: none;"';
			$size = $table;
			
			if(isset($_GET['sp'])) {
				$role = explode('-', $_GET['sp']);
				$class = $role['0'];
				$specc = $role['1'];	
				$amount = '150';
				$data = mysqli_query($stream, "SELECT * FROM `ap` WHERE `r` = '" .$table. "' AND `sp` = '" .$specc. "' AND `cl` = '" .$class. "' ORDER BY `ap` DESC LIMIT " .$amount. "");
				$classname = mysqli_fetch_array(mysqli_query($stream, "SELECT `class` FROM `classes` WHERE `id` = '" .$class. "'"));
				$size.= ' ' .$specc. ' ' .$classname['class']. 's';
			}
			elseif(!isset($_GET['sp'])) {
				$amount = '25';
				$data = mysqli_query($stream, "SELECT * FROM `ap` WHERE `r` = '" .$table. "' ORDER BY `ap` DESC LIMIT " .$amount. "");
			}				
		}
		
		echo '<div class="divl" ' .$style. '><br />
		click on a server or region to filter
		<p class="divl-desc next-region">TOP ' .$amount. ' ' .$size. ' <svg style="cursor: pointer;" enable-background="new 0 0 24 24" fill="#FFFFFF" height="24" id="Layer_1" version="1.1" viewBox="0 0 24 24" width="24" x="0px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" y="0px">
		<g id="XMLID_1_">
		<path d="M0,0h24v24H0V0z" fill="none"/>
		<g id="XMLID_2_">
		<rect height="2" id="XMLID_3_" width="12" x="4" y="10"/>
		<rect height="2" id="XMLID_4_" width="12" x="4" y="6"/>
		<rect height="2" id="XMLID_5_" width="8" x="4" y="14"/>
		<polygon id="XMLID_6_" points="14,14 14,20 19,17   "/>
		</g>
		</g>
		</svg></p>
		<table id="name" style="width: 100%;">
		<tr>
		<th>#</th>
		<th>Character</th>
		<th>Role</th>
		<th>AP</th>
		<th>Equip (bags)</th>
		<th>Mythics (highest)</th>
		<th>EN Mythic</th>
		<th>ToV Mythic</th>
		<th>NH Mythic</th>
		<th id="upd-row">updated</th>
		<th></th>
		</tr>';
		$num = '1';
		while($info = mysqli_fetch_array($data)) {
			$classinfo = mysqli_fetch_array(mysqli_query($stream, "SELECT `colorhex` FROM `classes` WHERE `id` = '" .$info['cl']. "'"));
			$old_data = mysqli_fetch_array(mysqli_query($stream, "SELECT `ap` FROM `ap_past` WHERE `c` = '" .$info['c']. "' AND `r` = '" .$info['r']. "' AND `s` = '" .addslashes($info['s']). "'"));
			if($old_data['ap'] != '') {
				if($info['ap'] != $old_data['ap']) {
					$apincrease = round(($info['ap']/$old_data['ap']-1)*100, 2);
					$apincrease = '<span id="gr" title="' .number_format($old_data['ap']). '">(+' .$apincrease. '%)</span>';
				}
			}
			echo '<tr>
			<td style="width: auto;">' .$num. '</td>
			<td style="width: auto;"><a href="?r=' .$info['r']. '&s=' .$info['s']. '&c=' .$info['c']. '" style="color: ' .$classinfo['colorhex']. ';">' .$info['c']. '</a> (<a href="?r=' .$info['r']. '">' .$info['r']. '</a>-<a href="?r=' .$info['r']. '&s=' .$info['s']. '">' .$info['s']. '</a>) <span id="imgs"><a href="http://' .$info['r']. '.battle.net/wow/en/character/' .$info['s']. '/' .$info['c']. '/simple"><img src="i/wow.ico" alt="404" /></a> | <a href="http://www.wowprogress.com/character/' .$info['r']. '/' .$info['s']. '/' .$info['c']. '"><img src="i/wpr.ico" alt="404" /></a> | <a href="?c=' .$info['c']. '&r=' .$info['r']. '&s=' .$info['s']. '"><img src="i/aaa.png" alt="404" /></a></span></td>
			<td><a href="?sp=' .$info['cl']. '-' .$info['sp']. '">' .$info['sp']. '</a></td>
			<td><span title="' .number_format($info['ap']). '">' .round($info['ap']/1000000, 3). ' M ' .$apincrease. '</span></td>
			<td>' .$info['ie']. ' (' .$info['ib']. ')</td>';
			
			if($info['mp'] <= '5') { $color_mp = 'id="re"'; } elseif($info['mp'] == '10') { $color_mp = 'id="or"'; } elseif($info['mp'] == '15') { $color_mp = 'id="gre"'; }
			if($info['en'] == '0') { $color_en = 'id="re"'; } elseif($info['en'] == '7') { $color_en = 'id="gre"'; } elseif($info['en'] > '0') { $color_en = 'id="or"'; }			
			if($info['tov'] == '0') { $color_tov = 'id="re"'; } elseif($info['tov'] == '3') { $color_tov = 'id="gre"'; } elseif($info['tov'] > '0') { $color_tov = 'id="or"'; }
			if($info['nh'] == '0') { $color_nh = 'id="re"'; } elseif($info['nh'] == '10') { $color_nh = 'id="gre"'; } elseif($info['nh'] > '0') { $color_nh = 'id="or"'; }
			
			echo '<td>' .$info['m']. ' (<span ' .$color_mp. '>' .$info['mp']. '</span>)</td>
			<td><span ' .$color_en. '>' .$info['en']. '/7</span></td>
			<td><span ' .$color_tov. '>' .$info['tov']. '/3</span></td>
			<td><span ' .$color_nh. '>' .$info['nh']. '/10</span></td>
			<td id="upd-row"><span title="' .date('d.m.Y - H:i', $info['lu']). '">';
				
			timeconversion($info['lu']);
				
			echo ''.$info['lu']. '</span></td>
			<td id="upd' .$info['id']. '" style="text-align: center;"><a href="#!" onclick="queue(this.id);" id="' .$info['id']. '"><img src="i/upd.png" id="refresh' .$info['id']. '" alt="404" width="13px" /><center><img src="i/load.gif" id="load' .$info['id']. '" alt="404" width="13px" style="display: none;" /></center></a></td>
			</tr>';
			$num++;
			unset($apincrease);
		}
	echo '</table>
	</div>';
	}

	for($i = '1'; $i <= '12'; $i++) {
		$classinfo = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `classes` WHERE `id` = '" .$i. "'"));
		echo '<div class="cl"><p><a href="?cl=' .$i. '"  style="color: ' .$classinfo['colorhex']. ';">TOP 5 ' .$classinfo['class']. '</a></p>
		<table style="width: 100%;">
		<tr>
		<th>Char</th>
		<th>AP</th>
		<th>EQ</th>
		<th></th>
		</tr>';
		$data = mysqli_query($stream, "SELECT * FROM `ap` WHERE `cl` = '" .$i. "' ORDER BY `ap` DESC LIMIT 5");
		while($info = mysqli_fetch_array($data)) {
			echo '<tr>
			<td style="width: 400px;"><a href="?r=' .$info['r']. '&s=' .$info['s']. '&c=' .$info['c']. '" style="color: ' .$classinfo['colorhex']. ';">' .$info['c']. '</a> (<a href="?r=' .$info['r']. '">' .$info['r']. '</a>-<a href="?r=' .$info['r']. '&s=' .$info['s']. '">' .$info['s']. '</a>)  <a href="http://' .$info['r']. '.battle.net/wow/en/character/' .$info['s']. '/' .$info['c']. '/simple"><img src="i/wow.ico" alt="404" /></a> | <a href="http://www.wowprogress.com/character/' .$info['r']. '/' .$info['s']. '/' .$info['c']. '"><img src="i/wpr.ico" alt="404" /></a> | <a href="http://check.artifactpower.info/?c=' .$info['c']. '&r=' .$info['r']. '&s=' .$info['s']. '"><img src="i/aaa.png" alt="404" /></a></td>
			<td><span title="' .number_format($info['ap']). '">' .round($info['ap']/1000000, 3). ' M</span></td>
			<td>' .$info['ie']. ' (' .$info['ib']. ')</td>
			<td id="upd' .$info['id']. '" style="text-align: center;"><a href="#!" onclick="queue(this.id);" id="' .$info['id']. '"><img src="i/upd.png" id="refresh' .$info['id']. '" alt="404" width="13px" /><center><img src="i/load.gif" id="load' .$info['id']. '" alt="404" width="13px" style="display: none;" /></center></a></td>
			</tr>';
		}
	echo '</table>
	</div>';
	}
}
// SERVER FILTER
elseif(isset($_GET['s']) && isset($_GET['r']) && !isset($_GET['c'])) {
				
	if($_GET['r'] == 'EU') {
		$region = 'EU';
	}
	elseif($_GET['r'] == 'US') {
		$region = 'US';
	}
	$realm = $_GET['s'];
	
	if(isset($_GET['sp'])) {
		$role = explode('-', $_GET['sp']);
		$class = $role['0'];
		$specc = $role['1'];		
		$data = mysqli_query($stream, "SELECT * FROM `ap` WHERE `r` = '" .$region. "' AND `s` = '" .addslashes($realm). "' AND `sp` = '" .$specc. "' AND `cl` = '" .$class. "' ORDER BY `ap` DESC LIMIT 500");
	}
	elseif(isset($_GET['cl'])) {
		$data = mysqli_query($stream, "SELECT * FROM `ap` WHERE `r` = '" .$region. "' AND `s` = '" .addslashes($realm). "' AND `cl` = '" .$_GET['cl']. "' ORDER BY `ap` DESC LIMIT 500");
		
		$classconversion = array('1' => 'Warriors', '2' => 'Paladins', '3' => 'Hunters', '4' => 'Rogues', '5' => 'Priests', '6' => 'Death Knights', '7' => 'Shamans', '8' => 'Mages', '9' => 'Warlocks', '10' => 'Monks', '11' => 'Druids', '12' => 'Demon Hunter');
	
		foreach($classconversion as $clnumber => $clname) {
			if($_GET['cl'] == $clnumber) {
				$classname = $clname;
			}
		}
	}
	elseif(!isset($_GET['sp'])) {
		$data = mysqli_query($stream, "SELECT * FROM `ap` WHERE `r` = '" .$region. "' AND `s` = '" .addslashes($realm). "' ORDER BY `ap` DESC LIMIT 500");
	}
		
	echo '<div class="divl">
	<p class="divl-desc next-region">TOP 500 ' .$region. '-' .$realm. ' ' .$classname. '</p>
	<p id="total">';
	$filterarray = array('1' => 'Warrior', '2' => 'Paladin', '3' => 'Hunter', '4' => 'Rogue', '5' => 'Priest', '6' => 'Death Knight', '7' => 'Shaman', '8' => 'Mage', '9' => 'Warlock', '10' => 'Monk', '11' => 'Druid', '12' => 'Demon Hunter');
	foreach($filterarray as $number => $filter) {
		if($number < '12') {
			echo '<a href="?r=' .$_GET['r']. '&s=' .$_GET['s']. '&cl=' .$number. '">' .$filter. '</a> | ';
		}
		elseif($number == '12') {
			echo '<a href="?r=' .$_GET['r']. '&s=' .$_GET['s']. '&cl=' .$number. '">' .$filter. '</a>';
		}
	}
		
	echo '</p>
	<table style="margin: 0 auto;">
	<tr>
	<th>#</th>
	<th>Character</th>
	<th>Role</th>
	<th>AP</th>
	<th>Artifact Level</th>
	<th>Equip (bags)</th>
	<th>Mythics (highest)</th>
	<th>EN Mythic</th>
	<th>ToV Mythic</th>
	<th>NH Mythic</th>
	<th>updated</th>
	<th></th>
	</tr>';
	$num = '1';
	while($info = mysqli_fetch_array($data)) {
		$classinfo = mysqli_fetch_array(mysqli_query($stream, "SELECT `colorhex` FROM `classes` WHERE `id` = '" .$info['cl']. "'"));
		$old_data = mysqli_fetch_array(mysqli_query($stream, "SELECT `ap` FROM `ap_past` WHERE `c` = '" .$info['c']. "' AND `r` = '" .$info['r']. "' AND `s` = '" .addslashes($info['s']). "'"));
		if($old_data['ap'] != '') {
			if($info['ap'] != $old_data['ap']) {
				$apincrease = round(($info['ap']/$old_data['ap']-1)*100, 2);
				$apincrease = '<span id="gr" title="' .number_format($old_data['ap']). '">(+' .$apincrease. '%)</span>';
			}
		}
		echo '<tr>
		<td style="width: 40px;">' .$num. '</td>
		<td style="width: 400px;"><a href="?r=' .$info['r']. '&s=' .$info['s']. '&c=' .$info['c']. '" style="color: ' .$classinfo['colorhex']. ';">' .$info['c']. '</a> (<a href="?r=' .$info['r']. '">' .$info['r']. '</a>-<a href="?r=' .$info['r']. '&s=' .$info['s']. '">' .$info['s']. '</a>) <a href="http://' .$info['r']. '.battle.net/wow/en/character/' .$info['s']. '/' .$info['c']. '/simple"><img src="i/wow.ico" alt="404" /></a> | <a href="http://www.wowprogress.com/character/' .$info['r']. '/' .$info['s']. '/' .$info['c']. '"><img src="i/wpr.ico" alt="404" /></a> | <a href="http://check.artifactpower.info/?c=' .$info['c']. '&r=' .$info['r']. '&s=' .$info['s']. '"><img src="i/aaa.png" alt="404" /></a></td>
		<td><a href="?r=' .$_GET['r']. '&s=' .$_GET['s']. '&sp=' .$info['cl']. '-' .$info['sp']. '">' .$info['sp']. '</a></td>
		<td><span title="' .number_format($info['ap']). '">' .round($info['ap']/1000000, 3). ' M</span> ' .$apincrease. '</td>
		<td>' .$info['al']. '</td>
		<td>' .$info['ie']. ' (' .$info['ib']. ')</td>';
			
		if($info['mp'] <= '5') { $color_mp = 'id="re"'; } elseif($info['mp'] == '10') { $color_mp = 'id="or"'; } elseif($info['mp'] == '15') { $color_mp = 'id="gre"'; }		
		if($info['en'] == '0') { $color_en = 'id="re"'; } elseif($info['en'] == '7') { $color_en = 'id="gre"'; } elseif($info['en'] > '0') { $color_en = 'id="or"'; }			
		if($info['tov'] == '0') { $color_tov = 'id="re"'; } elseif($info['tov'] == '3') { $color_tov = 'id="gre"'; } elseif($info['tov'] > '0') { $color_tov = 'id="or"'; }
		if($info['nh'] == '0') { $color_nh = 'id="re"'; } elseif($info['nh'] == '10') { $color_nh = 'id="gre"'; } elseif($info['nh'] > '0') { $color_nh = 'id="or"'; }
			
		echo '
		<td>' .$info['m']. ' (<span ' .$color_mp. '>' .$info['mp']. '</span>)</td><td><span ' .$color_en. '>' .$info['en']. '/7</span></td>
		<td><span ' .$color_tov. '>' .$info['tov']. '/3</span></td>
		<td><span ' .$color_nh. '>' .$info['nh']. '/10</span></td>
		<td><span title="' .date('d.m.Y - H:i', $info['lu']). '">';
				
		timeconversion($info['lu']);
				
		echo ''.$info['lu']. '</span></td><td id="upd' .$info['id']. '" style="text-align: center;"><a href="#!" onclick="queue(this.id);" id="' .$info['id']. '"><img src="i/upd.png" id="refresh' .$info['id']. '" alt="404" width="13px" /><center><img src="i/load.gif" id="load' .$info['id']. '" alt="404" width="13px" style="display: none;" /></center></a></td>
		</tr>';
		$num++;
		unset($apincrease);
	}
	echo '</table>
	</div>';
}
// REGION FILTER
elseif(!isset($_GET['s']) && isset($_GET['r']) && !isset($_GET['c']) && !isset($_GET['cl'])) {
		
	insert_form();
		
	if($_GET['r'] == 'EU') {
		$size = 'EU';
	}
	elseif($_GET['r'] == 'US') {
		$size = 'US';
	}
		
	if(isset($_GET['sp'])) {
		$role = explode('-', $_GET['sp']);
		$class = $role['0'];
		$specc = $role['1'];		
		$data = mysqli_query($stream, "SELECT * FROM `ap` WHERE `r` = '" .$size. "' AND `sp` = '" .$specc. "' AND `cl` = '" .$class. "' ORDER BY `ap` DESC LIMIT 500");
		$classname = mysqli_fetch_array(mysqli_query($stream, "SELECT `class` FROM `classes` WHERE `id` = '" .$class. "'"));
		$size.= ' ' .$specc. ' ' .$classname['class']. 's';
	}
	elseif(!isset($_GET['sp'])) {
		$data = mysqli_query($stream, "SELECT * FROM `ap` WHERE `r` = '" .$size. "' ORDER BY `ap` DESC LIMIT 500");
	}	
	
	echo '<div class="divl">
	<p class="divl-desc next-region">TOP 500 ' .$size. '</p>
	<table style="margin: 0 auto;">
	<tr>
	<th>#</th>
	<th>Character</th>
	<th>Role</th>
	<th>AP</th>
	<th>Artifact Level</th>
	<th>Equip (bags)</th>
	<th>Mythics (highest)</th>
	<th>EN Mythic</th>
	<th>ToV Mythic</th>
	<th>NH Mythic</th>
	<th>updated</th>
	<th></th>
	</tr>';
	$num = '1';
	while($info = mysqli_fetch_array($data)) {
		$classinfo = mysqli_fetch_array(mysqli_query($stream, "SELECT `colorhex` FROM `classes` WHERE `id` = '" .$info['cl']. "'"));
		$old_data = mysqli_fetch_array(mysqli_query($stream, "SELECT `ap` FROM `ap_past` WHERE `c` = '" .$info['c']. "' AND `r` = '" .$info['r']. "' AND `s` = '" .addslashes($info['s']). "'"));
		if($old_data['ap'] != '') {
			if($info['ap'] != $old_data['ap']) {
				$apincrease = round(($info['ap']/$old_data['ap']-1)*100, 2);
				$apincrease = '<span id="gr" title="' .number_format($old_data['ap']). '">(+' .$apincrease. '%)</span>';
			}
		}
		echo '<tr>
		<td style="width: 40px;">' .$num. '</td>
		<td style="width: 400px;"><a href="?r=' .$info['r']. '&s=' .$info['s']. '&c=' .$info['c']. '" style="color: ' .$classinfo['colorhex']. ';">' .$info['c']. '</a> (<a href="?r=' .$info['r']. '">' .$info['r']. '</a>-<a href="?r=' .$info['r']. '&s=' .$info['s']. '">' .$info['s']. '</a>) <a href="http://' .$info['r']. '.battle.net/wow/en/character/' .$info['s']. '/' .$info['c']. '/simple"><img src="i/wow.ico" alt="404" /></a> | <a href="http://www.wowprogress.com/character/' .$info['r']. '/' .$info['s']. '/' .$info['c']. '"><img src="i/wpr.ico" alt="404" /></a> | <a href="http://check.artifactpower.info/?c=' .$info['c']. '&r=' .$info['r']. '&s=' .$info['s']. '"><img src="i/aaa.png" alt="404" /></a></td>
		<td><a href="?r=' .$_GET['r']. '&sp=' .$info['cl']. '-' .$info['sp']. '">' .$info['sp']. '</a></td>
		<td><span title="' .number_format($info['ap']). '">' .round($info['ap']/1000000, 3). ' M ' .$apincrease. '</span></td>
		<td>' .$info['al']. '</td>
		<td>' .$info['ie']. ' (' .$info['ib']. ')</td>';
			
		if($info['mp'] <= '5') { $color_mp = 'id="re"'; } elseif($info['mp'] == '10') { $color_mp = 'id="or"'; } elseif($info['mp'] == '15') { $color_mp = 'id="gre"'; }
		if($info['en'] == '0') { $color_en = 'id="re"'; } elseif($info['en'] == '7') { $color_en = 'id="gre"'; } elseif($info['en'] > '0') { $color_en = 'id="or"'; }			
		if($info['tov'] == '0') { $color_tov = 'id="re"'; } elseif($info['tov'] == '3') { $color_tov = 'id="gre"'; } elseif($info['tov'] > '0') { $color_tov = 'id="or"'; }
		if($info['nh'] == '0') { $color_nh = 'id="re"'; } elseif($info['nh'] == '10') { $color_nh = 'id="gre"'; } elseif($info['nh'] > '0') { $color_nh = 'id="or"'; }
			
		echo '<td>' .$info['m']. ' (<span ' .$color_mp. '>' .$info['mp']. '</span>)</td>
		<td><span ' .$color_en. '>' .$info['en']. '/7</span></td>
		<td><span ' .$color_tov. '>' .$info['tov']. '/3</span></td>
		<td><span ' .$color_nh. '>' .$info['nh']. '/10</span></td>
		<td><span title="' .date('d.m.Y - H:i', $info['lu']). '">';
				
		timeconversion($info['lu']);
			
		echo ''.$info['lu']. '</span></td>
		<td id="upd' .$info['id']. '" style="text-align: center;"><a href="#!" onclick="queue(this.id);" id="' .$info['id']. '"><img src="i/upd.png" id="refresh' .$info['id']. '" alt="404" width="13px" /><center><img src="i/load.gif" id="load' .$info['id']. '" alt="404" width="13px" style="display: none;" /></center></a></td>
		</tr>';
		$num++;
	}
	echo '</table>
	</div>';
}
// CLASS FILTER
elseif(!isset($_GET['s']) && !isset($_GET['r']) && isset($_GET['cl']) && !isset($_GET['c'])) {
		
	insert_form();
	
	$classconversion = array('1' => 'Warriors', '2' => 'Paladins', '3' => 'Hunters', '4' => 'Rogues', '5' => 'Priests', '6' => 'Death Knights', '7' => 'Shamans', '8' => 'Mages', '9' => 'Warlocks', '10' => 'Monks', '11' => 'Druids', '12' => 'Demon Hunter');
	
	foreach($classconversion as $clnumber => $clname) {
		if($_GET['cl'] == $clnumber) {
			$classname = $clname;
		}
	}
		
	$data = mysqli_query($stream, "SELECT * FROM `ap` WHERE `cl` = '" .$_GET['cl']. "' ORDER BY `ap` DESC LIMIT 500");
	
	echo '<div class="divl">
	<p class="divl-desc next-region" style=" color: #C0B283 !important; font-size: 25px;">TOP 500 ' .$classname. '</p>
	<p id="total">';
	$filterarray = array('1' => 'Warrior', '2' => 'Paladin', '3' => 'Hunter', '4' => 'Rogue', '5' => 'Priest', '6' => 'Death Knight', '7' => 'Shaman', '8' => 'Mage', '9' => 'Warlock', '10' => 'Monk', '11' => 'Druid', '12' => 'Demon Hunter');
	foreach($filterarray as $number => $filter) {
		if($number < '12') {
			echo '<a href="?cl=' .$number. '">' .$filter. '</a> | ';
		}
		elseif($number == '12') {
			echo '<a href="?cl=' .$number. '">' .$filter. '</a>';
		}
	}
		
	echo '</p>
	<table style="margin: 0 auto;">
	<tr>
	<th>#</th>
	<th>Character</th>
	<th>Role</th>
	<th>AP</th>
	<th>Artifact Level</th>
	<th>Equip (bags)</th>
	<th>Mythics (highest)</th>
	<th>EN Mythic</th>
	<th>ToV Mythic</th>
	<th>NH Mythic</th>
	<th>updated</th>
	<th></th>
	</tr>';
	$num = '1';
	while($info = mysqli_fetch_array($data)) {
		$classinfo = mysqli_fetch_array(mysqli_query($stream, "SELECT `colorhex` FROM `classes` WHERE `id` = '" .$info['cl']. "'"));
		$old_data = mysqli_fetch_array(mysqli_query($stream, "SELECT `ap` FROM `ap_past` WHERE `c` = '" .$info['c']. "' AND `r` = '" .$info['r']. "' AND `s` = '" .addslashes($info['s']). "'"));
		if($old_data['ap'] != '') {
			if($info['ap'] != $old_data['ap']) {
				$apincrease = round(($info['ap']/$old_data['ap']-1)*100, 2);
				$apincrease = '<span id="gr" title="' .number_format($old_data['ap']). '">(+' .$apincrease. '%)</span>';
			}
		}
		echo '<tr>
		<td style="width: 40px;">' .$num. '</td>
		<td style="width: 400px;"><a href="?r=' .$info['r']. '&s=' .$info['s']. '&c=' .$info['c']. '" style="color: ' .$classinfo['colorhex']. ';">' .$info['c']. '</a> (<a href="?r=' .$info['r']. '">' .$info['r']. '</a>-<a href="?r=' .$info['r']. '&s=' .$info['s']. '">' .$info['s']. '</a>) <a href="http://' .$info['r']. '.battle.net/wow/en/character/' .$info['s']. '/' .$info['c']. '/simple"><img src="i/wow.ico" alt="404" /></a> | <a href="http://www.wowprogress.com/character/' .$info['r']. '/' .$info['s']. '/' .$info['c']. '"><img src="i/wpr.ico" alt="404" /></a> | <a href="http://check.artifactpower.info/?c=' .$info['c']. '&r=' .$info['r']. '&s=' .$info['s']. '"><img src="i/aaa.png" alt="404" /></a></td>
		<td><a href="?sp=' .$info['cl']. '-' .$info['sp']. '">' .$info['sp']. '</a></td>
		<td><span title="' .number_format($info['ap']). '">' .round($info['ap']/1000000, 3). ' M ' .$apincrease. '</span></td>
		<td>' .$info['al']. '</td>
		<td>' .$info['ie']. ' (' .$info['ib']. ')</td>';
			
		if($info['mp'] <= '5') { $color_mp = 'id="re"'; } elseif($info['mp'] == '10') { $color_mp = 'id="or"'; } elseif($info['mp'] == '15') { $color_mp = 'id="gre"'; }
		if($info['en'] == '0') { $color_en = 'id="re"'; } elseif($info['en'] == '7') { $color_en = 'id="gre"'; } elseif($info['en'] > '0') { $color_en = 'id="or"'; }			
		if($info['tov'] == '0') { $color_tov = 'id="re"'; } elseif($info['tov'] == '3') { $color_tov = 'id="gre"'; } elseif($info['tov'] > '0') { $color_tov = 'id="or"'; }
		if($info['nh'] == '0') { $color_nh = 'id="re"'; } elseif($info['nh'] == '10') { $color_nh = 'id="gre"'; } elseif($info['nh'] > '0') { $color_nh = 'id="or"'; }
			
		echo '<td>' .$info['m']. ' (<span ' .$color_mp. '>' .$info['mp']. '</span>)</td>
		<td><span ' .$color_en. '>' .$info['en']. '/7</span></td>
		<td><span ' .$color_tov. '>' .$info['tov']. '/3</span></td>
		<td><span ' .$color_nh. '>' .$info['nh']. '/10</span></td>
		<td><span title="' .date('d.m.Y - H:i', $info['lu']). '">';
				
		timeconversion($info['lu']);
			
		echo ''.$info['lu']. '</span></td>
		<td id="upd' .$info['id']. '" style="text-align: center;"><a href="#!" onclick="queue(this.id);" id="' .$info['id']. '"><img src="i/upd.png" id="refresh' .$info['id']. '" alt="404" width="13px" /><center><img src="i/load.gif" id="load' .$info['id']. '" alt="404" width="13px" style="display: none;" /></center></a></td>
		</tr>';
		$num++;
		unset($apincrease);
	}
	echo '</table>
	</div>';
}
// PROFILE FILTER
elseif(isset($_GET['s']) && isset($_GET['r']) && isset($_GET['c']) && !isset($_GET['cl'])) {
		
	$info = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `ap` WHERE `c` = '" .$_GET['c']. "' AND `s` = '" .addslashes($_GET['s']). "' AND `r` = '" .$_GET['r']. "'"));
	if($info['ap'] == '') {
			
		?>
		<script type="text/javascript">
			$( document ).ready(function() {
				var c = $("#ch").val();
				var r = $("#reg").val();
				var s = $("#rea").val();
		
				$.ajax({
					type: "GET",
					dataType: "html",
					url: "m/q.php",
					data: { cg: c,
				   		r: r,
				   		s: s
					},
					success: function(data) {
						$.ajax({
							type: "GET",
							dataType: "html",
							url: "m/i2.php",
							success: function(data) {
								location.reload();
							}
						})
					}
				});
			})
		</script>
		<?
			
		echo '<input hidden value="' .$_GET['r']. '" id="reg" />
		<input hidden value="' .$_GET['s']. '" id="rea" />
		<input hidden value="' .$_GET['c']. '" id="ch" />
		<p id="patience"><img src="i/load.gif" width="13px" alt="404" /><br />..updating.. page will auto refresh when done fetching data</p>';
	}
	elseif($info['id'] != '') {
	
		echo '<table style="margin: 0 auto;">
		<p id="total">Profile of <a href="?r=' .$info['r']. '&s=' .$info['s']. '&c=' .$info['c']. '" style="color: ' .$classinfo['colorhex']. ';">' .$info['c']. '</a> (<a href="?r=' .$info['r']. '">' .$info['r']. '</a>-<a href="?r=' .$info['r']. '&s=' .$info['s']. '">' .$info['s']. '</a>) <span id="imgs"><a href="http://' .$info['r']. '.battle.net/wow/en/character/' .$info['s']. '/' .$info['c']. '/simple"><img src="i/wow.ico" alt="404" /></a> | <a href="http://www.wowprogress.com/character/' .$info['r']. '/' .$info['s']. '/' .$info['c']. '"><img src="i/wpr.ico" alt="404" /></a> | <a href="?c=' .$info['c']. '&r=' .$info['r']. '&s=' .$info['s']. '"><img src="i/aaa.png" alt="404" /></a></p>
		<p id="total">Amount of finished Mythic dungeons
		<tr>';
		$dungeonarray = array('<span title="The Arcway">ARC</span>', '<span title="Black Rook Hold">BRH</span>', '<span title="Cathedral of Eternal Night">CEN</span>', '<span title="Court of Stars">COS</span>', '<span title="Darkheart Thicket">DHT</span>', '<span title="Eye of Azshara">EOA</span>', '<span title="Halls of Valor">HOV</span>', '<span title="Lower Karazhan">LKZ</span>', '<span title="Upper Karazhan">UKZ</span>', '<span title="Maw of Souls">MOS</span>', '<span title="Neltharions Lair">NEL</span>', '<span title="Violet Hold">VH</span>', '<span title="Vault of the Wardens">VOW</title>', 'TOTAL');
		foreach($dungeonarray as $dungeon) {
			echo '<th>' .$dungeon. '</th>';
		}
			
		echo '</tr>
		<tr>';
		
		$old_array = array();
		$dungeonarray = array('arc', 'brh', 'cen', 'cos', 'dht', 'eoa', 'hov', 'lkz', 'ukz', 'mos', 'nel', 'vh', 'vow', 'total');
		foreach($dungeonarray as $result) {
			if($result != 'total') {
				$old_data = mysqli_fetch_array(mysqli_query($stream, "SELECT `" .$result. "` FROM `ap_past` WHERE `c` = '" .$info['c']. "' AND `r` = '" .$info['r']. "' AND `s` = '" .addslashes($info['s']). "'"));
				$old = $info[$result]-$old_data[$result];
				if($old != '0') {			
					array_push($old_array, $old);
					$former = '<span id="gr">(+' .$old. ')</span>';
				}
				echo '<td>' .$info[$result]. ' ' .$former. '</td>';
			}
			elseif($result == 'total') {
				if(array_sum($old_array) != '0') {
					$former = '<span id="gr">(+' .array_sum($old_array). ')</span>';
				}
				echo '<td>' .($info['arc']+$info['brh']+$info['cen']+$info['cos']+$info['dht']+$info['eoa']+$info['hov']+$info['lkz']+$info['lkz']+$info['mos']+$info['nel']+$info['vh']+$info['vow']). ' ' .$former. '</td>';
			}
			unset($former);
		}
		
		echo '</tr>
		</table></p>';
			
		if(isset($_GET['lim']) && $_GET['lim'] == '1') {
			$specc = $info['sp'];
			$class = $info['cl'];
			$rank = mysqli_fetch_array(mysqli_query($stream, "SELECT z.rank FROM (SELECT `id`, `cl`, `sp`, @rownum := @rownum + 1 AS `rank` FROM `ap`, (SELECT @rownum := 0) r WHERE `sp` = '" .$specc. "' AND `cl` = '" .$class. "' ORDER BY `ap` DESC) as z WHERE `id` = '" .$info['id']. "'"));
			$lim = '<p id="total"><a href="?r=' .$_GET['r']. '&s=' .$_GET['s']. '&c=' .$_GET['c']. '">show all nearby</p>';
		}
		elseif(!isset($_GET['lim'])) {	
			$rank = mysqli_fetch_array(mysqli_query($stream, "SELECT z.rank FROM (SELECT `id`, @rownum := @rownum + 1 AS `rank` FROM `ap`, (SELECT @rownum := 0) r ORDER BY `ap` DESC) as z WHERE `id` = '" .$info['id']. "'"));
			$lim = '<p id="total"><a href="?r=' .$_GET['r']. '&s=' .$_GET['s']. '&c=' .$_GET['c']. '&lim=1">show only same class & role</p>';
		}
		
		echo '<table style="margin: 0 auto;">
		' .$lim. '
		<tr>
		<th>#</th>
		<th>Character</th>
		<th>Role</th>
		<th>AP</th>
		<th>Equip (bags)</th>
		<th>Mythics (highest)</th>
		<th>EN Mythic</th>
		<th>ToV Mythic</th>
		<th>NH Mythic</th>
		<th></th>
		</tr>';
		
		$selection = array('5', '4', '3', '2', '1', '0');
		
		function plusminusrows() {
			global $rank, $users, $info, $classinfo, $apincrease;
			if($users == '0') {
				$myself = 'style="background: black;"';
			}
			echo '<tr ' .$myself. '>
			<td style="width: 40px;">' .(number_format($rank['rank']-$users)). '</td>
			<td style="width: 400px;"><a href="?r=' .$info['r']. '&s=' .$info['s']. '&c=' .$info['c']. '" style="color: ' .$classinfo['colorhex']. ';">' .$info['c']. '</a> (<a href="?r=' .$info['r']. '">' .$info['r']. '</a>-<a href="?r=' .$info['r']. '&s=' .$info['s']. '">' .$info['s']. '</a>) <a href="http://' .$info['r']. '.battle.net/wow/en/character/' .$info['s']. '/' .$info['c']. '/simple"><img src="i/wow.ico" alt="404" /></a> | <a href="http://www.wowprogress.com/character/' .$info['r']. '/' .$info['s']. '/' .$info['c']. '"><img src="i/wpr.ico" alt="404" /></a> | <a href="http://check.artifactpower.info/?c=' .$info['c']. '&r=' .$info['r']. '&s=' .$info['s']. '"><img src="i/aaa.png" alt="404" /></a></td>
			<td><a href="?sp=' .$info['cl']. '-' .$info['sp']. '">' .$info['sp']. '</a></td>
			<td><span title="' .number_format($info['ap']). '">' .round($info['ap']/1000000, 3). ' M ' .$apincrease. '</span></td>
			<td>' .$info['ie']. ' (' .$info['ib']. ')</td>';
			
			if($info['mp'] <= '5') { $color_mp = 'id="re"'; } elseif($info['mp'] == '10') { $color_mp = 'id="or"'; } elseif($info['mp'] == '15') { $color_mp = 'id="gre"'; }
			if($info['en'] == '0') { $color_en = 'id="re"'; } elseif($info['en'] == '7') { $color_en = 'id="gre"'; } elseif($info['en'] > '0') { $color_en = 'id="or"'; }			
			if($info['tov'] == '0') { $color_tov = 'id="re"'; } elseif($info['tov'] == '3') { $color_tov = 'id="gre"'; } elseif($info['tov'] > '0') { $color_tov = 'id="or"'; }
			if($info['nh'] == '0') { $color_nh = 'id="re"'; } elseif($info['nh'] == '10') { $color_nh = 'id="gre"'; } elseif($info['nh'] > '0') { $color_nh = 'id="or"'; }
		
			echo '<td>' .$info['m']. ' (<span ' .$color_mp. '>' .$info['mp']. '</span>)</td>
			<td><span ' .$color_en. '>' .$info['en']. '/7</span></td>
			<td><span ' .$color_tov. '>' .$info['tov']. '/3</span></td>
			<td><span ' .$color_nh. '>' .$info['nh']. '/10</span></td>
			<td id="upd' .$info['id']. '" style="text-align: center;"><a href="#!" onclick="queue(this.id);" id="' .$info['id']. '"><img src="i/upd.png" id="refresh' .$info['id']. '" alt="404" width="13px" /><center><img src="i/load.gif" id="load' .$info['id']. '" alt="404" width="13px" style="display: none;" /></center></a></td>
			</tr>';
			
		}
		
		foreach($selection as $users) {
			
			if($users != '0') {
				if(isset($_GET['lim']) && $_GET['lim'] == '1') {
					$info = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM (SELECT *, @rownum := @rownum + 1 AS `rank` FROM `ap`, (SELECT @rownum := 0) r WHERE `sp` = '" .$specc. "' AND `cl` = '" .$class. "' ORDER BY `ap` DESC) as z WHERE `rank` = '" .($rank['rank']-$users). "'"));
				}
				elseif(!isset($_GET['lim'])) {
					$info = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM (SELECT *, @rownum := @rownum + 1 AS `rank` FROM `ap`, (SELECT @rownum := 0) r ORDER BY `ap` DESC) as z WHERE `rank` = '" .($rank['rank']-$users). "'"));
				}
			
				if(($info['c'] != '') && ($info['r'] != '') && ($info['s'] != '')) {
			
					$classinfo = mysqli_fetch_array(mysqli_query($stream, "SELECT `colorhex` FROM `classes` WHERE `id` = '" .$info['cl']. "'"));
					$old_data = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `ap_past` WHERE `c` = '" .$info['c']. "' AND `r` = '" .$info['r']. "' AND `s` = '" .addslashes($info['s']). "'"));
					if($old_data['ap'] != '') {
						if($info['ap'] != $old_data['ap']) {
							$apincrease = round(($info['ap']/$old_data['ap']-1)*100, 2);
							$apincrease = '<span id="gr" title="' .number_format($old_data['ap']). '">(+' .$apincrease. '%)</span>';
						}
					}
					
					plusminusrows();
					
					unset($apincrease);
				
				}
			}
			elseif($users == '0') {
				$info = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `ap` WHERE `c` = '" .$_GET['c']. "' AND `s` = '" .addslashes($_GET['s']). "' AND `r` = '" .$_GET['r']. "'"));
				$classinfo = mysqli_fetch_array(mysqli_query($stream, "SELECT `colorhex` FROM `classes` WHERE `id` = '" .$info['cl']. "'"));
				$old_data = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `ap_past` WHERE `c` = '" .$info['c']. "' AND `r` = '" .$info['r']. "' AND `s` = '" .addslashes($info['s']). "'"));
				if($old_data['ap'] != '') {
					if($info['ap'] != $old_data['ap']) {
						$apincrease = round(($info['ap']/$old_data['ap']-1)*100, 2);
						$apincrease = '<span id="gr" title="' .number_format($old_data['ap']). '">(+' .$apincrease. '%)</span>';
					}
				}
				
				plusminusrows();
				
				unset($apincrease);
				
			}
		}
		echo '</table><br />';
	}
}

echo '</div>
</html>';

mysqli_close();

?>
<script type="text/javascript">
(function(){

var $divl = $('.divl');
var $divlDescriptions = $('.divl-desc');
var i = 0;
		
$('.next-region').on('click', function(){
    i = (i + 1) % $divl.length;
    $divl.hide().eq(i).show();
    $divlDescriptions.hide().eq(i).show();
});

})();
</script>
<script type="text/javascript">
server_EU=new Array("Aegwynn","Aerie Peak","Agamaggan","Aggra-portugues","Aggramar","Ahn'Qiraj","Al'Akir","Alexstrasza","Alleria","Alonsus","Aman'Thul","Ambossar","Anachronos","Anetheron","Antonidas","Anub'arak","Arak-arahm","Arathi","Arathor","Archimonde","Area 52","Argent Dawn","Arthas","Arygos","Aszune","Auchindoun","Azjol-Nerub","Azshara","Azuremyst","Baelgun","Balnazzar","Blackhand","Blackmoore","Blackrock","Blade's Edge","Bladefist","Bloodfeather","Bloodhoof","Bloodscalp","Blutkessel","Boulderfist","Bronze Dragonflight","Bronzebeard","Burning Blade","Burning Legion","Burning Steppes","C'Thun","Chamber of Aspects","Chants \u00e9ternels","Cho'gall","Chromaggus","Colinas Pardas","Confr\u00e9rie du Thorium","Conseil des Ombres","Crushridge","Culte de la Rive Noire","Daggerspine","Dalaran","Dalvengyr","Darkmoon Faire","Darksorrow","Darkspear","Das Konsortium","Das Syndikat","Deathwing","Defias Brotherhood","Dentarg","Der abyssische Rat","Der Mithrilorden","Der Rat von Dalaran","Destromath","Dethecus","Die Aldor","Die Arguswacht","Die ewige Wacht","Die Nachtwache","Die Silberne Hand","Die Todeskrallen","Doomhammer","Draenor","Dragonblight","Dragonmaw","Drak'thul","Drek'Thar","Dun Modr","Dun Morogh","Dunemaul","Durotan","Earthen Ring","Echsenkessel","Eitrigg","Eldre'Thalas","Elune","Emerald Dream","Emeriss","Eonar","Eredar","Euskal Encounter","Executus","Exodar","Festung der St\u00fcrme","Forscherliga","Frostmane","Frostmourne","Frostwhisper","Frostwolf","Garona","Garrosh","Genjuros","Ghostlands","Gilneas","Gorgonnash","Grim Batol","Gul'dan","Hakkar","Haomarush","Hellfire","Hellscream","Hyjal","Illidan","Jaedenar","Kael'Thas","Karazhan","Kargath","Kazzak","Kel'Thuzad","Khadgar","Khaz Modan","Khaz'goroth","Kil'Jaeden","Kilrogg","Kirin Tor","Kor'gall","Krag'jin","Krasus","Kul Tiras","Kult der Verdammten","La Croisade \u00e9carlate","Laughing Skull","Les Clairvoyants","Les Sentinelles","Lightbringer","Lightning's Blade","Lordaeron","Los Errantes","Lothar","Madmortem","Magtheridon","Mal'Ganis","Malfurion","Malorne","Malygos","Mannoroth","Mar\u00e9cage de Zangar","Mazrigos","Medivh","Minahonda","Molten Core","Moonglade","Mug'thol","Nagrand","Nathrezim","Naxxramas","Nazjatar","Nefarian","Nemesis","Neptulon","Ner'zhul","Nera'thor","Nethersturm","Nordrassil","Norgannon","Nozdormu","Onyxia","Outland","Perenolde","Pozzo dell'Eternit\u00e0","Proudmoore","Quel'Thalas","Ragnaros","Rajaxx","Rashgarroth","Ravencrest","Ravenholdt","Rexxar","Runetotem","Sanguino","Sargeras","Saurfang","Scarshield Legion","Sen'jin","Shadowmoon","Shadowsong","Shattered Halls","Shattered Hand","Shattrath","Shen'dralar","Silvermoon","Sinstralis","Skullcrusher","Spinebreaker","Sporeggar","Steamwheedle Cartel","Stonemaul","Stormrage","Stormreaver","Stormscale","Sunstrider","Suramar","Sylvanas","Taerar","Talnivarr","Tarren Mill","Teldrassil","Temple noir","Terenas","Terokkar","Terrordar","The Maelstrom","The Sha'tar","The Venture Co","Theradras","Thrall","Throk'Feroth","Thunderhorn","Tichondrius","Tirion","Todeswache","Trollbane","Turalyon","Twilight's Hammer","Twisting Nether","Tyrande","Uldaman","Uldum","Un'Goro","Varimathras","Vashj","Vek'lor","Vek'nilash","Vol'jin","Warsong","Wildhammer","Wrathbringer","Xavius","Ysera","Ysondre","Zenedar","Zirkel des Cenarius","Zul'jin","Zuluhed","\u0410\u0437\u0443\u0440\u0435\u0433\u043e\u0441","\u0411\u043e\u0440\u0435\u0439\u0441\u043a\u0430\u044f \u0442\u0443\u043d\u0434\u0440\u0430","\u0412\u0435\u0447\u043d\u0430\u044f \u041f\u0435\u0441\u043d\u044f","\u0413\u0430\u043b\u0430\u043a\u0440\u043e\u043d\u0434","\u0413\u043e\u043b\u0434\u0440\u0438\u043d\u043d","\u0413\u043e\u0440\u0434\u0443\u043d\u043d\u0438","\u0413\u0440\u043e\u043c","\u0414\u0440\u0430\u043a\u043e\u043d\u043e\u043c\u043e\u0440","\u041a\u043e\u0440\u043e\u043b\u044c-\u043b\u0438\u0447","\u041f\u0438\u0440\u0430\u0442\u0441\u043a\u0430\u044f \u0431\u0443\u0445\u0442\u0430","\u041f\u043e\u0434\u0437\u0435\u043c\u044c\u0435","\u0420\u0430\u0437\u0443\u0432\u0438\u0439","\u0420\u0435\u0432\u0443\u0449\u0438\u0439 \u0444\u044c\u043e\u0440\u0434","\u0421\u0432\u0435\u0436\u0435\u0432\u0430\u0442\u0435\u043b\u044c \u0414\u0443\u0448","\u0421\u0435\u0434\u043e\u0433\u0440\u0438\u0432","\u0421\u0442\u0440\u0430\u0436 \u0421\u043c\u0435\u0440\u0442\u0438","\u0422\u0435\u0440\u043c\u043e\u0448\u0442\u0435\u043f\u0441\u0435\u043b\u044c","\u0422\u043a\u0430\u0447 \u0421\u043c\u0435\u0440\u0442\u0438","\u0427\u0435\u0440\u043d\u044b\u0439 \u0428\u0440\u0430\u043c","\u042f\u0441\u0435\u043d\u0435\u0432\u044b\u0439 \u043b\u0435\u0441");
server_US=new Array("Aegwynn","Aerie Peak","Agamaggan","Aggramar","Akama","Alexstrasza","Alleria","Altar of Storms","Alterac Mountains","Aman'Thul","Andorhal","Anetheron","Antonidas","Anub'arak","Anvilmar","Arathor","Archimonde","Area 52","Argent Dawn","Arthas","Arygos","Auchindoun","Azgalor","Azjol-Nerub","Azralon","Azshara","Azuremyst","Baelgun","Balnazzar","Barthilas","Black Dragonflight","Blackhand","Blackrock","Blackwater Raiders","Blackwing Lair","Blade's Edge","Bladefist","Bleeding Hollow","Blood Furnace","Bloodhoof","Bloodscalp","Bonechewer","Borean Tundra","Boulderfist","Bronzebeard","Burning Blade","Burning Legion","Caelestrasz","Cairne","Cenarion Circle","Cenarius","Cho'gall","Chromaggus","Coilfang","Crushridge","Daggerspine","Dalaran","Dalvengyr","Dark Iron","Darkspear","Darrowmere","Dath'Remar","Dawnbringer","Deathwing","Demon Soul","Dentarg","Destromath","Dethecus","Detheroc","Doomhammer","Draenor","Dragonblight","Dragonmaw","Drak'tharon","Drak'thul","Draka","Drakkari","Dreadmaul","Drenden","Dunemaul","Durotan","Duskwood","Earthen Ring","Echo Isles","Eitrigg","Eldre'Thalas","Elune","Emerald Dream","Eonar","Eredar","Executus","Exodar","Farstriders","Feathermoon","Fenris","Firetree","Fizzcrank","Frostmane","Frostmourne","Frostwolf","Galakrond","Gallywix","Garithos","Garona","Garrosh","Ghostlands","Gilneas","Gnomeregan","Goldrinn","Gorefiend","Gorgonnash","Greymane","Grizzly Hills","Grizzly Hills","Gul'dan","Gundrak","Gurubashi","Hakkar","Haomarush","Hellscream","Hydraxis","Hyjal","Icecrown","Illidan","Jaedenar","Jubei'Thos","Kael'thas","Kalecgos","Kargath","Kel'Thuzad","Khadgar","Khaz Modan","Khaz'goroth","Kil'Jaeden","Kilrogg","Kirin Tor","Korgath","Korialstrasz","Kul Tiras","Laughing Skull","Lethon","Lightbringer","Lightning's Blade","Lightninghoof","Llane","Lothar","Madoran","Maelstrom","Magtheridon","Maiev","Mal'Ganis","Malfurion","Malorne","Malygos","Mannoroth","Medivh","Misha","Mok'Nathal","Moon Guard","Moonrunner","Mug'thol","Muradin","Nagrand","Nathrezim","Nazgrel","Nazjatar","Nemesis","Ner'zhul","Nesingwary","Nordrassil","Norgannon","Onyxia","Perenolde","Proudmoore","Quel'Dorei","Quel'Thalas","Ragnaros","Ravencrest","Ravenholdt","Rexxar","Rivendare","Runetotem","Sargeras","Saurfang","Scarlet Crusade","Scilla","Sen'Jin","Sentinels","Shadow Council","Shadowmoon","Shadowsong","Shandris","Shattered Halls","Shattered Hand","Shu'Halo","Silver Hand","Silvermoon","Sisters of Elune","Skullcrusher","Skywall","Smolderthorn","Spinebreaker","Spirestone","Staghelm","Steamwheedle Cartel","Stonemaul","Stormrage","Stormreaver","Stormscale","Suramar","Tanaris","Terenas","Terokkar","Thaurissan","The Forgotten Coast","The Scryers","The Underbog","The Venture Co","Thorium Brotherhood","Thrall","Thunderhorn","Thunderlord","Tichondrius","Tol Barad","Tortheldrin","Trollbane","Turalyon","Twisting Nether","Uldaman","Uldum","Undermine","Ursin","Uther","Vashj","Vek'nilash","Velen","Warsong","Whisperwind","Wildhammer","Windrunner","Winterhoof","Wyrmrest Accord","Ysera","Ysondre","Zangarmarsh","Zul'jin","Zuluhed");
		
populateSelect();
			
$(function() {
	$('#r').change(function(){
			populateSelect();
		});
	});
			
	function populateSelect(){
		region=$('#r').val();
		$('#s').html('');
		
		if(region=='EU'){
			server_EU.forEach(function(t) { 
				$('#s').append('<option>'+t+'</option>');
			});
		}
		
		if(region=='US'){
			server_US.forEach(function(t) {
				$('#s').append('<option>'+t+'</option>');
			});
		}
}
</script>