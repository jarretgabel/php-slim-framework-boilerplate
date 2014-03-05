$(function() {

	var video            = document.getElementById("video"),
			playButton       = document.getElementById("play-pause"),
			muteButton       = document.getElementById("mute"),
			fullScreenButton = document.getElementById("full-screen"),
			seekBar          = document.getElementById("seek-bar"),
			volumeBar        = document.getElementById("volume-bar");

	$(playButton).on("click", function() {
	  if (video.paused == true) {
	    video.play();
	    playButton.innerHTML = "Pause";
	  } else {
	    video.pause();
	    playButton.innerHTML = "Play";
	  }
	});

	$(muteButton).on("click", function() {
	  if (video.muted == false) {
	    video.muted = true;
	    muteButton.innerHTML = "Unmute";
	  } else {
	    video.muted = false;
	    muteButton.innerHTML = "Mute";
	  }
	});

	$(fullScreenButton).on("click", function() {
		if (video.requestFullscreen) {
		  video.requestFullscreen();
		} else if (video.mozRequestFullScreen) {
		  video.mozRequestFullScreen();
		} else if (video.webkitRequestFullscreen) {
		  video.webkitRequestFullscreen();
		}
	});

	$(seekBar).on("change", function() {
  	var time = video.duration * (seekBar.value / 100);
  	video.currentTime = time;
  });

  $(video).on("timeupdate", function() {
	  var value = (100 / video.duration) * video.currentTime;
	  seekBar.value = value;
	});

	$(seekBar).on("mousedown", function() {
	  video.pause();
	});

	$(seekBar).on("mouseup", function() {
	  video.play();
	});

	$(volumeBar).on("change", function() {
	  video.volume = volumeBar.value;
	});

	// $(window).on("resize", function() {
	// 	resize();
	// });
	// resize();
	// function resize() {
	// 	var winWidth 				 = $(window).width(),
	// 			winHeight        = $(window).height();

	// 	$(video).css({
	// 		width: winWidth,
	// 		// height: winHeight
	// 	});
	// }

});