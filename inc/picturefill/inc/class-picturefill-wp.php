<?php
if(!class_exists('Picturefill_WP')){
  class Picturefill_WP{

    // Setup singleton pattern
    public static function get_instance(){
      static $instance;

      if(null === $instance){
        $instance = new self();
      }

      return $instance;
    }

    private function __clone(){
      return null;
    }

    private function __wakeup(){
      return null;
    }

    // Constructor, add actions and filters
    private function __construct(){
      add_action('init', array($this, 'add_image_sizes'), 11);	//after Largo's sizes
      add_filter('the_content', array($this, 'replace_images'), 11);
    }

    public function replace_images($html){
      require_once(PICTUREFILL_WP_PATH . 'inc/class-model-picturefill-wp.php');
      $DOMDocument = Model_Picturefill_WP::get_DOMDocument();
      $images = Model_Picturefill_WP::get_images($DOMDocument, $html);
      if($images->length > 0){
        require_once(PICTUREFILL_WP_PATH . 'inc/class-view-picturefill-wp.php');
        wp_enqueue_script('picturefill');
        $html = View_Picturefill_WP::standardize_img_tags($html);
        foreach($images as $image){
          $model_picturefill_wp = new Model_Picturefill_WP($DOMDocument, $image);
          $view_picturefill_wp = new View_Picturefill_WP($model_picturefill_wp);

          $html = str_replace($view_picturefill_wp->get_original_image(), $view_picturefill_wp->render_template('picture'), $html);
        }
      }
      return $html;
    }

    public function add_image_sizes(){
      add_image_size('thumbnail@2x', get_option('thumbnail_size_w') * 2, get_option('thumbnail_size_h') * 2, get_option('thumbnail_crop'));
      add_image_size('medium@2x', get_option('medium_size_w') * 2, get_option('medium_size_h') * 2, get_option('medium_crop'));
      add_image_size('large@2x', get_option('large_size_w') * 2, get_option('large_size_h') * 2, get_option('large_crop'));
    }
  }
}
