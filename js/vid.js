	var vid = {
		status: Object.freeze({
			"INIT": 1, "READY": 2, "PLAYING": 3, "PAUSED": 4, "ENDED": 5, "INVALID": 6
			}),								// enum status for video window
		currentStatus: this.status.INIT,	// current status
		vidObj: new Object,					// object for video
		winObj: new Object,					// object for video window
		timer: 0,							// interval timer variable
		currentTime: 0,						// current time of video
		url: '',							// url of currently opened video file
		initCounter: 0,						// counter to timeout attempted inits for video (10 seconds)
		
		init: function() {
			if(vid.url != '') {
				vid.winObj = window.open(
									BROWSER_ROOT_FULL + "sim-player/video.php?url=" + vid.url,
									"Video Player", 
									"location=0,status=0,scrollbars=0,width=640,height=480"
								);
			} else {
				clearInterval(vid.timer);
				return;
			}
			
			vid.currentStatus = vid.status.INIT;
			vid.initCounter = 0;
			
			// init timer
			clearInterval(vid.timer);
			
			vid.timer = setInterval(function() {
				if(vid.currentStatus == vid.status.INIT) {
					vid.vidObj = vid.winObj.document.getElementById("video-player");
					if(vid.vidObj !== null) {
						vid.currentStatus = vid.status.READY;
						
						// bind events
						$('#event-log table tr').click(function() {
							vid.vidObj.currentTime = $(this).attr('data-ts');
						});
					}
					
					vid.initCounter++;
					if(vid.initCounter == 10) {
						
					}
				} else if(vid.currentStatus == vid.status.READY) {
					currentTime = Math.ceil(vid.vidObj.currentTime);
					$('#current-time').html("Current video at " + currentTime + " sec.");
				
					// clear out formatting for events
					$('#event-log table tr').removeClass("selected");
					$("#event-log table tr").filter(function() {
						return $(this).data('ts') <= currentTime;
					}).last().addClass("selected");
					
					// has video been placed at the end?
					if(vid.vidObj.ended) {
						vid.currentStatus = vid.status.ENDED;
					}

					// has video started?
					if(!vid.vidObj.paused) {
						vid.currentStatus = vid.status.PLAYING;
					}
				} else if(vid.currentStatus == vid.status.PLAYING) {
					currentTime = Math.ceil(vid.vidObj.currentTime);
					$('#current-time').html("Current video at " + currentTime + " sec.");
				
					// clear out formatting for events
					$('#event-log table tr').removeClass("selected");
					$("#event-log table tr").filter(function() {
						return $(this).data('ts') <= currentTime;
					}).last().addClass("selected");
					
					// have we ended?
					if(vid.vidObj.ended) {
						vid.currentStatus = vid.status.ENDED;
					}
				} else if(vid.currentStatus == vid.status.ENDED) {
					$('#event-log table tr').removeClass("selected");
					$("#event-log table tr").last().addClass("selected");

					// has timeline been moved off of end?
					if(!vid.vidObj.ended) {
						vid.currentStatus = vid.status.READY;
					}
					
				}
			}, 1000);
			
			// close window if page is closed or refreshed
			$(window).on('beforeunload', function(){
				vid.winObj.close();
			});
		}
	}