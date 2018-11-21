<?php
	// init
	require_once("../init.php");
	$returnVal = array();

	// is user logged in
	if(adminClass::isUserLoggedIn() === FALSE) {
		$returnVal['status'] = AJAX_STATUS_LOGIN_FAIL;
		echo json_encode($returnVal);
		exit();
	}
	
	// get params
	$logFileName = dbClass::valuesFromPost('fn');

	if(file_exists(SERVER_SIM_LOGS . $logFileName) === FALSE) {
		$returnVal['status'] = AJAX_STATUS_FAIL;
		echo json_encode($returnVal);
		exit();
	}
	
	$logArray = file(SERVER_SIM_LOGS . $logFileName, FILE_IGNORE_NEW_LINES + FILE_SKIP_EMPTY_LINES);
//FB::log($logArray);

	if(count($logArray) == 0) {
		$returnVal['status'] = AJAX_STATUS_FAIL;
		echo json_encode($returnVal);
		exit();	
	}

	$content = '<table>
					<tr>
						<td class="event-header time align-center">Elapsed Time</td>
						<td class="event-header time align-center">Scenario Time</td>
						<td class="event-header time align-center">Scene Time</td>
						<td class="event-header align-center">Event Description</td>
					</tr>
	';
	
	$timeStampBase = strtotime("1/1/2000 0:0:0");
	
	foreach($logArray as $logRecord) {
		list($timeStamp, $scenarioTime, $sceneTime, $event) = explode(" ", $logRecord, 4);
		$eventTimeStamp = strtotime('1/1/2000 ' . $timeStamp) - $timeStampBase;

		$content .= '
			<tr class="event-content-row" data-ts="' . $eventTimeStamp . '">
				<td class="time-stamp event-content align-center">' . $timeStamp . '</td>
				<td class="time-stamp event-content align-center">' . $scenarioTime . '</td>
				<td class="time-stamp event-content align-center">' . $sceneTime . '</td>
				<td class="event event-content">' . $event . '</td>
			</tr>
		';
	}
	$content .= '</table>';

	$returnVal['status'] = AJAX_STATUS_OK;
	$returnVal['html'] = $content;
	echo json_encode($returnVal);
	exit();
?>