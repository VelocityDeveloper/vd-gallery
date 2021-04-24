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
    $('.vdgallery-pagination').each(function() {
        var node = $(this).data('node');
        $('.vdgallery-galleryshow-'+node+' .vdgallery-item').hide();
        $('.vdgallery-galleryshow-'+node+' .vdgallery-item[data-pagi="1"]').show();
        $('.vdgallery-galleryshow-'+node+' .vdgallery-pagi-btn[data-pagi="1"]').addClass('active');
    });
    $('.vdgallery-pagi-btn').click(function() {
        var node = $(this).data('node');
        var pagi = $(this).data('pagi');
        $('.vdgallery-galleryshow-'+node+' .vdgallery-item').hide();
        $('.vdgallery-galleryshow-'+node+' .vdgallery-item[data-pagi="'+pagi+'"]').show();
        $('.vdgallery-galleryshow-'+node+' .vdgallery-pagi-btn').removeClass('active');
        $('.vdgallery-galleryshow-'+node+' .vdgallery-pagi-btn[data-pagi="'+pagi+'"]').addClass('active');
    });
});