const input = document.getElementById('file-upload');
const preview = document.getElementById('preview');
const close_overlay_button = document.getElementById('close-overlay-button');
const popup_box = document.querySelector(".add-product-overlay");
const cancelBtn = document.getElementById('cancelBtn-prd');
const add_product_btn = document.getElementById('add-product-btn');
const cancle_product = document.getElementById('cancel');
const product_form = document.querySelector("form");
const classes_btn = document.getElementById("classes-btn");
const classes_list = document.querySelector(".drop-list");
const favoriteBtn = document.getElementById('favourite');

input.addEventListener('change', function () {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      preview.src = e.target.result;
      preview.style.display = 'block';
    }
    reader.readAsDataURL(file);
  }
});

function open_overlay() {
    add_product_btn.addEventListener('click', function() {
        popup_box.style.opacity = "1";
        popup_box.style.pointerEvents = "auto";
    });
}

function close_overlay() {
    close_overlay_button.addEventListener('click',function() {
        popup_box.style.opacity = "0";
        popup_box.style.pointerEvents = "none";
        cancel();
    });
}

function cancel() {
    preview.src = "";
    preview.style.display = 'none';
    product_form.reset();
}

function classes_list_appear(){
  classes_btn.addEventListener('click',function() {
      if(window.getComputedStyle(classes_list).opacity =="0") {
        classes_list.style.opacity = "1";
        classes_list.style.pointerEvents = "auto";
      }
      else {
        classes_list.style.opacity = "0";
        classes_list.style.pointerEvents = "none";
      }
  });
}

favoriteBtn.addEventListener('click', function() {
    this.classList.toggle('active');
});

open_overlay();
close_overlay();
cancel();
classes_list_appear();
cancle_product.addEventListener('click',function() {
  cancel();
});

