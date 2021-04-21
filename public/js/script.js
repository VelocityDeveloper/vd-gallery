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
        $(this).find('.gallery-item').addClass('vd-gallery-modif');
        $(this).magnificPopup({
            delegate: '.gallery-item a',
            type: 'image',
            gallery: {
              enabled:true
            }
        });
    });

});