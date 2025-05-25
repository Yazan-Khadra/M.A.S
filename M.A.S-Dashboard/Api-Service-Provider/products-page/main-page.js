const delete_overlay = document.querySelector(".delete-overlay");
const confirm_delete_btn = document.getElementById('delete-btn');


function show_categories(){
      const ul_dropList = document.querySelector(".drop-list ul");
      
    fetch("http://127.0.0.1:8000/api/categories/ViewAllCategory")

    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
          }
          return  response.json();
    })
    
    .then(data => {

        for (element of data){
            const li_element = document.createElement("li");
            li_element.textContent = element.name;
            li_element.setAttribute("category_id",element.id);
            li_element.addEventListener("click",function() {
                if(li_element.getAttribute("category_id") === "0"){
                    get_all_products();
                }
                else{
                get_product_by_category(li_element.getAttribute("category_id"));
                }
                
            });
            ul_dropList.appendChild(li_element);
        }       
    })
    .catch(error => {
        console.error(error);
    });
}

function create_delete_update_button(obj) {
    const div_container = document.createElement("div");
    div_container.className = "U-D";
    const delete_button = document.createElement("button");
    delete_button.id = "delete-button";
    delete_button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                                            <path d="M6.75337 17.5988C6.29245 17.5988 5.89801 17.4349 5.57006 17.1069C5.24211 16.779 5.07785 16.3842 5.0773 15.9228V5.02827H4.23926V3.3522H8.42945V2.51416H13.4577V3.3522H17.6479V5.02827H16.8098V15.9228C16.8098 16.3837 16.6458 16.7784 16.3179 17.1069C15.9899 17.4354 15.5952 17.5994 15.1337 17.5988H6.75337ZM15.1337 5.02827H6.75337V15.9228H15.1337V5.02827ZM8.42945 14.2467H10.1055V6.70435H8.42945V14.2467ZM11.7816 14.2467H13.4577V6.70435H11.7816V14.2467Z" fill="#CF1414"/>
                                          </svg>`;
    const delete_statment = document.createElement("span");
    delete_statment.textContent = "حذف";
    
    // let product_id = document.createAttribute('product_id');

    // add Event listner
    delete_button.addEventListener('click',function() {
             delete_overlay.style.opacity = "1";
            delete_overlay.style.pointerEvents = "auto";
            confirm_delete_btn.setAttribute("product_id",obj.id);
            
    });
    div_container.appendChild(delete_button);
    div_container.appendChild(delete_statment);
   // Update button
    const updateBtn = document.createElement('button');
    updateBtn.id = 'update-btn';
    updateBtn.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
        <g clip-path="url(#clip0_25_8435)">
          <path d="M9.59186 2.32397H4.16939C3.7585 2.32397 3.36443 2.4872 3.07389 2.77775C2.78334 3.06829 2.62012 3.46236 2.62012 3.87325V14.7182C2.62012 15.1291 2.78334 15.5231 3.07389 15.8137C3.36443 16.1042 3.7585 16.2675 4.16939 16.2675H15.0143C15.4252 16.2675 15.8193 16.1042 16.1098 15.8137C16.4004 15.5231 16.5636 15.1291 16.5636 14.7182V9.29571" stroke="black" stroke-width="1.54928" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M14.5302 2.03342C14.8384 1.72525 15.2563 1.55212 15.6922 1.55212C16.128 1.55212 16.5459 1.72525 16.8541 2.03342C17.1623 2.34159 17.3354 2.75956 17.3354 3.19538C17.3354 3.6312 17.1623 4.04917 16.8541 4.35734L9.8723 11.3399C9.68837 11.5237 9.46113 11.6582 9.21154 11.7311L6.986 12.3818C6.91935 12.4013 6.84869 12.4024 6.78143 12.3852C6.71417 12.368 6.65277 12.333 6.60368 12.2839C6.55458 12.2348 6.51958 12.1734 6.50235 12.1061C6.48512 12.0388 6.48629 11.9682 6.50573 11.9015L7.15642 9.676C7.22965 9.4266 7.36444 9.19964 7.54839 9.01601L14.5302 2.03342Z" stroke="black" stroke-width="1.54928" stroke-linecap="round" stroke-linejoin="round"/>
        </g>
        <defs>
          <clipPath id="clip0_25_8435">
            <rect width="18.5913" height="18.5913" fill="white" transform="translate(0.295898)"/>
          </clipPath>
        </defs>
      </svg>
    `;

    div_container.appendChild(updateBtn);

    // Update span
    const updateSpan = document.createElement('span');
    updateSpan.textContent = 'تعديل';
    div_container.appendChild(updateSpan);

    return div_container;
}
function get_all_products() {
    const table_tbody = document.querySelector("table tbody");
    
    fetch("http://127.0.0.1:8000/api/products/ViewAllProducts")
    .then(response => {
        if(!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        table_tbody.innerHTML = '';
       
        for(obj of data){

            const tr = document.createElement("tr");
            const name = document.createElement("td");
            const description = document.createElement("td");
            const category = document.createElement("td");
            const actions_row = document.createElement("td");
            name.textContent = obj.name;
            description.textContent = obj.description;
            category.textContent = obj.category;

            actions_row.appendChild(create_delete_update_button(obj));
            
            tr.appendChild(name);
            tr.appendChild(description);
            tr.appendChild(category);
            tr.appendChild(actions_row);
            table_tbody.appendChild(tr);

        }
    })
    .catch(error => {
        console.error(error);
    });
}

function delete_product(product_id) {
 
 spinner.style.opacity = "1";
 spinner_icon.style.display = "block";
 console.log(spinner_icon.style);
 const data = {
    id : product_id
 };
    
    fetch(`http://127.0.0.1:8000/api/products/delete`,{
        method : "DELETE",
        headers : {
            "Content-type" : 'application/json',
        },  
        body : JSON.stringify(data),
    }

    )
    .then(response => {
        if(!response.ok){
            throw new Error("حدث خطأ غير متوقع");
        }
        return response.json();
    })
    .then(data => {
          spinner_icon.style.display = "none";
 done_statment.style.display = "block";
 done_statment.textContent = "تم حذف العنصر بنجاح";
 window.setTimeout(function(){
    spinner.style.opacity = "0";
    done_statment.style.display = "none";
    get_all_products();
    delete_overlay.style.opacity = "0";
        delete_overlay.style.pointerEvents = "none";
 },3000);
       
    })
    .catch(error => {
        console.error(error);
    })
   
}
  
function confirm_delete() {
    confirm_delete_btn.onclick = function() {
        delete_product(confirm_delete_btn.getAttribute("product_id"));
    }
}
function get_product_by_category($id) {
    
    fetch("http://127.0.0.1:8000/api/category/products/"+$id,{
        headers : {
            "accept" : "application.json"
        },
    })
    .then(response => {
        if(!response.ok) {
            throw new Error(" حدث خطأ غير متوقع في السيرفر يرجى المحاولة لاحقا"+response.status)
        }
        return response.json();
    }
    )
     .then(data => {
        console.log(data);
        const table_tbody = document.querySelector("table tbody");
        table_tbody.innerHTML = '';

        for(obj of data){
            
            const tr = document.createElement("tr");
            const name = document.createElement("td");
            const description = document.createElement("td");
            const category = document.createElement("td");
            const actions_row = document.createElement("td");
            name.textContent = obj.name;
            description.textContent = obj.description;
            category.textContent = obj.category;

            actions_row.appendChild(create_delete_update_button(obj));
            
            tr.appendChild(name);
            tr.appendChild(description);
            tr.appendChild(category);
            tr.appendChild(actions_row);
            table_tbody.appendChild(tr);

        }
    })
    .catch(error => {
        console.error(error);
    });
}
// function categories_filter() {
//     const drop_list_li = document.querySelectorAll(".drop-list ul li");
//     console.log(drop_list_li);
//     drop_list_li.forEach((e)=> {
//         e.addEventListener("click",function() {
//             get_product_by_category(drop_list_li.getAttribute("category_id"));

//         });
//     });
// }

show_categories();
get_all_products(); 
confirm_delete();
// categories_filter();
    // const delete_buttons = document.querySelectorAll('#delete-button');
    // const confirm_delete = document.getElementById('delete-btn');
    // delete_buttons.forEach((button) => {
    //     button.addEventListener('click',function(e) {
    //        product_id = e.currentTarget.getAttribute("product_id");
      
    //     });
    // });
    //         confirm_delete.addEventListener('click',function() {
    //             console.log("hay");
    //          delete_product(product_id);
    // });
 
   