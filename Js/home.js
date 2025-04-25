function Read_More_Button_Active(){
let action_button = document.querySelector("button");

    let pargraphs = document.querySelector(".pargraphs");
    action_button.onclick = function(){
    if(window.getComputedStyle(pargraphs).opacity == "0"){
        action_button.textContent = "اخفاء";
        pargraphs.style.opacity = "1";
        pargraphs.style.height = "80%";
       

    }
    else {
        pargraphs.style.opacity = "0";
        pargraphs.style.height = "0";
        action_button.textContent = "قراءة المزيد";
        
     }
    
  }
}
function See_All_Products() {
    let action_element = document.querySelector(".footer h2");
    action_element.addEventListener("click",function(){
        window.sessionStorage.active = "4";
        window.location.href = "products.html#first-product";
    });
}
    let current = 0;
const slider = document.querySelector(".intro");
const images = [
    "Images/backgrounds/Home.png",
    "Images/Slider/image-1.jpg",
    "Images/Slider/image-2.jpg",
    "Images/Slider/image-3.jpg",
    
    
];
function Change_Background(){

    slider.style.backgroundImage = `url('${images[current]}')`;
     current = (current + 1) % images.length;
   
}
Read_More_Button_Active();
See_All_Products();
Change_Background();
setInterval(Change_Background, 4000);


