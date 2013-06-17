<?php
if(!class_exists('View_Picturefill_WP')){
  class View_Picturefill_WP{

    // Object variables
    private $image_sizes = array();
    private $original_image = '';
    private $image_attributes = array();
    private $image_attachment_data = array();

    // Static methods
    static function standardize_img_tags($html){
      return preg_replace('/(<img[^<]*?)(?:>|\/>|\s\/>)/', '$1 />', $html);
    }

    // Constructor, get data from model object
    public function __construct($model_picturefill_wp){
      $this->original_image = html_entity_decode(self::standardize_img_tags($model_picturefill_wp->get_image_xml()), ENT_COMPAT, 'UTF-8');
      $this->image_attributes = $model_picturefill_wp->get_image_attributes();
      $this->image_attachment_data = $model_picturefill_wp->get_image_attachment_data();
      $this->image_sizes = $model_picturefill_wp->get_image_sizes();
    }

    // Methods to render data in the templates
    public function generate_source_list(){
      $output = '';
      $template_data = array();
      $image_output_queue = $this->image_sizes;

      while(!empty($image_output_queue)){
        $image_size = array_shift($image_output_queue);
        $template_data['image_size'] = $image_size;
        $output .= $this->render_template('source', $template_data);
      }

      return $output;
    }

    public function get_picture_attribute_string(){
      $image_attributes = $this->image_attributes;

      $output_string = '';

      foreach($image_attributes as $attribute => $value){
        $output_string .= !empty($value) && !is_array($value) ? ' data-' . $attribute . '="' . html_entity_decode($value, ENT_COMPAT, 'UTF-8') . '"' : '';
      }

      return $output_string;
    }

    public function get_original_image_src(){
      return $this->image_attributes['src'];
    }

    public function get_original_image(){
      return $this->original_image;
    }

    public function get_image_src($image_size){
      return $this->image_attachment_data[$image_size][0];
    }

    public function get_image_width($image_size){
      return $image_size === $this->image_attributes['size'][1] ? $this->image_attributes['width'] : $this->image_attachment_data[$image_size][1];
    }

    public function get_image_height($image_size){
      return $image_size === $this->image_attributes['size'][1] ? $this->image_attributes['height'] : $this->image_attachment_data[$image_size][2];
    }

    public function get_media_query($image_size){
      $width = $image_size === $this->image_attributes['size'][1] ? $this->image_attributes['width'] : $this->image_attachment_data[$image_size][1];
      $breakpoint = 'thumbnail' === $image_size || 'thumbnail@2x' === $image_size ? 1 : $width + 20;
      $resolution_query = '@2x' === substr($image_size, -3) ? ' and (-webkit-min-device-pixel-ratio: 1.5),(min-resolution: 144dpi),(min-resolution: 1.5dppx)' : '';
      return '(min-width: ' . $breakpoint . 'px)' . $resolution_query;
    }

    // Render templates
    public function render_template($template, $template_data = array()){
      $template = PICTUREFILL_WP_PATH . 'inc/templates/' . $template . '-template.php';
      $view_picturefill_wp = $this;
      if(!empty($template_data)){
        extract($template_data);
      }
      ob_start();
      include($template);
      return ob_get_clean();
    }
  }
}
