<?php
	ini_set('display_errors', 'On');
	error_reporting(E_ALL | E_STRICT);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Player</title>
		<script
			  src="http://code.jquery.com/jquery-3.3.1.min.js"
			  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
			  crossorigin="anonymous"></script>
		<script src="js/vwin.js"></script>
<script>
var vWin = new vWindow();

<?php
	$flist = array();
	$newest = 0;
	$newestDay = "";
	$newestTime = "";
	$date = "";
	if ( $handle = opendir('.'))
	{
		while ( false !== ($file = readdir($handle)))
		{
			if ( $file != "." && $file != "..")
			{
				$parts = explode("_", $file );
				//printf("// %s    %d\n", $file, count($parts) );
				
				if ( count($parts) == 3 ) 
				{
					$time = strtotime($parts[0].' '.$parts[1]);
					if ( $time > $newest )
					{
						$newest = $time;
						$newestDay = $parts[0];
						$newestTime = $parts[1];
					}
				} 
			}
		}
		closedir($handle);
		
		printf("var date=\"%s_%s\";\n", $newestDay, $newestTime  );
		$date = sprintf("%s_%s", $newestDay, $newestTime  );
		
		// Create the file list
		$handle = opendir('.');
		while ( false !== ($file = readdir($handle)))
		{
			if ( $file != "." && $file != "..")
			{
				if ( strncmp($date, $file, strlen($date) ) == 0 )
				{
					$flist[] = $file;
					printf("// %s    %d\n", $file, count($parts) );
				}
			}
		}
		closedir($handle);
	}
	else
	{
		printf("var date=\"\";\n" );
	}
	printf(" var files = {\n" );
	$acount = 0;
	$vcount = 0;
	foreach ( $flist as $file )
	{
		$parts = explode(".", $file );
		if ( count($parts) == 2 )
		{
			$ext = $parts[1];
			$parts = explode("_", $parts[0] );
			$id = $parts[2];
			if ( $ext == 'mp4' )
			{
				if ( $id == 'scr' )
				{
					printf("  'screen':'%s',\n", $file );
				}
				else if ( $id[0] == 'v' )
				{
					$vcount++;
					printf("  'v_%d' : '%s',\n", $vcount, $file );
				}
			}
			else if ( $ext == 'mp3' )
			{
				if ( $id[0] == 'a' )
				{
					$acount++;
					printf("  'a_%d' : '%s',\n", $acount, $file );
				}
			}
		}
	}
	printf(" 'end': '0' };\n" );
?>

	$(document).ready(function() 
	{
		console.log(date );
		$.each(files, function(key, filename ) {
			if ( key == 'screen' )
			{
				console.log("Video", key, filename );
				vWin.openWindow(640,480,filename);
			}
			if ( key.charAt(0) == 'v' )
			{
				console.log("Video", key, filename );
				vWin.openWindow(640,480,filename);
			}
			else if ( key.charAt(0) == 'a' )
			{
				console.log("Audio", key, filename );
				
				$('#audioBlocks').append(
'<audio id="audio-'+key+'" class="audioPlayer" width=100% height="auto" controls >'+
'<source src="'+filename+'" type="audio/mp3" controls >'+
'</audio><br>'
);
				var num = key.substr(2 );
				$('#audioBlocks').append("<button class='muteAudio' id='muteAudio-"+num+"'>Toggle Mute Audio "+num+"</button><br>");
			}
		});

		setTimeout(function(){
			vWin.connectVideo(0 );
		}, 1000 );
		
		$('#startPlay').click(function() {
			console.log("Start" );
			vWin.playAll();
		});
		$('#jumpContent').click(function() {
			vWin.pauseAll();
			vWin.seekAll(10);
			vWin.playAll();
		});
		
		$('.muteAudio').click(function() {
			var obj = $(this);
			var idParts = $(obj).attr('id' ).split('-');
			var which = idParts[1];
			vWin.mute_toggle(which);
		});
		$('#pauseVideo').click(function() {
			vWin.pauseAll();
		});
	});
		</script>
	</head>

	<body>
		<p>The content of the document......</p>
		
		<button id="startPlay">Start</button>
		<button id="pauseVideo">Pause</button>
		<button id="jumpContent">Jump</button><br>
		<br>

		<div id='audioBlocks'></div>
	</body>
</html> 
	