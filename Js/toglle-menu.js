
function Mobile_Toglle_menu(){
    
    let ul_list = document.querySelector("ul");
    let li_elements = document.querySelectorAll("li");
    if(ul_list.style.display == "none"){
        ul_list.style.cssText = "display: flex;flex-flow: column wrap;justify-content: flex-start;align-items: flex-start;position: absolute;margin: 0px;padding: 0px;top: 90px; right:-50px;z-index:1";
        for(let i=0;i<li_elements.length;i++){
            li_elements[i].style.cssText = "padding-right:40px;margin:0;font-size-14px";
        }
}
else {
     ul_list.style.display = "none";
}
  }
 
  let toggle_menu = document.querySelector(".mobile-icon");
  toggle_menu.addEventListener("click",function(){
    Mobile_Toglle_menu();
  });