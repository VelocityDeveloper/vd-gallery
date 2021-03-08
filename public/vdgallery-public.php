<?php
// [thumbnail id='']
function vdgallery_showgallery( $atts ) {
    ob_start();   

    //generate node/id uniqe for shortcode 
    $idnode = 'vd'.uniqid();

    //set attribute shortcode
    $atribut = shortcode_atts( array(
        'id' => '',
    ), $atts );
    $id         = $atribut['id'];

    //set value by meta vdgaleri
    $vdgaleri   = get_post_meta( $id, 'vdgaleri', true );
    $size       = $vdgaleri['option']['size']?$vdgaleri['option']['size']:'thumbnail';

    ///show if have ID & meta vdgaleri
    if($id && $vdgaleri):
    ?>
        <div class="vdgallery-galleryshow vdgallery-galleryshow-<?php echo $idnode;?>" data-node="<?php echo $idnode;?>" data-id="<?php echo $id;?>">
            <?php if($vdgaleri['media']): ?> 

                <div class="vdgallery-kolom">
                <?php foreach($vdgaleri['media'] as $idmedia): ?>

                    <a href="<?php echo wp_get_attachment_image_src($idmedia,'full')[0]; ?>">
                        <img src="<?php echo wp_get_attachment_image_src($idmedia,$size)[0]; ?>">
                    </a>

                <?php endforeach;?>
                </div>

            <?php endif;?>
        </div>
    <?php
    endif; //endif have ID & meta vdgaleri

    return ob_get_clean();
}
add_shortcode( 'vdgallery', 'vdgallery_showgallery' );
