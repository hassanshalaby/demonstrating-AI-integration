<?php 
namespace AIintegration\Components\Ajax;

trait Bootstrap{
        public function ajax__construct(){

            if( !empty(get_class_methods($this)) ){
                foreach (get_class_methods($this) as $method) {
                    add_action( 'wp_ajax_'.$method, [$this,$method]);
                    add_action( 'wp_ajax_nopriv_'.$method, [$this,$method]);
                }
            }
                  
        }


        public function generate_text_by_ai() {
            // Verify nonce 
            check_ajax_referer('ai-ajax-nonce', '_ajax_nonce');

            try {
                $content = wp_kses_post($_POST['content']);
                $number_of_words = intval(get_option('summary_length'));
                 $url = 'https://www.test.com/api';
                 $post_data = [
                    'content' => $content,
                    'summary_length' => $number_of_words
                 ];

                 /*
                $all_contents = $this->file_get_contents($url, $post_data);

                // Check  curl errors
                if (!is_null($all_contents['error'])) {
                    wp_send_json_error([
                        'message' => 'error in network: ' . $all_contents['error'],
                        'error_code' => $all_contents['error_no']
                    ]);
                    return;
                }

                // Check HTTP status code
                if ($all_contents['http_code'] !== 200) {
                    $error_message = 'API  failed - HTTP code is : ' . $all_contents['http_code'];
                    
                    // Try to extract error message from response if it's JSON
                    $response_data = json_decode($all_contents['data'], true);
                    if (json_last_error() === JSON_ERROR_NONE && isset($response_data['error'])) {
                        $error_message = $response_data['error'];
                    }
                    
                    wp_send_json_error([
                        'message' => $error_message,
                        'http_code' => $all_contents['http_code']
                    ]);
                    return;
                }

                //  successful response
                $response_data = json_decode($all_contents['data'], true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    wp_send_json_success([
                        'message' => 'Request successful',
                        'data' => $response_data
                    ]);
                } else {
                    // Handle non-JSON responses
                    wp_send_json_success([
                        'message' => 'Request successful (non-JSON response)',
                        'data' => $all_contents['data'],
                        'content_type' => $all_contents['info']['content_type'] ?? 'unknown'
                    ]);
                }
                */

                $generated_text = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

                // Prepare response
                $response = array(
                    'success' => true,
                    'data' => array(
                        'text' => $generated_text,
                        'message' => 'Text generated successfully',
                    )
                );
            } catch (Exception $e) {
                // Handle errors
                $response = array(
                    'success' => false,
                    'data' => array(
                        'message' => 'Error generating text: ' . $e->getMessage(),
                        'error_code' => $e->getCode()
                    )
                );
            }

            // Send JSON response
            wp_send_json($response);
        }

}

