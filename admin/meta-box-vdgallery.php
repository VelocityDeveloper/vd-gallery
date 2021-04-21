<?php 
/**
* Save meta boxes.
*/
add_action( 'save_post', 'vdgallery_save_post_class_meta', 10, 2 );
function vdgallery_save_post_class_meta( $post_id, $post ) {

  global $post; 

  if ($post->post_type != 'vdgallery') {
      return;
  }

  /* Verify the nonce before proceeding. */
  if ( !isset( $_POST['vdgallery_post_nonce'] ) || !wp_verify_nonce( $_POST['vdgallery_post_nonce'], basename( __FILE__ ) ) )
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

  /* Get the posted data and sanitize it for use as an HTML class. */
  $new_meta_value = ( isset( $_POST['vdgaleri-post'] ) ? $_POST['vdgaleri-post'] : ’ );

  /* Get the meta key. */
  $meta_key = 'vdgaleri';

  /* Get the meta value of the custom field key. */
  $meta_value = get_post_meta( $post_id, $meta_key, true );

  /* Update meta. */
  update_post_meta( $post_id, $meta_key, $new_meta_value );

} 

/**
 * Register meta boxes.
 */
function vdgallery_register_meta_boxes() {
  add_meta_box(
       'vdgallery-meta', 
       'Detail Galeri', 
       'vdgallery_display_callback', 
       'vdgallery',
       'normal',
       'default',
       ''
  );
}
add_action( 'add_meta_boxes', 'vdgallery_register_meta_boxes' );

/**
* Meta box display callback.
*
* @param WP_Post $post Current post object.
*/
function vdgallery_display_callback( $post ) {
  $getId        = isset($_GET['post'])?$_GET['post']:'';
  $datagaleri   = get_post_meta( $post->ID, 'vdgaleri', true );

  //gallery
  $datasize     = $datagaleri?$datagaleri['option']['size']:'';
  $datakolom    = $datagaleri?$datagaleri['option']['kolom']:'4';
  $datakolomres = $datagaleri?$datagaleri['option']['kolomresponsif']:'2';

  ///slideshow
  $datasizes          = $datagaleri?$datagaleri['option']['slidesize']:'full';
  $dataperslide       = $datagaleri?$datagaleri['option']['perslide']:'1';
  $dataperslideres    = $datagaleri?$datagaleri['option']['persliderespon']:'1';
  $navbtn             = $datagaleri?$datagaleri['option']['navbtn']:'';
  $navdots            = $datagaleri?$datagaleri['option']['navdots']:'';

  // print_r($datagaleri);
  wp_nonce_field( basename( __FILE__ ), 'vdgallery_post_nonce' );
  ?>

  <?php if($getId): ?>
    <div class="vdgallery-shortcode">
      <div><strong>Shortcode</strong></div>
      <br>
      <table>
        <tr>
            <td>Gallery</td>
            <td> : <span>[vdgallery id="<?php echo $getId; ?>"]</span></td>
        </tr>
        <tr>
            <td>Slide</td>
            <td> : <span>[vdgalleryslide id="<?php echo $getId; ?>"]</span></td>
        </tr>
      </table>
    </div>
    <br><hr><br>
  <?php endif; ?>

  <div class="vdgallery-tabs">
    <ul class="vdgallery-tabs-link">
      <li>
        <span class="tabs-link" data-target="tab-1">Galeri</span>
      </li>
      <li>
        <span class="tabs-link" data-target="tab-2">Galeri Option</span>
      </li>
      <li>
        <span class="tabs-link" data-target="tab-3">Slideshow Option</span>
      </li>
    </ul>
    <div class="vdgallery-tabs-opt">
      <div class="tabs-item" data-target="tab-1">
        <div class="vdgallery-main">
          <?php if($datagaleri && $datagaleri['media']): ?>
            <?php foreach($datagaleri['media'] as $galeri): ?>
              <?php $idunik = uniqid(); ?>
              <div class="vdgallery-image vdgallery-image-<?php echo $idunik; ?>" data-node="<?php echo $idunik; ?>" data-id="<?php echo $galeri; ?>">
                  <input name="vdgaleri-post[media][]" value="<?php echo $galeri; ?>" type="hidden">
                  <img src="<?php echo wp_get_attachment_image_src($galeri)[0]; ?>">
                  <div class="vdgallery-option">
                      <span class="vdgallery-remove dashicons dashicons-no-alt"></span>
                  </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <div class="box-vdgallery-clone">
          <span class="button vdgallery-add">+ Edit Galeri</span> 
        </div>
      </div>

      <div class="tabs-item" data-target="tab-2">

        <table>
          <tr>
            <td>Ukuran gambar</td>
            <td>: 
              <select name="vdgaleri-post[option][size]" class="vdgallery-input">
                <option value="thumbnail" <?php selected( $datasize,'thumbnail'); ?>>Thumbnail</option>
                <option value="medium" <?php selected( $datasize,'medium'); ?>>Medium</option>
                <option value="large" <?php selected( $datasize,'large'); ?>>large</option>
                <option value="full" <?php selected( $datasize,'full'); ?>>Full</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>Baris tampil</td>
            <td>
              : <input name="vdgaleri-post[option][kolom]" value="<?php echo $datakolom; ?>" type="number" min="1" class="vdgallery-input">
            </td>
          </tr>
          <tr>
            <td>Baris tampil responsif</td>
            <td>
              : <input name="vdgaleri-post[option][kolomresponsif]" value="<?php echo $datakolomres; ?>" type="number" min="1" max="2" class="vdgallery-input">
            </td>
          </tr>
        </table>

      </div>
      <div class="tabs-item" data-target="tab-3">
        <table>
          <tr>
            <td>Ukuran gambar</td>
            <td>: 
              <select name="vdgaleri-post[option][slidesize]" class="vdgallery-input">
                <option value="full" <?php selected( $datasizes,'full'); ?>>Full</option>
                <option value="medium" <?php selected( $datasizes,'medium'); ?>>Medium</option>
                <option value="large" <?php selected( $datasizes,'large'); ?>>large</option>
                <option value="thumbnail" <?php selected( $datasizes,'thumbnail'); ?>>Thumbnail</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>Tampil per Slide</td>
            <td>
              : <input name="vdgaleri-post[option][perslide]" value="<?php echo $dataperslide; ?>" type="number" min="1" class="vdgallery-input">
            </td>
          </tr>
          <tr>
            <td>Tampil per Slide responsif</td>
            <td>
              : <input name="vdgaleri-post[option][persliderespon]" value="<?php echo $dataperslideres; ?>" type="number" min="1" class="vdgallery-input">
            </td>
          </tr>
          <tr>
            <td>Navigasi</td>
            <td>: 
              <select name="vdgaleri-post[option][navbtn]" class="vdgallery-input">
                <option value=1 <?php selected( $navbtn,1); ?>>Ya</option>
                <option value=0 <?php selected( $navbtn,0); ?>>Tidak</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>Dots</td>
            <td>: 
              <select name="vdgaleri-post[option][navdots]" class="vdgallery-input">
                <option value="1" <?php selected( $navdots,1); ?>>Ya</option>
                <option value="0" <?php selected( $navdots,0); ?>>Tidak</option>
              </select>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  <?php
}
