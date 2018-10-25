function Player(playerIndex, shouldLoop){
	if(typeof(shouldLoop) === 'undefined') shouldLoop = false;

	// Load song data for this player
	var songData = JS.playlist[playerIndex];
	this.src = songData.filename;
	this.loop = shouldLoop;
	JS.players[playerIndex] = {
		musicid: songData.musicid,
		title: songData.title,
		artist: songData.artist,
		duration: songData.duration,
		seektime: 0,
		volume: 100,
		paused: true
	};

	// write HTML
	var playerHTML = '<div class="songrow" id="player'+playerIndex+'">';
		playerHTML += '<div class="slidertrack">';
			playerHTML += '<button class="playpausebtn"></button>';
			playerHTML += '<input class="seekslider" type="range" min="0" max="100" value="0" step="1">';
			playerHTML += '<div class="timebox">';
				playerHTML += '<span class="curtimetext">00:00</span> / <span class="durtimetext">00:00</span>';
			playerHTML += '</div>';
			playerHTML += '<button class="mutebtn"></button>';
			playerHTML += '<input class="volumeslider" type="range" min="0" max="100" value="100" step="1">';
			playerHTML += '<div class="title">'+JS.players[playerIndex].title+'</div>';
		playerHTML += '</div>';
	playerHTML += '</div> <!-- end songrow -->';
	$("#players").append(playerHTML);

	// Set player references
	JS.players[playerIndex]['playbtn'] = $("#player"+playerIndex+" .playbtn");
	JS.players[playerIndex]['mutebtn'] = $("#player"+playerIndex+" .mutebtn");
	JS.players[playerIndex]['seekslider'] = $("#player"+playerIndex+" .seekslider");
	JS.players[playerIndex]['volumeslider'] = $("#player"+playerIndex+" .volumeslider");

	// Set initial parameters
	JS.players[playerIndex]['curtimetext'] = 0;
	JS.players[playerIndex]['durtimetext'] = songData.duration;

	// Add Event Handling
	$("#player"+playerIndex+" .playbtn").on("click tap", function() {
		JS.togglePlay(playerIndex);
	});
	$("#player"+playerIndex+" .mutebtn").on("click tap", function() {
		JS.mute(playerIndex);
	});
	$("#player"+playerIndex+" .seekslider").on("mousedown taphold", function() {
		JS.seek(playerIndex);
	});
	$("#player"+playerIndex+" .volumeslider").on("mousedown taphold", function() {
		JS.setVolume(playerIndex);
	});
}

var JS = {
	playlist: null,
	user: null,
	players: [],
	onReady: function() {
		JS.getPlaylist();
	},
	getPlaylist: function() {
		// get playlist for this user, or default if no playlist found
		// JS.playlist = {playerID:n, music: {musicID:n, title:'str', artist:'str', mp3file:'str'}, volume:100, seek:0, playing:false}
		$.getJSON("get-playlist.php?user="+userid, "", function(data) {
			if(data.status == "success") {
				JS.playlist = data.playlist;
				JS.user = data.user;
				JS.renderPlaylist();
			} else {
				alert("Error getting playlist:\n"+data.status);
			}
		});
	},
	renderPLaylist: function() {
		for(var i = 0; i < JS.playlist.length; i++) {
			var songData = JS.playlist[i];
			var player = new Player(i, false);
		}
	},
	togglePlay: function(playerIndex) {
		// \*\
		if(JS.playerIndex.paused){
		    audio2.play();
		    playbtn2.style.background = "url(../../images/pause.png) no-repeat";
	    } else {
		    audio2.pause();
		    playbtn2.style.background = "url(../../images/play.png) no-repeat";
	    }
	},
	mute: function(playerID) {
		// \*\
		if(audio2.muted){
		    audio2.muted = false;
		    mutebtn2.style.background = "url(../../images/speaker.png) no-repeat";
	    } else {
		    audio2.muted = true;
		    mutebtn2.style.background = "url(../../images/speaker_muted.png) no-repeat";
	    }
	},
	seek: function(playerID, event) {
	    // \*\
		if(seeking){
		    seekslider2.value = event.clientX - seekslider2.offsetLeft;
	        seekto2 = audio2.duration * (seekslider2.value / 100);
	        audio2.currentTime = seekto2;
	    }
    },
	setvolume: function(playerID, event) {
	    // \*\
		audio2.volume = volumeslider2.value / 100;
    },
	seektimeupdate: function() {
		// \*\
		var nt = audio2.currentTime * (100 / audio2.duration);
		seekslider2.value = nt;
		var curmins = Math.floor(audio2.currentTime / 60);
	    var cursecs = Math.floor(audio2.currentTime - curmins * 60);
	    var durmins = Math.floor(audio2.duration / 60);
	    var dursecs = Math.floor(audio2.duration - durmins * 60);
		if(cursecs < 10){ cursecs = "0"+cursecs; }
	    if(dursecs < 10){ dursecs = "0"+dursecs; }
	    if(curmins < 10){ curmins = "0"+curmins; }
	    if(durmins < 10){ durmins = "0"+durmins; }
		curtimetext2.innerHTML = curmins+":"+cursecs;
	    durtimetext2.innerHTML = durmins+":"+dursecs;
	}

};

