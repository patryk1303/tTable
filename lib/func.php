<?php

function check_minute($hour, $minute) {
	$status = "";
	$current_time = time();
	$next_hour_time = time() + 3600;
	$c_time = strtotime("$hour:$minute");
	if ($current_time > $c_time) {
		$status .= " passed";
	} elseif ($next_hour_time > $c_time) {
		$status .= " next";
	}
	return $status;
}

function check_cookie($name,$val) {
	if(!isset($_COOKIE[$name])) {
		$_COOKIE[$name] = $val;
		return $val;
	} else {
		return $_COOKIE[$name];
	}
}