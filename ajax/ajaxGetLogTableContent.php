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
	
	// get selected file name
	$currentLogFile = dbClass::valuesFromPost('fn');
	
	// get list of log files
	$fileNameArray = scandir(SERVER_SIM_LOGS);

	// parse file list
	$fileList = array();
	if(count($fileNameArray) > 0) {
		foreach($fileNameArray as $fileName) {
			if($fileName == '.' || $fileName == '..' || $fileName == 'archive' || $fileName == 'video') {
				continue;
			}
			
			$nameArray = explode('_', $fileName);
			$dateString = $nameArray[0] . ' ' . $nameArray[1];
			
			// get number of elements in name array
			$nameString = '';
			for($index = 2; $index < count($nameArray); $index++) {
				$nameString .= $nameArray[$index] . '-';
			}
			
			$fileList[] = array('name' => substr($nameString, 0, -5),
								'date' => $dateString,
								'orig' => $fileName
								);
		}
	}
	
	// generate content
	$content = '
		<h2 id="user-table-title">Found Log Files</h2>
		
		<table id="log-table-title" class="log-table">
			<tr>
				<td class="user-header col-200">Scenario Name</td>
				<td class="user-header col-200">Date</td>
				<td class="user-header col-300">Video File Found?</td>
			</tr>
		</table>
		
		<div id="log-table-content" class="log-table">
		<table class="log-table">
	';
	
	$currentFilePos = -1;
	$returnVal['currentFilePos'] = $currentFilePos;
	foreach($fileList as $fileInfo) {
		$currentFilePos++;
		$selectContent = '';
		if($fileInfo['orig'] == $currentLogFile) {
			$selectContent = ' class="selected" ';
			$returnVal['currentFilePos'] = $currentFilePos;
		}
		
		$videoFileName = substr($fileInfo['orig'], 0, -4) . '.mp4';

		// does video file exist?
		if( !file_exists(SERVER_SIM_VIDEO . $videoFileName) ) {
			$videoFileName = "Video file not found!";
		}
		
		$content .= '
			<tr data-filename="' . $fileInfo['orig'] . '" ' . $selectContent . '>
				<td class="col-200 log-name">' . $fileInfo['name'] . '</td>
				<td class="col-200">' . $fileInfo['date'] . '</td>
				<td class="col-300 log-video">' . $videoFileName . '</td>
			</tr>
		';
	}
	
	$content .= '
		</table>
		</div>
	';

	$returnVal['status'] = AJAX_STATUS_OK;
	$returnVal['html'] = $content;
	echo json_encode($returnVal);
	exit();
?>