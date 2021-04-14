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
    $kolom      = $vdgaleri['option']['kolom']?$vdgaleri['option']['kolom']:1;
    $koloms     = 100/$kolom;
    $kolomres   = $vdgaleri['option']['kolomresponsif']?$vdgaleri['option']['kolomresponsif']:1;
    $kolomress  = 100/$kolomres;

    ///show if have ID & meta vdgaleri
    if($id && $vdgaleri):
    ?>
        <div class="vdgallery-galleryshow vdgallery-galleryshow-<?php echo $idnode;?>" data-node="<?php echo $idnode;?>" data-id="<?php echo $id;?>">
            
            <?php if(isset($vdgaleri['media'])&&!empty($vdgaleri['media'])): ?> 

                <div class="vdgallery-kolom">
                <?php foreach($vdgaleri['media'] as $idmedia): ?>
                    <div class="vdgallery-item vdgallery-item-<?php echo $idmedia;?>" data-id="<?php echo $idmedia;?>">
                        <a class="vdgallery-item-link" href="<?php echo wp_get_attachment_image_src($idmedia,'full')[0]; ?>">
                            <img class="vdgallery-item-image" src="<?php echo wp_get_attachment_image_src($idmedia,$size)[0]; ?>">
                        </a>
                    </div>
                <?php endforeach;?>
                </div>

            <?php endif;?>

            <style>
                .vdgallery-galleryshow-<?php echo $idnode;?> {
                    position: relative;
                }
                .vdgallery-galleryshow-<?php echo $idnode;?> .vdgallery-item {
                    -webkit-flex: 0 0 <?php echo $kolomress;?>%;
                    -ms-flex: 0 0 <?php echo $kolomress;?>%;
                    flex: 0 0 <?php echo $kolomress;?>%;
                    max-width: <?php echo $kolomress;?>%;
                }
                @media (min-width: 768px) {
                    .vdgallery-galleryshow-<?php echo $idnode;?> .vdgallery-item {
                        -webkit-flex: 0 0 <?php echo $koloms;?>%;
                        -ms-flex: 0 0 <?php echo $koloms;?>%;
                        flex: 0 0 <?php echo $koloms;?>%;
                        max-width: <?php echo $koloms;?>%;
                    }
                }
            </style>
            
        </div>
    <?php
    endif; //endif have ID & meta vdgaleri

    return ob_get_clean();
}
add_shortcode( 'vdgallery', 'vdgallery_showgallery' );
