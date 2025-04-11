let footer_li = document.querySelectorAll(".app-bar li");
footer_li.forEach((element) => {
    element.addEventListener("click",function(e) {
        footer_li.forEach((li) => {
            li.classList.remove("active");
        });
         window.sessionStorage.setItem("active",e.currentTarget.dataset.order);
        
    });
});