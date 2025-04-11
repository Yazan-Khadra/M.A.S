
function Appear_Overlay(){
const open_details_button = document.querySelectorAll(".product button");
const overlay_container = document.querySelectorAll(".overlay");

open_details_button.forEach((button,index) => {
      button.addEventListener("click",function() {
        overlay_container[index].classList.add("overlay-appear");
        document.body.style.pointerEvents = "none";
        overlay_container.style.backgroundColor = "#000000B8";

      });
});
}
// hide overlay
function Hide_Overlay(){
    const open_details_button = document.querySelectorAll(".cancle-button svg");
const overlay_container = document.querySelectorAll(".overlay");
open_details_button.forEach((button,index) => {
      button.addEventListener("click",function() {
        overlay_container[index].classList.remove("overlay-appear");
        document.body.style.pointerEvents = "all";
      });
});
}
Appear_Overlay();
Hide_Overlay();