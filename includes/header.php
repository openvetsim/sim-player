		<meta charset="UTF-8">
		<title>Open VetSim Debrief Viewer</title>
		<link rel="shortcut icon" href="favicon.ico" />		

<?php
		if(MOBILIZED) {
			echo '<link rel="stylesheet" href="' . BROWSER_CSS . 'jquery.mobile-1.4.5.min.css" />';
			echo '<link rel="stylesheet" href="' . BROWSER_CSS . 'mobilize.css" />';
		}
?>

		<link rel="stylesheet" href="<?= BROWSER_CSS; ?>common.css" type="text/css" />
		<link rel="stylesheet" href="scripts/jquery-ui/1.11.4/jquery-ui.smoothness.min.css">
		<link rel="stylesheet" href="<?= BROWSER_CSS; ?>modal.css" type="text/css" />
		
		<?php
			// php defines in JS
			require_once(SERVER_INCLUDES."phpDefinesToJs.php");
			$ts = date("U");
		?>

		<script type="text/javascript" src="scripts/jquery/2.2.1/jquery.min.js"></script>
		<script src="scripts/jquery-ui/1.11.4/jquery-ui.js"></script>
<?php
		if(MOBILIZED) {
			echo '<script src="scripts/jquery.mobile.custom.min.js"></script>';
		}
?>
		
		<script type="text/javascript" src="<?= BROWSER_SCRIPTS; ?>menu.js?v=<?= $ts ?>"></script>
		<script type="text/javascript" src="<?= BROWSER_SCRIPTS; ?>modal.js?v=<?= $ts ?>"></script>
		<script type="text/javascript" src="<?= BROWSER_SCRIPTS; ?>user.js?v=<?= $ts ?>"></script>
		<script type="text/javascript" src="<?= BROWSER_SCRIPTS; ?>eventLog.js?v=<?= $ts ?>"></script>
		<script type="text/javascript" src="<?= BROWSER_SCRIPTS; ?>vid.js?v=<?= $ts ?>"></script>		