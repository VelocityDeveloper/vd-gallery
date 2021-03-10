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
       '',
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
  $datasize     = $datagaleri?$datagaleri['option']['size']:'';
  $datakolom    = $datagaleri?$datagaleri['option']['kolom']:'4';
  $datakolomres = $datagaleri?$datagaleri['option']['kolomresponsif']:'2';
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

  <br><hr><br>

  <table>
    <tr>
      <td>Ukuran tampil</td>
      <td>: 
        <select name="vdgaleri-post[option][size]">
          <option value="thumbnail" <?php selected( $datasize,'thumbnail'); ?>>Thumbnail</option>
          <option value="full" <?php selected( $datasize,'full'); ?>>Full</option>
          <option value="medium" <?php selected( $datasize,'medium'); ?>>Medium</option>
          <option value="large" <?php selected( $datasize,'large'); ?>>large</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>Baris tampil</td>
      <td>
        : <input name="vdgaleri-post[option][kolom]" value="<?php echo $datakolom; ?>" type="number" min="0">
      </td>
    </tr>
    <tr>
      <td>Baris tampil responsif</td>
      <td>
        : <input name="vdgaleri-post[option][kolomresponsif]" value="<?php echo $datakolomres; ?>" type="number" min="0">
      </td>
    </tr>
  </table>

  <?php
}
