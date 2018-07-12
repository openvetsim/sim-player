<?php
	require_once('init.php');
	
	// delete session data
	$status = adminClass::isUserLoggedIn();
	if($status === FALSE) {
		header('location: index.php');
	}
	
	$userRow = adminClass::getUserRowFromSession();
	$userName = $userRow['UserFirstName'] . " " . $userRow['UserLastName'];
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require_once(SERVER_INCLUDES . "header.php"); ?>
		
		<script type="text/javascript">
			$(document).ready(function() {
				// init menu
				menu.init();
				
				// show log files
				modal.showLogFiles();
				
				$('#admin-nav').css({
					'width': '700px',
					'margin-bottom': '20px'
				});
				$('.profile-display.scenario').css('width', '340px');
				
				$('#sitewrapper').css('margin', '0');
				var wWidth = $('body').width();
					$('#event-log').width(wWidth - 75);
				$( window ).resize(function() {
					var wWidth = $('body').width();
					$('#event-log').width(wWidth - 75);
				});
				
			});
		</script>
	</head>
	<body>
		<div id="sitewrapper">
			<div id="admin-nav">
				<h1>Open VetSim Debrief Viewer</h1>
				<h1 class="welcome-title">Welcome <?= $userName; ?></h1>
				<div class="profile-display scenario">
					<!-- Log File Name: -->
					<span id="scenario-name-display"></span>
				</div>
				<ul id="main-nav">
					<!-- <li class="with-sub-nav">
						<a href="javascript:void(2);">File</a>
						<ul class="sub-nav">
							<li><a href="javascript: void(2);">File Another Item</a></li>
							<li><a href="javascript: void(2);">File Another Item</a></li>
							<li><a href="javascript: void(2);">File Another Item</a></li>
							<li><a href="javascript: void(2);">File Another Item</a></li>
						</ul>
					</li>
					<li class="with-sub-nav">
						<a href="javascript:void(2);">Settings</a>
						<ul class="sub-nav">
							<li><a href="javascript: void(2);">Settings Another Item</a></li>
							<li><a href="javascript: void(2);">Settings Another Item</a></li>
							<li><a href="javascript: void(2);">Settings Another Item</a></li>
							<li><a href="javascript: void(2);">Settings Another Item</a></li>
						</ul>
					</li> -->

					<li>
						<a href="javascript:void(2);" onclick="modal.showLogFiles();">Log Files</a>
					</li>
					<li class="debrief">
						<a href="/sim-ii/ii.php" class="event-link">Instructor Interface</a>						
					</li>

					<li class="logout">
						Version: <?= VERSION_MAJOR . '.' . VERSION_MINOR; ?>						
					</li>
					<li class="logout">
						<a href="index.php" class="event-link">Logout</a>						
					</li>
				</ul>
			</div>
			
			<div id="event-log"></div>
			<div id="current-time" style="clear: both; float: left;"></div>
						
			<div class="clearer"></div>
		</div> <!-- sitewrapper -->
		
		<!-- Modal -->
		<div id="modal">
			<div class="container">
				<div id="modal-content">
				</div>
				<a class="close_modal" href="javascript: void(2);">
					<img src="<?= BROWSER_IMAGES; ?>x.png" alt="Close Modal">
				</a>
			</div>
		</div>
	</body>
</html>