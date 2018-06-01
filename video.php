<?php
	require_once('init.php');
	
	// get filename
	$videoSrc = dbClass::valuesFromGet('url');
	
	// does file exist
	if($videoSrc == "" || !file_exists(SERVER_SIM_VIDEO . $videoSrc) ) {
		$status = MISSING_VIDEO;
	} else {
		$status = VIDEO_FOUND;
	}
	
	$videoURL = BROWSER_VIDEO . $videoSrc;
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require_once(SERVER_INCLUDES . "header.php"); ?>
		<script type="text/javascript">
			$(document).ready(function() {

			});
		</script>
	</head>
	<body>
		<div id="video-div">
			<video id="video-player" width=100% height="auto" controls>
				<source src="<?= $videoURL; ?>" type="video/mp4">
			</video>
		</div>
	</body>
</html>