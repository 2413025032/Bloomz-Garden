let slides = document.querySelectorAll(".slide");
let index = 0;
function showNextSlide() {
  slides[index].classList.remove("active");
  index = (index + 1) % slides.length;
  slides[index].classList.add("active");
}
setInterval(showNextSlide, 5000);

const modal = document.getElementById("zoomModal");
const modalImg = document.getElementById("zoomImage");
const closeBtn = document.querySelector(".zoom-close");
const productImages = document.querySelectorAll(".product-img");
productImages.forEach(img => {
    img.addEventListener("click", function() {
        modal.style.display = "flex";
        modalImg.src = this.src;
    });
});
closeBtn.onclick = function() {
    modal.style.display = "none";
}
modal.onclick = function(e) {
    if (e.target === modal) {
        modal.style.display = "none";
    }
}