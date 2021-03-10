jQuery(function($){
    $('.vdgallery-galleryshow').each(function() {
        $(this).magnificPopup({
            delegate: 'a',
            type: 'image',
            gallery: {
              enabled:true
            }
        });
    });
    $('.gallery').each(function() {
        $(this).magnificPopup({
            delegate: '.gallery-item a',
            type: 'image',
            gallery: {
              enabled:true
            }
        });
    });
});