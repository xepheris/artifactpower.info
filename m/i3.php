<?php
require_once('d.php');

if(isset($_GET['r']) && isset($_GET['s']) && isset($_GET['c'])) {
	$char = $_GET['c'];
	$realm = $_GET['s'];
	$region = $_GET['r'];
}
elseif(!isset($_GET['r']) && !isset($_GET['s']) && !isset($_GET['c'])) {
	$var = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `q` ORDER BY RAND() LIMIT 1"));
	$char = $var['c'];
	$realm = $var['s'];
	$region = $var['r'];
}

if(strpos($realm, ' ') !== false) {
	$realm = str_replace(' ', '-', $realm);
}
		
// ENABLE SSL
$arrContextOptions=array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, ),);  
	
$url = 'https://' .$region. '.api.battle.net/wow/character/' .$realm. '/' .$char. '?fields=items,statistics,achievements,talents&locale=en_GB&apikey=KEY_HERE';

$data = @file_get_contents($url, false, stream_context_create($arrContextOptions));
if($data != '') {
		
	$data = json_decode($data, true);
	
	if($data['level'] > '100') {
					
		// CLASS INFORMATION
		$class = $data['class'];
			
		// SPECIALIZATION NAME
		for($i = '0'; $i <= '4'; $i++) {
			if($specc == '') {
				if($data['talents'][$i]['selected'] == '1') {
					for($k = '0'; $k <= '7'; $k++) {					
						if(isset($data['talents'][$i]['talents'][$k]['spec']['name'])) {
							$specc = $data['talents'][$i]['talents'][$k]['spec']['name'];
						}
					}
				}
				if($specc == '') {
					$specc = $data['talents'][$i]['spec']['name'];
				}
			}
		}
		if($specc == '') {
			$specc = $data['talents'][$i]['spec']['name'];
		}
		
		// EQUIPPED ITEMLEVEL						
		$ie = $data['items']['averageItemLevelEquipped'];
					
		// BAG ITEMLEVEL						
		$ib = $data['items']['averageItemLevel'];
		
		// RAID PROGRESS MYTHIC
		$en = '0';					
		$enarray = array($data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['33']['quantity'], $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['37']['quantity'], $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['41']['quantity'], $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['45']['quantity'], $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['49']['quantity'], $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['53']['quantity'], $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['57']['quantity']);
					
		foreach($enarray as $enmythic) {
			if($enmythic > '0') {
				$en++;
			}
		}
			
		$tov = '0';					
		$tovarray = array($data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['61']['quantity'], $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['65']['quantity'], $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['69']['quantity']);
					
		foreach($tovarray as $tovmythic) {
			if($tovmythic > '0') {
				$tov++;
			}
		}
					
		$nh = '0';
		$nharray = array($data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['73']['quantity'], $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['77']['quantity'], $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['81']['quantity'], $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['85']['quantity'], $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['89']['quantity'], $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['93']['quantity'], $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['97']['quantity'], $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['101']['quantity'], $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['105']['quantity'], $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['109']['quantity']);
					
		foreach($nharray as $nhmythic) {
			if($nhmythic > '0') {
				$nh++;
			}
		}
					
		// MYTHIC AND(!! it's NOT seperatable) MYTHIC PLUS STATS
		$arc = $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['27']['quantity'];
		$brh = $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['23']['quantity'];
		$cen = '0';
		$cos = $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['28']['quantity'];
		$dht = $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['5']['quantity'];
		$eoa = $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['2']['quantity'];
		$hov = $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['11']['quantity'];
		$lkz = '0';
		$ukz = '0';
		$mos = $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['26']['quantity'];
		$nel = $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['8']['quantity'];
		$vh1 = $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['16']['quantity'];
		$vh2 = $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['17']['quantity'];
		$vh = $vh1+$vh2;
		$vow = $data['statistics']['subCategories']['5']['subCategories']['6']['statistics']['20']['quantity'];
				
		$mythicsum = $eoa+$dht+$nel+$hov+$vh+$vow+$brh+$mos+$arc+$cos+$lkz+$ukz+$cen;
				
		// ARTIFACT POWER AND LEVEL - their position is INDIVIDUAL as I have been asked how to find these several times now
		$key = array_search('30103', $data['achievements']['criteria']);
		$key2 = array_search('29395', $data['achievements']['criteria']);
		$key3 = array_search('31466', $data['achievements']['criteria']);
		
		if($key != '') {
			$criterias = array();
			array_push($criterias, $data['achievements']['criteriaQuantity']);
			$criterias = $criterias['0'];
			$ap = $criterias[$key];
			$al = $criterias[$key2];
			$ak = $criterias[$key3];
		}
					
		if(in_array('11162', $data['achievements']['achievementsCompleted'])) {
			$mplus = '15';
		}	
		elseif(in_array('11185', $data['achievements']['achievementsCompleted'])) {
			$mplus = '10';
		}
		elseif(in_array('11184', $data['achievements']['achievementsCompleted'])) {
			$mplus = '5';
		}
		elseif(in_array('11183', $data['achievements']['achievementsCompleted'])) {
			$mplus = '2';
		}
		
		if(strpos($realm, '-') !== false) {
			$realm = str_replace('-', ' ', $realm);
		}
		
		$lastupdate = substr($data['lastModified'], '0', '10');
		// CHECK FOR LAST UPDATE
		$old = mysqli_fetch_array(mysqli_query($stream, "SELECT `lo` FROM `ap` WHERE `c` = '" .$char. "' AND `r` = '" .$region. "' AND `s` = '" .addslashes($realm). "'"));
		
		// NEW ENTRY IF OLD = ''
		if($old == '') {
			$sql =  "INSERT INTO `ap` (`c`, `r`, `s`, `cl`, `sp`, `ap`, `al`, `ie`, `ib`, `arc`, `brh`, `cen`, `cos`, `dht`, `eoa`, `hov`, `lkz`, `ukz`, `mos`, `nel`, `vh`, `vow`, `m`, `mp`, `en`, `tov`, `nh`, `lu`, `lo`) VALUES ('" .$char. "', '" .$region. "', '" .addslashes($realm). "', '" .$class. "', '" .$specc. "', '" .$ap. "', '" .$al. "', '" .$ie. "', '" .$ib. "', '" .$arc. "', '" .$brh. "', '" .$cen. "', '" .$cos. "', '" .$dht. "', '" .$eoa. "', '" .$hov. "', '" .$lkz. "', '" .$ukz. "', '" .$mos. "', '" .$nel. "', '" .$vh. "', '" .$vow. "', '" .$mythicsum. "', '" .$mplus. "', '" .$en. "', '" .$tov. "', '" .$nh. "', '" .time('now'). "', '" .$lastupdate. "'); ";
			
			echo $sql;
			$insert = mysqli_query($stream, $sql);
			if($insert) {
				if(strpos($realm, ' ') !== false) {
					$realm = str_replace(' ', '-', $realm);
				}
				mysqli_query($stream, "DELETE FROM `q` WHERE `c` = '" .$char. "' AND `r` = '" .$region. "' AND `s` = '" .addslashes($realm). "'");
			}			
		}
		
		// IF HAS BEEN MODIFIED SINCE LAST IMPORT
		elseif($old['lo'] < substr($data['lastModified'], '0', '10')) {
			$compare = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `ap` WHERE `c` = '" .$char. "' AND `r` = '" .$region. "' AND `s` = '" .addslashes($realm). "'"));
			
			$sql = "UPDATE `ap` SET ";
			
			$comparearray = array('ap' => $ap, 'al' => $al, 'sp' => $specc, 'ie' => $ie, 'ib' => $ib, 'arc' => $arc, 'brh' => $brh, 'cen' => $cen, 'cos' => $cos, 'dht' => $dht, 'eoa' => $eoa, 'hov' => $hov, 'lkz' => $lkz, 'ukz' => $ukz, 'mos' => $mos, 'nel' => $nel, 'vh' => $vh, 'vow' => $vow, 'm' => $mythicsum, 'mp' => $mplus, 'en' => $en, 'tov' => $tov, 'nh' => $nh, 'lo' => $lastupdate);
			foreach($comparearray as $var => $oldvar) {
				if($compare['' .$var. ''] != $oldvar) {
					$sql.="`" .$var. "` = '" .$oldvar. "', ";
				}
			}
			
			$sql.= "`lu` = '" .time('now'). "' WHERE `c` = '" .$char. "' AND `r` = '" .$region. "' AND `s` = '" .addslashes($realm). "'";
			
			echo $sql;
			
			$very_old = mysqli_fetch_array(mysqli_query($stream, "SELECT `lo` FROM `ap_past` WHERE `c` = '" .$char. "' AND `r` = '" .$region. "' AND `s` = '" .addslashes($realm). "'"));
			if($very_old != '') {
				$compare_real_old = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `ap_past` WHERE `c` = '" .$char. "' AND `r` = '" .$region. "' AND `s` = '" .addslashes($realm). "'"));
				$compare_current = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `ap` WHERE `c` = '" .$char. "' AND `r` = '" .$region. "' AND `s` = '" .addslashes($realm). "'"));
				$update_real_old = "UPDATE `ap_past` SET";
				
				$comparearray = array('ap' => $compare_current['ap'], 'al' => $compare_current['al'], 'sp' => $compare_current['sp'], 'ie' => $compare_current['ie'], 'ib' => $compare_current['ib'], 'arc' => $compare_current['arc'], 'brh' => $compare_current['brh'], 'cen' => $compare_current['cen'], 'cos' => $compare_current['cos'], 'dht' => $compare_current['dht'], 'eoa' => $compare_current['eoa'], 'hov' => $compare_current['hov'], 'lkz' => $compare_current['lkz'], 'ukz' => $compare_current['ukz'], 'mos' => $compare_current['mos'], 'nel' => $compare_current['nel'], 'vh' => $compare_current['vh'], 'vow' => $compare_current['vow'], 'm' => $compare_current['m'], 'mp' => $compare_current['mp'], 'en' => $compare_current['en'], 'tov' => $compare_current['tov'], 'nh' => $compare_current['nh'], 'lo' => $compare_current['lo']);
				foreach($comparearray as $var => $oldvar) {
					if($compare_real_old['' .$var. ''] != $oldvar) {
						$update_real_old.="`" .$var. "` = '" .$oldvar. "', ";
					}
				}
				$update_real_old.= "`lu` = '" .time('now'). "' WHERE `c` = '" .$char. "' AND `r` = '" .$region. "' AND `s` = '" .addslashes($realm). "'";
				
				echo '<br />' .$update_real_old. '';
				
				$update_backup = mysqli_query($stream, $update_real_old);				
			}
			elseif($very_old == '') {
				// INSERT NEW BACKUP
				$move_to_old = mysqli_query($stream, "INSERT INTO `ap_past` SELECT * FROM `ap` WHERE `c` = '" .$char. "' AND `r` = '" .$region. "' AND `s` = '" .addslashes($realm). "'");
			}		
						
			$insert = mysqli_query($stream, $sql);
			if($insert) {
				if(strpos($realm, ' ') !== false) {
					$realm = str_replace(' ', '-', $realm);
				}
				mysqli_query($stream, "DELETE FROM `q` WHERE `c` = '" .$char. "' AND `r` = '" .$region. "' AND `s` = '" .addslashes($realm). "'");
			}
		}
		// IF ENTRY IS NOT NEWER
		elseif($old['lo'] == substr($data['lastModified'], '0', '10')) {
			// UPDATE TIMESTAMP
			$updtimestamp = mysqli_query($stream, "UPDATE `ap` SET `lu` = '" .time('now'). "' WHERE `c` = '" .$char. "' AND `r` = '" .$region. "' AND `s` = '" .addslashes($realm). "'");
			echo '<p style="color: red;">Removing ' .$char. ' (' .$region. '-' .$realm. ') from queue because update is not necessary.</p>';
			if(strpos($realm, ' ') !== false) {
				$realm = str_replace(' ', '-', $realm);
			}
			mysqli_query($stream, "DELETE FROM `q` WHERE `c` = '" .$char. "' AND `r` = '" .$region. "' AND `s` = '" .addslashes($realm). "'");
		}
	}
	// IF CHARACTER IS <= 100
	elseif($data['level'] <= '100') {
		if(strpos($realm, '-') !== false) {
			$realm = str_replace('-', ' ', $realm);
		}
		echo '<p style="color: red;">' .$char. ' (' .$region. '-' .$realm. ') is not 101 or higher and thus not eligible to be looked up here.</p>';
		if(strpos($realm, ' ') !== false) {
			$realm = str_replace(' ', '-', $realm);
		}
		mysqli_query($stream, "DELETE FROM `q` WHERE `c` = '". $char. "' AND `r` = '" .$region. "' AND `s` = '" .addslashes($realm). "'");
	}
}
// IF CHARACTER DOES NOT EXIST
elseif($data == '') {
	if(strpos($realm, '-') !== false) {
		$realm = str_replace('-', ' ', $realm);
	}
	echo '<p style="color: red;">' .$char. ' (' .$region. '-' .$realm. ') transferred, renamed or became inactive.</p>';
	if(strpos($realm, ' ') !== false) {
		$realm = str_replace(' ', '-', $realm);
	}
	mysqli_query($stream, "DELETE FROM `q` WHERE `c` = '". $char. "' AND `r` = '" .$region. "' AND `s` = '" .addslashes($realm). "'");
}
	echo '<meta http-equiv="refresh" content="1;url=http://artifactpower.info/m/i3.php" />';

mysqli_close();	

?>