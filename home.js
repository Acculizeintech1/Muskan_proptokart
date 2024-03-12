// ------------------------------------------------For Slider---------------------------------------------------------------
let slideIndex = 1;
        function moveSlide(n) {
            showSlides(slideIndex += n);
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
        // ----------------------------------------------- For calling-----------------------------------------------------------------------
function confirmCall(phoneNumber) {
    var confirmed = confirm("Do you want to call " + phoneNumber + "?");
    if (confirmed) {
        window.location.href = "tel:" + phoneNumber;
    }
}