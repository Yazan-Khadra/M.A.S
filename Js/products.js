
function Appear_Overlay(){
const open_details_button = document.querySelectorAll(".product .button button");
const overlay_container = document.querySelectorAll(".overlay");


open_details_button.forEach((button,index) => {
  const product = button.closest('.product');
  const product_image = product.querySelector('img').src;
  const product_name = product.querySelector('h3').textContent;
  const product_p = product.querySelector('p').textContent;
      button.addEventListener("click",function() {
        overlay_container[index].querySelector('img').src = product_image;
        overlay_container[index].querySelector('h3').textContent = product_name;
        overlay_container[index].querySelector('p').textContent = product_p;
        overlay_container[index].classList.add("overlay-appear");
        document.body.style.overflow = "hidden";
        document.documentElement.style.overflowY = "hidden"; // تأكيد إضافي
        
        
        

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
        document.body.style.overflow = "";
        document.documentElement.style.overflowY = "";
      });
});
}
Appear_Overlay();
Hide_Overlay();