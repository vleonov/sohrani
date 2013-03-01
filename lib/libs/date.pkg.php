<?

/*
 *
 */
function ifdate($date) {
	$result = false;
	$test = preg_match(DATE_FORMAT, $date);
	if ($test) {
		$result = true;
	}
	return $result;

} // end checkdate()

/*
 *
 */
function gdate($date) {

	$result = false;

	$date = sdate($date);

	if (is_array($date)) {
		$result = $date['date_ymd'] . ' ' . $date['time'] . ':00';
	}

	return $result;

} // end gdate()

/*
 *
 */
function sdate($date) {
	$result = false;
	$test = preg_match(DATE_FORMAT, $date, $match);
	if ($test) {
		$day	= $match[1];
		$month	= $match[2];
		$year	= $match[3];
		$hours	= $match[4];
		$mins	= $match[5];

		$result = array(
			'd' => $day,
			'm' => $month,
			'y' => $year,
			'hours' => $hours,
			'mins' => $mins,
			'date_ymd' => $year . '-' . $month . '-' . $day,
			'date' => $day . '.' . $month . '.' . $year, 
			'time' => $hours . ':'  . $mins,
		);
	}
	return $result;

} // end sdate()

?>