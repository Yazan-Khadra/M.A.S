const nav_list = document.querySelector(".links ul");
const list_content = document.querySelectorAll(".links ul li");
function Mobile_Toglle_menu(){
  
    if(window.getComputedStyle(nav_list).opacity == "0"){
    document.documentElement.style.overflow = "hidden";
    nav_list.style.cssText = "opacity:1;pointer-events: auto;";
    nav_list.style.height = "100vh";

    }
    else {
      nav_list.style.opacity = "0";
      nav_list.style.height = "0";
      nav_list.style.pointerEvents = "none";
      document.documentElement.style.overflowY = "";
    }
  }
 
  let toggle_menu = document.querySelector(".mobile-icon");
  toggle_menu.addEventListener("click",function(){
    Mobile_Toglle_menu();
  });