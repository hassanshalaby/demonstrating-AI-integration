<?php
class FOTOPT {
    /**
     * Get all theme options with RTL support
     * 
     * @return array
     */
    function options() {
        wp_enqueue_media();
        
        return [
            'general' => $this->general_options(),
        ];
    }

    /**
     * General theme options
     * 
     * @return array
     */
    private function general_options() {
        return [
            'name' => $this->rtl_text('اعدادات عامة', 'General Settings'),
            'icon' => '<i class="fad fa-th"></i>',
            'options' => [
                         
          
                [
                    'type' => 'number',
                    'id' => 'summary_length',
                    'name' => $this->rtl_text('طول الملخص', 'Summary Length'),
                ],                

            ],
        ];
    }

    

    /**
     * Create a text input option with RTL support
     * 
     * @param string $id
     * @param string $ar_name
     * @param string $en_name
     * @param string $default_value
     * @return array
     */
    private function text_option($id, $ar_name, $en_name, $default_value = '') {
        return [
            'type' => 'text',
            'id' => $id,
            'name' => $this->rtl_text($ar_name, $en_name),
            'default' => $default_value,
            'placeholder' => $default_value
        ];
    }

    /**
     * Generate RTL-compatible text based on language
     * 
     * @param string $ar Arabic text
     * @param string $en English text
     * @return string
     */
    private function rtl_text($ar, $en) {
        // You can replace this with your actual RTL detection logic
        $is_rtl = (function_exists('is_rtl') && is_rtl()) || 
                 (function_exists('get_locale') && in_array(get_locale(), ['ar', 'he', 'fa']));
        
        return $is_rtl ? $ar : $en;
    }
}