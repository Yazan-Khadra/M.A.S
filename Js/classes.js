let categories = document.querySelectorAll('.category-card');
categories.forEach((category,index) => {
    if(index == categories.length-1){
        category.classList.add("hidden-Y");
    }
   
});
