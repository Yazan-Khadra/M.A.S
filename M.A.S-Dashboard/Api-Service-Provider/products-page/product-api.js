const data_form = document.querySelector(".form-group form");
let responsed_data = null;
let response_message = null;
let favourite_value = false;
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

function add_product(){
    data_form.addEventListener('submit', async function(e){
        e.preventDefault();
        spinner.style.opacity = "1";
        
        try {
            const image_file = document.getElementById('file-upload').files[0];
            const product_name = document.getElementById('product_name').value;
            const product_description = document.getElementById('product_description').value;
            const class_name = document.getElementById('class_value').value;
            
            // Reset favorite value before checking
            favourite_value = false;
            if(favoriteBtn.classList.contains("active")){
                favourite_value = true;
            }
            
            if (!product_name || !product_description || !class_name) {
                throw new Error("جميع الحقول مطلوبة");
            }

            if (!image_file) {
                throw new Error("الرجاء اختيار صورة للمنتج");
            }

            // Validate file type
            if (!image_file.type.startsWith('image/')) {
                throw new Error("الرجاء اختيار ملف صورة صالح");
            }
            
            const formData = new FormData();
            formData.append('Arabic_name', product_name);
            formData.append('Arabic_description', product_description);
            formData.append('category_id', class_name);
            formData.append('main_product', favourite_value ? '1' : '0'); // Send as string '1' or '0'
            formData.append('photo', image_file, image_file.name); // Properly append file with filename

            const response = await fetch(`http://127.0.0.1:8000/api/products/insert`, {
                method: "POST",
                body: formData
            });

            if (!response.ok) {
                const status = response.status;
                try {
                    const responseText = await response.text();
                    try {
                        // Try to parse as JSON first
                        const jsonResponse = JSON.parse(responseText);
                        if (status === 422) {
                            spinner.style.opacity = "0";
                            const errorMessage = jsonResponse.errors ? 
                                Object.values(jsonResponse.errors)[0] : 
                                "خطأ في البيانات المدخلة";
                            throw new Error(errorMessage);
                        }
                    } catch(parseError) {
                        // If response is HTML (500 error page)
                        if (responseText.includes('<!DOCTYPE')) {
                            throw new Error("خطأ في رفع الصورة - يرجى التحقق من حجم وتنسيق الملف");
                        }
                    }
                    throw new Error("خطأ في السيرفر");
                } catch(e) {
                    console.error('Error details:', e);
                    throw e;
                }
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
                get_all_products();
            }, 3000);

        } catch(error) {
            console.error('Error:', error);
            showError(error.message || "حدث خطأ في الاتصال بالسيرفر");
        }
    });
}

function get_categories(){
    const class_list = document.getElementById('class_value');
    fetch(`http://127.0.0.1:8000/api/categories/ViewAllCategory`, {
        headers: {
            "Accept": "application/json",
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        class_list.innerHTML = ''; // Clear existing options
        for (element of data){
            const option_element = document.createElement("option");
            option_element.value = element.id;
            option_element.textContent = element.name;
            class_list.appendChild(option_element);
        }       
    })
    .catch(error => {
        console.error(error);
        class_list.innerHTML = '<option value="">خطأ في تحميل التصنيفات</option>';
    });
}

add_product();
get_categories();