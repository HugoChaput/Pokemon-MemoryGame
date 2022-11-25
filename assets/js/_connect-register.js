let secondPageMusic = document.getElementById("secondPageMusic");
let soundBtn = document.getElementById("mute");
let faSound = document.getElementById("fa_sound");
let startBackground = document.getElementById("background");
let memoryTitle = document.getElementById("memoryTitle");



// Sound
let unMute = document.getElementById("unMute");
let muteSound = document.getElementById("muteSound");

// Sound Volume
secondPageMusic.volume = 0.3;




    window.onload = function(){
      secondPageMusic.play()
      faSound.classList.replace("fa-volume-xmark", "fa-volume-high");
      startBackground.classList.add("kenburns2");
      memoryTitle.classList.add("slide-in");

    };

    // Mute Btn ON/OFF

    soundBtn.addEventListener("click", () => {
    if (secondPageMusic.paused) {
      unMute.play();
      setTimeout(() => {
        secondPageMusic.play();
      }, 800);
  
      faSound.classList.replace("fa-volume-xmark", "fa-volume-high");
      soundBtn.style.backgroundColor = "#3c5aa6";
    } else {
      secondPageMusic.pause();
      muteSound.play();
      secondPageMusic.currentTime = 0;
      faSound.classList.replace("fa-volume-high", "fa-volume-xmark");
      soundBtn.style.backgroundColor = "#b92b2b";
    }
  });