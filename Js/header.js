let nav_li = document.querySelectorAll(".links ul li");
nav_li.forEach((element) => {
    element.addEventListener("click",function(e) {
        nav_li.forEach((li) => {
            li.classList.remove("active");
        });
         window.sessionStorage.setItem("active",e.currentTarget.dataset.order);
        w
        
    });
});