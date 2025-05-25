const data_form = document.querySelector(".form-group form");
let responsed_data = null;
let response_message = null;

const spinner = document.getElementById('spinner-screen');
const done_statment = document.getElementById('done-statment');
const spinner_icon = document.getElementById('spinner');




function showError(message) {
    spinner_icon.style.display = "none";
    done_statment.style.display = "block";
    done_statment.textContent = message;
    done_statment.style.color = "#ff3333";
    
    window.setTimeout(function(){
        spinner.style.opacity = "0";
        spinner_icon.style.display = "block";
        done_statment.style.display = "none";
        done_statment.style.color = ""; // Reset color
    }, 3000);
}

function add_category(){
    data_form.addEventListener('submit', async function(e){
        e.preventDefault();
        spinner.style.opacity = "1";
        
        try {
            const image_url = document.getElementById('file-upload').files[0];
            const category_name = document.getElementById('category_name').value;
            if (!category_name) {
                throw new Error("جميع الحقول مطلوبة");
            }
           
         const formData = new FormData();
         formData.append("Arabic_name",category_name);
         formData.append('photo', image_url, image_url.name);

            const response = await fetch(`http://127.0.0.1:8000/api/categories/insert`, {
                method: "POST",
                body: formData,
            });
            
            if (!response.ok) {
                 const errorData = await response.json();
                 console.log(errorData);
                const status = response.status;
                try {   
                   
                    if (status === 409) {
                        spinner.style.opacity = "0";
                        throw new Error("هذا التصنيف موجود بالفعل");
                    }
                    else if(status === 422) {
                        
                    }
                    
                } catch(e) {
                    console.error('Error parsing response:', e);
                }
                throw new Error("خطأ في السيرفر");
            }
            
            responsed_data = await response.json();
            
            spinner_icon.style.display = "none";
            done_statment.style.display = "block";
            done_statment.textContent = "تم اضافة العنصر بنجاح";
            
            window.setTimeout(function(){
                spinner.style.opacity = "0";
                spinner_icon.style.display = "block";
                done_statment.style.display = "none";
                cancel();
                get_all_categories();
            }, 3000);

        } catch(error) {
            console.error('Error:', error);
            showError(error.message || "حدث خطأ في الاتصال بالسيرفر");
        }
    });
}

add_category();
