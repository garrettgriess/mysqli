<?
/*
	Example of php/mySql library.
	Non-functional.
*/

require('inc/vars.php');
require('inc/mysql_func.php');

if ($_GET['lat']&&$_GET['lon']) {

	//Insert in to Database
	$mysql_insert = array(
		'lat' => $_GET['lat'],
		'lon' => $_GET['lon'],
		'speed' => $_GET['speed'],
		'heading' => $_GET['heading'],
		'vacc' => $_GET['vacc'],
		'hacc' => $_GET['hacc'],
		'altitude' => $_GET['altitude'],
		'deviceid' => $_GET['deviceid'],
		'battery' => $_GET['battery'],
		'ts' => $_GET['ts'],
		'quick' => $_GET['quick'],
	);
	$mysql_results = mysql_insert("table_name",$mysql_insert);
	if ($mysql_results!="OK") {
		echo $mysql_results;
	} else {
		echo "Inserted ".date("g:i:s a");
	}
	
} else {
	
	//Select Database
	$mysql_select = array('ts','lat','lon','speed','heading','vacc','hacc','altitude','battery');
	$mysql_results = mysql_select("table_name",$mysql_select,null,'`ts` DESC',5);
	if (!is_array($mysql_results)) {
		echo $mysql_results;
	} else {
		$num = count($mysql_results);
		
		//Display Table
		if ($num>0) {
			?><table><?
				?><tr><?
					foreach ($mysql_select as $val) {
						?><th><?=$val?></th><?
					}
				?></tr><?
			for ($i=0;$i<$num;$i++) {
				?><tr><?
					foreach ($mysql_select as $val) {
						if ($val=="speed"||$val=="heading"||$val=="vacc"||$val=="hacc") {
							$mysql_results[$i][$val]=round($mysql_results[$i][$val]);
						}
						?><td><?=$mysql_results[$i][$val]?></td><?
					}
				?></tr><?
			}
			?></table><?
		}
		
	}
	
}

//Update Test
$mysql_update = array(
	'deviceid' => "Test",
	'quick' => "test",
);
$mysql_results = mysql_update("table_name",$mysql_update,"`id`='3'");
if ($mysql_results!="OK") {
	echo $mysql_results;
} else {
	echo "Updated.";
}

//Delete Test
$mysql_results = mysql_delete("table_name","`id`='3'");
if ($mysql_results!="OK") {
	echo $mysql_results;
} else {
	echo "Deleted.";
}
?>