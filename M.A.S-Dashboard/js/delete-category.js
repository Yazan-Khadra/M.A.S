

// close delete overlay variables
const close_deleteOverlay_button = document.getElementById('cancel-delete-btn');


function close_delete_overlay() {
    close_deleteOverlay_button.addEventListener('click',function() {
        delete_overlay.style.opacity = "0";
        delete_overlay.style.pointerEvents = "none";
    });
}

close_delete_overlay();
