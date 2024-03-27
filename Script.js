// ------------------------------------------------For Slider---------------------------------------------------------------
let slideIndex = 1;
function moveSlide(n) {
  showSlides((slideIndex += n));
}

function showSlides(n) {
  let slides = document.getElementsByClassName("slide_row");
  if (n > slides.length) {
    slideIndex = 1;
  }
  if (n < 1) {
    slideIndex = slides.length;
  }
  for (let i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slides[slideIndex - 1].style.display = "block";
}

// --------------------------------------------------------------Reviews-----------------------------------------------------
const sliderContainer = document.querySelector(".slider-container");
const sliderItems = document.querySelectorAll(".review-card");
let currentIndex = 0;

function nextSlide() {
  currentIndex++;
  if (currentIndex > sliderItems.length - 1) {
    currentIndex = 0;
  }
  updateSlider();
}

function prevSlide() {
  currentIndex--;
  if (currentIndex < 0) {
    currentIndex = sliderItems.length - 1;
  }
  updateSlider();
}

function updateSlider() {
  const offset = -currentIndex * sliderItems[0].offsetWidth;
  sliderContainer.style.transform = `translateX(${offset}px)`;
}
// ----------------------------------------------- For calling-----------------------------------------------------------------------
function confirmCall(phoneNumber) {
  var confirmed = confirm("Do you want to call " + phoneNumber + "?");
  if (confirmed) {
    window.location.href = "tel:" + phoneNumber;
  }
}

//-------------------------------------- Function to toggle password visibility-------------------------------------------------------------
function togglePasswordVisibility() {
  var passwordInput = document.getElementById("password");
  var showPasswordCheckbox = document.getElementById("showPassword");

  // If checkbox is checked, show password, otherwise hide it
  if (showPasswordCheckbox.checked) {
    passwordInput.type = "text";
  } else {
    passwordInput.type = "password";
  }
}

// Attach event listener to the checkbox
document
  .getElementById("showPassword")
  .addEventListener("change", togglePasswordVisibility);
