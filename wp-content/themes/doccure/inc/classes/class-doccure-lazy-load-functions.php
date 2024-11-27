<?php
/**
 * Core class used to add lazy load.
 *
 * @package WordPress
 * @subpackage doccure
 * @since 2.0.0
 */

class DoccureBase_Lazy_Load {

  public static function get_default_placeholder(){
    return apply_filters( 'doccure/no_thumb_url',  get_template_directory_uri() . '/assets/images/placeholder.png' );
  }

  /**
  * Show Lazy load post image
  * @param $post_id to get the id of thumbnail
  * @param $size to set new size for thumbanil
  * @param $title return alt attribute title in a string
  * @since 2.0.0
  */
  public static function show_lazy_load_post_image($post_id = null, $size = 'full'){

    $post_id = $post_id == null ? get_the_ID() : $post_id;

    if(!has_post_thumbnail($post_id)) {
      return;
    }

    $no_thumb = !empty( doccure_get_option('lazy-load-thumb') ) && doccure_get_option('lazy-load-thumb')['url'] ? doccure_get_option('lazy-load-thumb')['url'] : self::get_default_placeholder();
    $image_id = get_post_thumbnail_id($post_id);
    $title = the_title_attribute (array('echo' => 0) );
    $image_url = wp_get_attachment_image_src($image_id, $size);
    $image_url = (!empty($image_url) && isset($image_url[0]) ) ? $image_url[0] : '';

      if(doccure_get_option('enable-lazy-loading') == true && !empty($image_url)){ ?>
        <img class="lazyload" data-src="<?php echo esc_js($image_url); ?>" src="<?php echo esc_url($no_thumb); ?>" alt="<?php echo esc_attr($title); ?>">
      <?php } else { ?>
        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>">
      <?php }
  }

  /**
  * get resized image url
  * @param $src for image path
  * @param $width to add custom width of image
  * @param $height to add custom height of image
  * @param $crop tru/false for cropping image
  * @param $quality is between 1-100
  * @return string return image resize image url
  * @since 2.0.0
  */
  public static function get_resized_url($src, $width = '', $height = '', $crop = false, $quality = '100') {
    $img_width = !empty($width) ? $width : null;
    $img_height = !empty($height) ? $height : null;
		$params = array( 'width' => $img_width, 'height' => $img_height, 'crop' => $crop, 'quality' => $quality);
		$image_url = esc_url($src);

    $no_thumb = !empty( doccure_get_option('lazy-load-thumb') ) && doccure_get_option('lazy-load-thumb')['url'] ? doccure_get_option('lazy-load-thumb')['url'] : self::get_default_placeholder();

    if(empty($image_url)){
      $image_url = bfi_thumb( $no_thumb, $params );
    } else {
  		$image_url = bfi_thumb( $image_url, $params );
    }
		return $image_url;
	}

  /**
  * function to get not resized image url
  * @param $src for image path
  * @return string return image not resize image url
  * @since 2.0.0
  */
  public static function get_not_resized_url($src) {
		$image_url = esc_url($src);
    $no_thumb = !empty( doccure_get_option('lazy-load-thumb') ) && doccure_get_option('lazy-load-thumb')['url'] ? doccure_get_option('lazy-load-thumb')['url'] : self::get_default_placeholder();

    if(empty($image_url)){
      $image_url = $no_thumb;
    } else {
  		$image_url = $image_url;
    }
		return $image_url;
	}

  /**
  * show resized image url
  * @param $width to add custom width of image
  * @param $height to add custom height of image
  * @param $crop tru/false for cropping image
  * @param $quality is between 1-100
  * @param $alt  for alternative image text
  * @since 2.0.0
  */
  public static function show_resized_image( $src, $width, $height, $crop, $quality, $alt ){

    $width_param = (!empty($width)) ? $width: '';
		$height_param = (!empty($height)) ? $height: '';

    $no_thumb = !empty( doccure_get_option('lazy-load-thumb') ) && doccure_get_option('lazy-load-thumb')['url'] ? doccure_get_option('lazy-load-thumb')['url'] : self::get_default_placeholder();
    $alt_title = !empty($alt) ? $alt : the_title_attribute (array('echo' => 0) );

    if(doccure_get_option('enable-lazy-loading') == true && !empty($src)){ ?>
      <img class="lazyload" data-src="<?php echo self::get_resized_url($src, $width, $height, $crop, $quality); ?>"
        <?php if(!empty($width)) { ?> width="<?php echo esc_attr($width); ?>" <?php }
        if(!empty($height)) { ?> height="<?php echo esc_attr($height); ?>" <?php } ?>
       src="<?php echo esc_url($no_thumb); ?>" alt="<?php echo esc_attr($alt_title); ?>">
    <?php } else { ?>
      <img src="<?php echo self::get_resized_url($src, $width, $height, $crop, $quality); ?>"
        <?php if(!empty($width)) { ?> width="<?php echo esc_attr($width); ?>" <?php }
        if(!empty($height)) { ?> height="<?php echo esc_attr($height); ?>" <?php } ?>
        alt="<?php echo esc_attr($alt_title); ?>">
    <?php }
  }

  /**
  * show resized image url
  * @param $src for image url
  * @param $alt  for alternative image text
  * @since 2.0.0
  */
  public static function show_not_resized_image( $src, $alt ){

    $no_thumb = !empty( doccure_get_option('lazy-load-thumb') )  && doccure_get_option('lazy-load-thumb')['url'] ? doccure_get_option('lazy-load-thumb')['url'] : self::get_default_placeholder();
    $alt_title = !empty($alt) ? $alt : the_title_attribute (array('echo' => 0) );

    if(doccure_get_option('enable-lazy-loading') == true && !empty($src)){ ?>
      <img class="lazyload" data-src="<?php echo self::get_not_resized_url($src); ?>" src="<?php echo esc_url($no_thumb); ?>" alt="<?php echo esc_attr($alt_title); ?>">
    <?php } else { ?>
      <img src="<?php echo self::get_not_resized_url($src); ?>" alt="<?php echo esc_attr($alt_title); ?>">
    <?php }
  }

  /**
  * Add lazy load to the content filter
  * @param $content to get the content in which data-src will be added
  * @return $content return the updated url of image data-src and src
  * @since 2.0.0
  */
  public static function doccure_add_lazy_load_content_images($content){

    if (doccure_get_option('enable-lazy-loading') == true){
        if($content){
            if(stripos($content, 'class=') !== false){
                $content = str_replace('class="', 'class="lazyload ', $content);
            }else{
                $content = str_replace('img', 'img class="lazyload"', $content);
            }

            $new_src_url = !empty( doccure_get_option('lazy-load-thumb') )  && doccure_get_option('lazy-load-thumb')['url'] ? doccure_get_option('lazy-load-thumb')['url'] : self::get_default_placeholder();

            $content = preg_replace("/<img.*?src='(.*?)'/", 'src="'.esc_url($new_src_url).'" data-src="$1"', $content);
        }
    }
    return $content;

  }
  /**
  * add lazy load to all image attachments
  * @param $attr is for image tag attributes using in wp_get_attachment_image
  * @param $attachment object for the attachment
  * @return $attr return filtered attributes of img tag
  * @since 2.0.0
  */
  public static function doccure_add_lazy_load_attachment_images( $attr, $attachment ) {
    if(doccure_get_option('enable-lazy-loading')) {
      $attr['class'] = 'lazyload ' . $attr['class'];
      $attr['data-src'] = $attr['src'];
      $attr['src'] = !empty( doccure_get_option('lazy-load-thumb') ) && doccure_get_option('lazy-load-thumb')['url'] ? doccure_get_option('lazy-load-thumb')['url'] : self::get_default_placeholder();
      return $attr;
    } else{
      $attr['src'] = $attr['src'];
      return $attr;
    }
  }
}

add_filter( 'the_content', array('DoccureBase_Lazy_Load', 'doccure_add_lazy_load_content_images') );
add_filter( 'widget_text', array('DoccureBase_Lazy_Load', 'doccure_add_lazy_load_content_images') );
add_filter( 'post_thumbnail_html', array('DoccureBase_Lazy_Load', 'doccure_add_lazy_load_content_images') );
add_filter( 'wp_get_attachment_image_attributes',  array('DoccureBase_Lazy_Load', 'doccure_add_lazy_load_attachment_images'), 10, 2 );
