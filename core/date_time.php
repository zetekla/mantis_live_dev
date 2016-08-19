<?php
	// this file exists in the directory as it featured in Seriscan plugin.
	// by default, getDatetime() returns Los Angeles time, for Europe time, use 'Europe/Helsinki' as a payload: e.g. getDatetime('Europe/Paris')
	function getDateTime($timezone='America/Los_Angeles'){
		date_default_timezone_set($timezone);
		if (strpos($timezone, 'America') !== false)
			return date('m/d/Y h:i:s a', time());
		else return date('d/m/Y h:i:s a', time());
	}
	function getDateTime2($timezone='America/Los_Angeles'){
		date_default_timezone_set($timezone);
		return date('Y-m-d H:i:s', time());
	}