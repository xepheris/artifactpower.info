<?php
require_once('d.php');

if(isset($_GET['id'])) {
	$data = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `ap` WHERE `id` = '" .$_GET['id']. "'"));
	$_GET['cg'] = $data['c'];
	$_GET['s'] = $data['s'];
	$_GET['r'] = $data['r'];
	$reload = '1';
}

$charguild = ucwords(strtolower($_GET['cg']));
$realm = $_GET['s'];
$region = $_GET['r'];


if($region != 'EU' && $region != 'US') {
	die();
}

if((strlen($region) > '2')) {
	die();
}

if((strlen($charguild) > '30')) {
	die();
}

// CHECK IF VAR IS CHARACTER OR GUILD
// ENABLE SSL
$arrContextOptions=array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, ),);  
		
// REMOVE SPACES IN SERVER AND POTENTIAL GUILD NAME TO PREVENT BUGS IN URL
if(strpos($realm, ' ') !== false) {
	$realm = str_replace(' ', '-', $realm);
}
if(strpos($charguild, ' ') !== false) {
	$charguild = str_replace(' ', '%20', $charguild);
}
// REMOVE SLASHES IN SERVER NAME TO ALLOW ACTUAL SEARCH AGAIN
$realm = stripslashes($realm);

// CHECK IF VAR IS GUILD
$url = 'https://' .$region. '.api.battle.net/wow/guild/' .$realm. '/' .$charguild. '?fields=members&locale=en_GB&apikey=KEY';

$data = @file_get_contents($url, false, stream_context_create($arrContextOptions));

if($data != '') {
	$guild = '1';
	$data = json_decode($data, true);
	if($data['name'] == $_GET['cg']) {
		$chararray = array();
						
		foreach($data['members'] as $member) {
			if($member['character']['level'] >= '101') {
				array_push($chararray, $member['character']['name']);
			}
		}
				
		$num = '0';
		foreach($chararray as $char) {
			$realm = addslashes($realm);
			$already_in = mysqli_fetch_array(mysqli_query($stream, "SELECT `id` FROM `q` WHERE `c` = '" .$char. "' AND `r` = '" .$region. "' AND `s` = '" .$realm. "'"));
			if($already_in['id'] == '') {
				mysqli_query($stream, "INSERT INTO `q` (`c`, `r`, `s`) VALUES ('" .$char. "', '" .$region. "', '" .$realm. "'); ");
				$num++;
			}
		}
		echo '<p style="color: green;">Guild insertion: ' .$num. ' characters have been queued. If this value is below your desired, this means some characters were already queued.</p>';
	}
}

$url = 'https://' .$region. '.api.battle.net/wow/character/' .$realm. '/' .$charguild. '?fields=statistics,achievements,talents&locale=en_GB&apikey=KEY';
$data = @file_get_contents($url, false, stream_context_create($arrContextOptions));

if($data != '') {
	$character = '1';
	$data = json_decode($data, true);
	if($data['level'] > '100') {
		$realm = addslashes($realm);
		$already_in = mysqli_fetch_array(mysqli_query($stream, "SELECT `id` FROM `q` WHERE `c` = '" .$data['name']. "' AND `r` = '" .$region. "' AND `s` = '" .$realm. "'"));
		if($already_in['id'] == '') {
			mysqli_query($stream, "INSERT INTO `q` (`c`, `r`, `s`) VALUES ('" .$data['name']. "', '" .$region. "', '" .$realm. "'); ");
			$realm = stripslashes($realm);
			if(strpos($realm, '-') !== false) {
				$realm = str_replace('-', ' ', $realm);
			}
			echo '<a href="?r=' .$region. '&s=' .$realm. '&c=' .$data['name']. '" style="color: green !important;">Profile</a>';
		}
		else {
			$realm = stripslashes($realm);
			if(strpos($realm, '-') !== false) {
				$realm = str_replace('-', ' ', $realm);
			}
			echo '<span style="color: orange;">Already queued!</span>';
		}
	}
	elseif($data['level'] < '100') {
		echo '<p style="color: red;">Sorry, only characters eligible to collect Artifact Power are allowed.<br />Please insert a character higher than or equal level 100.</p>';
	}
	mysqli_close();
	die();
}

if($character != '1' && $guild != '1') {
	
	echo '<p style="color: red;">Your inserted information did not lead to a guild or character.
	<br />Please check for special characters: ' .$charguild. ' (' .$region. '-' .$realm. ')
	<br />Potential character link: <a href="http://' .$region. '.battle.net/wow/en/character/' .$realm. '/' .$charguild. '/simple"><img src="i/wow.ico" alt="404" /></a> | <a href="http://www.wowprogress.com/character/' .$region. '/' .$realm. '/' .$charguild. '"><img src="i/wpr.ico" alt="404" /></a> | <a href="http://check.artifactpower.info/?c=' .$charguild. '&r=' .$region. '&s=' .$realm. '"><img src="i/aaa.png" alt="404" /></a>
	<br />Potential guild link: <a href="http://' .$region. '.battle.net/wow/en/guild/' .$realm. '/' .$charguild. '/"><img src="i/wow.ico" alt="404" /></a> | <a href="http://www.wowprogress.com/guild/' .$region. '/' .$realm. '/' .$charguild. '"><img src="i/wpr.ico" alt="404" /></a></p>';
}
mysqli_close();
?>