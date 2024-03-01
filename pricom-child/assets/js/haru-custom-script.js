(function ($) {
    "use strict";
    // Add your custom script here
    // jQuery(document).on('change','input.inputh',function(e){
    //     var val = Number(jQuery(this).val());
    //     if(val < 100) {
    //         val = 100;
    //     }
    //     jQuery('input.inputh').val(val);
    // });

    // jQuery(document).on('click','.quantity2 span.minus',function(e){
    //     var btn = e.target;
    //     var value = Number(jQuery(event.target.parentNode).find('input.inputh').val());
    //     if(value > 1) {
    //         value = value - 1;
    //     }
    //     jQuery(event.target.parentNode).find('input.inputh').val(value);
    // });

    // jQuery(document).on('click','.quantity2 span.plus',function(e){
    //     var btn = e.target;
    //     var value = Number(jQuery(event.target.parentNode).find('input.inputh').val());
    //     value = value + 1;
    //     jQuery(event.target.parentNode).find('input.inputh').val(value);
    // });

    // Lấy ra phần tử cần kiểm tra
    // var element = jQuery(".nbd-option-field .nbd-swatch-wrap .nbd-swatch");
    // console.log(element,1999);

    // Lấy giá trị của thuộc tính style background
    // var backgroundColor = window.getComputedStyle(element, null).getPropertyValue("background-color");

    // Kiểm tra xem màu đó có phải là màu đen hay không
    // if (backgroundColor === "rgb(0, 0, 0)" || backgroundColor === "#000" || backgroundColor === "black") {
    //     console.log("Phần tử có màu đen trong background.");
    // } else {
    //     console.log("Phần tử không có màu đen trong background.");
    // }

    jQuery(document).on('click','.product-button.product-button--add-to-cart.cth span',function(e){
        jQuery('.single_add_to_cart_button.button.alt').click();
    });
    
})(jQuery);