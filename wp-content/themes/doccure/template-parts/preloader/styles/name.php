<div class="preloader-name">
  <p id="letters">
    <?php
    $site_name = get_bloginfo('name');
    if($site_name){
      array_map( function($e){
        echo '<span>'.esc_html($e).'</span>';
      }, str_split($site_name));
    }
    ?>
  </p>
  <b><?php echo esc_html__('Loading Site', 'doccure') ?> <i>.</i><i>.</i><i>.</i> </b>
</div>
