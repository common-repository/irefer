jQuery(function ($) {
    
    $('#irefer-signup_form').on('submit', function () {
        var regex = /^(https?:\/\/)?((([a-z\d]([a-z\d-]*[a-z\d])*)\.)+[a-z]{2,}|((\d{1,3}\.){3}\d{1,3}))(\:\d+)?(\/[-a-z\d%_.~+]*)*(\?[;&a-z\d%_.~+=-]*)?(\#[-a-z\d_]*)?$/i;
        
        if (!regex.test($('#irefer_signup_data').val())) {
            alert("Please enter valid URL.");
            return false;
        } else {
            return true;
        }
    });

    $('#irefer_signup_data').tooltip({
        content: '<img class="irefer_signup_tooltip" src="' + irefer_signup.tooltop_img_src + '" />',
        position: {
            my: "left top",
            at: "bottom+5",
            collision: "none"
        }
    });
});
