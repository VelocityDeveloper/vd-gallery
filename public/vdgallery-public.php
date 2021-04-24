<?php
// [vdgallery id='']
add_shortcode( 'vdgallery', 'vdgallery_showgallery' );
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
    $vdgaleri           = get_post_meta( $id, 'vdgaleri', true );
    $size               = $vdgaleri['option']['size']?$vdgaleri['option']['size']:'thumbnail';
    $kolom              = $vdgaleri['option']['kolom']?$vdgaleri['option']['kolom']:1;
    $koloms             = 100/$kolom;
    $kolomres           = $vdgaleri['option']['kolomresponsif']?$vdgaleri['option']['kolomresponsif']:1;
    $kolomress          = 100/$kolomres;    
    $galericaption      = $vdgaleri['option']['galericaption']?$vdgaleri['option']['galericaption']:'tidak';
    $pagination         = (bool)$vdgaleri['option']['pagination']?$vdgaleri['option']['pagination']:0;
    $paginationitem     = $vdgaleri['option']['paginationitem']?$vdgaleri['option']['paginationitem']:9;

    //pagination     
    if($pagination==true) {
        $pagi = [];
    }

    ///show if have ID & meta vdgaleri
    if($id && $vdgaleri):
    ?>
        <div class="vdgallery-galleryshow vdgallery-galleryshow-<?php echo $idnode;?>" data-node="<?php echo $idnode;?>" data-id="<?php echo $id;?>">
            
            <?php if(isset($vdgaleri['media'])&&!empty($vdgaleri['media'])): ?> 

                <div class="vdgallery-kolom">
                <?php $pagit = 1;?>
                <?php $urut = 1;?>
                <?php foreach($vdgaleri['media'] as $idmedia): ?>

                    <?php 
                        $mediainfo = get_post($idmedia);
                        $caption   = $mediainfo->post_excerpt;
                    ?>

                    <div class="vdgallery-item vdgallery-item-<?php echo $idmedia;?>" data-id="<?php echo $idmedia;?>" data-urut="<?php echo $urut;?>" data-pagi="<?php echo $pagit;?>">
                        <div class="vdgallery-item-inside">
                        
                            <a class="vdgallery-item-link" href="<?php echo wp_get_attachment_image_src($idmedia,'full')[0]; ?>" title="<?php echo $caption;?>">
                                <img class="vdgallery-item-image" src="<?php echo wp_get_attachment_image_src($idmedia,$size)[0]; ?>">
                            </a>      

                            <?php if(($galericaption!='tidak') && !empty($caption)): ?>
                                <span class="vdgallery-item-caption vdgallery-item-caption-<?php echo $galericaption;?>">
                                    <?php echo $caption;?>
                                </span>
                            <?php endif; ?>

                        </div>

                    </div>
                    <?php 
                    $pagi[$pagit][] = $idmedia;
                    if($pagination==true && $urut==$paginationitem) {
                        $urut = 0;
                        $pagit++;
                    }
                    $urut++;
                    ?>
                <?php endforeach;?>
                </div>
                
                <?php
                //pagination if true
                if($pagination==true) { 
                    echo '<div class="vdgallery-pagination" data-node="'.$idnode.'">';
                    foreach ($pagi as $key => $value) {
                        echo '<div class="vdgallery-pagi-btn" data-pagi="'.$key.'" data-node="'.$idnode.'">';
                            echo '<span>'.$key.'</span>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
                ?>

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

// [vdgalleryslide id='']
add_shortcode( 'vdgalleryslide', 'vdgallery_showslide' );
function vdgallery_showslide( $atts ) {
    ob_start();   

    //generate node/id uniqe for shortcode 
    $idnode = 'vd'.uniqid();

    //set attribute shortcode
    $atribut = shortcode_atts( array(
        'id' => '',
    ), $atts );
    $id         = $atribut['id'];

    //set value by meta vdgaleri
    $vdgaleri       = get_post_meta( $id, 'vdgaleri', true );
    $size           = $vdgaleri['option']['slidesize']?$vdgaleri['option']['slidesize']:'full';
    $perslide       = $vdgaleri['option']['perslide']?$vdgaleri['option']['perslide']:100;
    $perslideres    = $vdgaleri['option']['persliderespon']?$vdgaleri['option']['persliderespon']:100;

    //flickity opt
    $flickity   = [
        'contain'           => true,
        'cellAlign'         => 'left',
        'adaptiveHeight'    => false,
        'prevNextButtons'   => (bool)$vdgaleri['option']['navbtn'],
        'pageDots'          => (bool)$vdgaleri['option']['navdots'],
        'autoPlay'          => (bool)$vdgaleri['option']['autoplay'],
        'wrapAround'        => (bool)$vdgaleri['option']['infinite'],
    ];
    $flickity   = json_encode($flickity);

    ///show if have ID & meta vdgaleri
    if($id && $vdgaleri):
    ?>
    <div class="vdgallery-slideshow vdgallery-slideshow-<?php echo $idnode;?>" data-node="<?php echo $idnode;?>" data-id="<?php echo $id;?>">
        
        <?php if(isset($vdgaleri['media'])&&!empty($vdgaleri['media'])): ?>

            <div class="vdgallery-slide-box" data-node="<?php echo $idnode;?>">
                <div class="vdgallery-slide" data-flickity='<?php echo $flickity;?>'>
                    <?php foreach($vdgaleri['media'] as $idmedia): ?>
                        <div class="vdgallery-item vdgallery-item-<?php echo $idmedia;?>" data-id="<?php echo $idmedia;?>">
                            <img class="vdgallery-item-image" src="<?php echo wp_get_attachment_image_src($idmedia,$size)[0]; ?>">
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
            
        <?php endif;?>

        <style>
            .vdgallery-slideshow-<?php echo $idnode;?> .vdgallery-item {
                width: <?php echo 100/$perslideres;?>%;
            }
            @media (min-width: 768px) {
                .vdgallery-slideshow-<?php echo $idnode;?> .vdgallery-item {
                    width: <?php echo 100/$perslide;?>%;
                }
            }
        </style>

    </div>
    <?php
    endif; //endif have ID & meta vdgaleri

    return ob_get_clean();
}