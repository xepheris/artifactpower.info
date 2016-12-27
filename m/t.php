<?php
require_once('d.php');

$apsum = mysqli_fetch_array(mysqli_query($stream, "SELECT SUM(`ap`) AS `sum` FROM `ap`"));
$apsum = $apsum['sum'];

$usersum = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`c`) AS `sum` FROM `ap`"));
$usersum = $usersum['sum'];

$mythic10sum = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `sum` FROM `ap` WHERE `mp` >= '10' AND `ie` >= '830'"));
$mythic10sum = $mythic10sum['sum'];

$mythic15sum = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `sum` FROM `ap` WHERE `mp` >= '15' AND `ie` >= '860'"));
$mythic15sum = $mythic15sum['sum'];

echo 'Total AP collected: ' .number_format($apsum). ' || Current users: ' .number_format($usersum). '<br />
~' .(round($mythic10sum/$usersum*100, 3)). '% have Mythic+10 or higher cleared || ~' .(round($mythic15sum/$usersum*100, 3)). '% have Mythic+15 or higher cleared';

?>