//DISPLAY NONE WHEN CLOSE BUTTON CLICKED
const closeTagSpan = document.querySelector(".closeButton");

if (closeTagSpan) {
  //ADD EVENT LISTENER ON CLOSE BUTTON SPAN TAG
  closeTagSpan.addEventListener("click", () => {
    closeTagSpan.parentElement.style.display = "none";
  });
}
