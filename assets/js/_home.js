let startBackground = document.getElementById("background");
let soundBtn = document.getElementById("mute");
let body = document.getElementById("body");
let faSound = document.getElementById("fa_sound");
let startBtn = document.getElementById("startBtn");
let pikaLoader = document.getElementById("pikaLoader");

// Sound
let backgroundSound = document.getElementById("start_music");
let startSound = document.getElementById("startSound");
let unMute = document.getElementById("unMute");
let muteSound = document.getElementById("muteSound");

// Sound Volume
backgroundSound.volume = 0.3;

// After the page load
window.onload = function () {
  let ouiBtn = document.getElementById("ouiBtn");
  let nonBtn = document.getElementById("nonBtn");
  let modal = document.getElementById("modalContainer");

  // Modal settings
  ouiBtn.addEventListener("click", () => {
    unMute.play();
    setTimeout(() => {
      backgroundSound.play();
    }, 800);
    // alert("Activation du son en jeu");
    modal.style.display = "none";
    faSound.classList.replace("fa-volume-xmark", "fa-volume-high");
    soundBtn.style.backgroundColor = "#3c5aa6";
  });

  nonBtn.addEventListener("click", () => {
    alert("Le son en jeu est désormais désactivé");
    modal.style.display = "none";
    faSound.classList.replace("fa-volume-high", "fa-volume-xmark");
    soundBtn.style.backgroundColor = "#3c5aa6";
  });

  if (ouiBtn || nonBtn) {
    let memoryTitle = document.getElementById("memoryTitle");
    let logo_pokemon = document.getElementById("logo_pokemon");

    // adding animation CSS by Class
    addEventListener("click", () => {
      startBackground.classList.add("kenburns");
      memoryTitle.classList.add("slide-in");
      soundBtn.classList.add("bounce");
      logo_pokemon.classList.add("rotate");
    });
  }
};

// Mute Btn ON/OFF

soundBtn.addEventListener("click", () => {
  if (backgroundSound.paused) {
    unMute.play();
    setTimeout(() => {
      backgroundSound.play();
    }, 800);

    faSound.classList.replace("fa-volume-xmark", "fa-volume-high");
    soundBtn.style.backgroundColor = "#3c5aa6";
  } else {
    backgroundSound.pause();
    muteSound.play();
    backgroundSound.currentTime = 0;
    faSound.classList.replace("fa-volume-high", "fa-volume-xmark");
    soundBtn.style.backgroundColor = "#b92b2b";
  }
});

// Start Button settings

startBtn.addEventListener("click", () => {
  startSound.play();
  backgroundSound.pause();
  pikaLoader.style.display = "block";
  console.log(startBackground.classList);
  startBackground.classList.replace("kenburns", "kenburns2");
  setTimeout(() => {
    window.location.href = "sign-in";
  }, 3100);
});

// Enter keyboard
window.addEventListener("keydown", (event) => {
  if (event.code === "Enter") {
    startSound.play();
    backgroundSound.pause();
    pikaLoader.style.display = "block";
    startBackground.classList.replace("kenburns", "kenburns2");
    setTimeout(() => {
      window.location.href = "sign-in";
    }, 3100);
  }
});
