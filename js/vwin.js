class vWindow {
	constructor()
	{
		this.next = 0;
		this.vObjs = new Array();
		this.aObjs = new Array();
		this.windowObjs = new Array();
	}
	checkReady(idx=0 )
	{
		var win = this.windowObjs[idx];
	}
	openWindow(width, height, filename)
	{
		this.next++;
		var idx = this.next;
		var winName = "ChildWindow"+this.next;
		var win = window.open("video.html?filename="+filename,winName, "location=0,status=0,scrollbars=0,width="+width+",height="+height);
					
		this.windowObjs[idx] = win;
		console.log("Opened ", idx, win );
		return ( idx );
	}
	connectVideo(idx=0)
	{
		var win;
		var obj;
		
		if ( idx != 0 )
		{
			win = this.windowObjs[idx]
			win.document.getElementById('target').innerHTML = 'Connected!';
			obj = this.windowObjs[idx].document.getElementsByClassName('videoPlayer');
			if ( typeof obj !== 'undefined' )
			{
				this.vObjs[idx] = obj;
			}
		}
		else
		{
			console.log("Connect All" );
			this.windowObjs.forEach( function(win, idx ){
				console.log(" Connect", idx, win );
				if ( idx > 0 )
				{
					win.document.getElementById('target').innerHTML = 'Connected!';
					obj = win.document.getElementsByClassName('videoPlayer');
					if ( typeof obj === 'undefined' )
					{
						console.log(" Connect not found" );
					}
					else
					{
						vWin.vObjs[idx] = obj;
					}
				}
			});
		}
	}
	
	playAll()
	{
		this.vObjs.forEach (function(obj)
		{
			obj[0].play();
		});
		$('.audioPlayer').each(function()
		{
			this.play();
		});
	}
	pauseAll()
	{
		this.vObjs.forEach (function(obj)
		{
			obj[0].pause();
		});
		$('.audioPlayer').each(function()
		{
			this.pause();
		});
	}

	seekAll(time=0 )
	{
		this.vObjs.forEach (function(obj)
		{
			obj[0].currentTime = time;
		});
		$('.audioPlayer').each(function()
		{
			this.currentTime = time;
		});
	}

	mute_toggle(idx )
	{
		if ( idx == 0 )
		{
			$('.audioPlayer').each(function()
			{
				if ( this.muted === true ) {
					this.muted = false;
				} else {
					this.muted = true;
				}
			});
		}
		else
		{
			$('#audio-a_'+idx).each(function()
			{
				if ( this.muted === true ) {
					this.muted = false;
				} else {
					this.muted = true;
				}
			});
		}
	}
}