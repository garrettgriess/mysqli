<?
function mysql_insert($table, $insert) {
	if (!$table||!is_array($insert)) {
		$return = "Error: Wrong Input";
	} else {
		$creds=$GLOBALS['mysql_creds'];
		$conn = mysqli_connect($creds['dbhost'], $creds['dbuser'], $creds['dbpass'], $creds['dbname']);
		if (!$conn) {
			$return = "Error: ".mysqli_connect_error();
		} else {
			$len = count($insert);
			$sql = "INSERT INTO `".$table."` (";
			$i = 0;
			foreach ($insert as $key => $val) {
				$sql.= "`".$key."`";
				if (++$i !== $len) {$sql.= ",";}
			}
			$sql.=") VALUES (";
			$i = 0;
			foreach ($insert as $key => $val) {
				$sql.= "'".$val."'";
				if (++$i !== $len) {$sql.= ",";}
			}
			$sql.=")";
			if (mysqli_query($conn, $sql)) {
				$return = "OK";
			} else {
				$return = "Error: ".mysqli_error($conn);
			}
		}
		mysqli_close($conn);
	}
	return $return;
}

function mysql_update($table, $update, $where, $limit=1) {
	if (!$table||!is_array($update)||!$where) {
		$return = "Error: Wrong Input";
	} else {
		$creds=$GLOBALS['mysql_creds'];
		$conn = mysqli_connect($creds['dbhost'], $creds['dbuser'], $creds['dbpass'], $creds['dbname']);
		if (!$conn) {
			$return = "Error: ".mysqli_connect_error();
		} else {
			$len = count($update);
			$sql = "UPDATE `".$table."` SET ";
			$i = 0;
			foreach ($update as $key => $val) {
				$sql.= "`".$key."` = '".$val."'";
				if (++$i !== $len) {$sql.= ",";}
			}
			$sql.=" WHERE ".$where;
			if ($limit!=null) {$sql.= " LIMIT ".$limit;}
			if (mysqli_query($conn, $sql)) {
				$return = "OK";
			} else {
				$return = "Error: ".mysqli_error($conn);
			}
		}
		mysqli_close($conn);
	}
	return $return;
}

function mysql_delete($table, $where, $limit=1) {
	if (!$table||!$where) {
		$return = "Error: Wrong Input";
	} else {
		$creds=$GLOBALS['mysql_creds'];
		$conn = mysqli_connect($creds['dbhost'], $creds['dbuser'], $creds['dbpass'], $creds['dbname']);
		if (!$conn) {
			$return = "Error: ".mysqli_connect_error();
		} else {
			$sql = "DELETE FROM `".$table."`";
			$sql.=" WHERE ".$where;
			if ($limit!=null) {$sql.= " LIMIT ".$limit;}
			if (mysqli_query($conn, $sql)) {
				$return = "OK";
			} else {
				$return = "Error: ".mysqli_error($conn);
			}
		}
		mysqli_close($conn);
	}
	return $return;
}

function mysql_select($table, $select, $where=null, $order=null, $limit=null) {
	if (!$table||(!is_array($select)&&$select!="*")) {
		$return = "Error: Wrong Input";
	} else {
		$creds=$GLOBALS['mysql_creds'];
		$conn = mysqli_connect($creds['dbhost'], $creds['dbuser'], $creds['dbpass'], $creds['dbname']);
		if (!$conn) {
			$return = "Error: ".mysqli_connect_error();
		} else {
			$len = count($select);
			$sql = "SELECT ";
			if ($select=="*") {
				$sql.= "*";
			} else {
				$i = 0;
				foreach ($select as $val) {
					if (strpos($val," AS ")) { //Special Cases
						$sql.= $val;
					} else {
						$sql.= "`".$val."`";
					}
					if (++$i !== $len) {$sql.= ",";}
				}
			}
			$sql.= " FROM `".$table."`";
			if ($where!=null) {$sql.= " WHERE ".$where;}
			if ($order!=null) {$sql.= " ORDER BY ".$order;}
			if ($limit!=null) {$sql.= " LIMIT ".$limit;}
			$result = mysqli_query($conn, $sql);
			if ($result) {
				$return=array();
				if (mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_assoc($result)) {
						$return[] = $row;
					}
				}
			} else {
				$return = "Error: ".mysqli_error($conn);
			}
		}
		mysqli_close($conn);
	}
	return $return;
}
?>