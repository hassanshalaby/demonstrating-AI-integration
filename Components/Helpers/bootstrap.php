<?php
namespace AIintegration\Components\Helpers; 
  trait Bootstrap {

    public function helpers__construct(){
        add_action( 'admin_enqueue_scripts',array( $this, 'enqueue_admin_scripts' ));
        add_filter('the_content',array($this,'summary_content'));
    }


      // Showing Summary Ai after the content

      public function summary_content(){
          ob_start();

          echo '<div class="custom-section" style="padding:10px; background-color:#f7f7f7;border-radius:5px">';
          echo  '<h3>AI Summary</h3>';
          echo  '<p>'.esc_html( get_post_meta(get_the_ID(),'generated_text',true) ).'</p>';
          echo '</div>';
          $custom_section = ob_get_clean();

          return $content . $custom_section;

      }
          

    public function enqueue_admin_scripts(){
     wp_enqueue_style('ai-defult-css', DAI_URI . 'assets/css/ai.css',[],'1.0.0');
     wp_register_script('ai-js', DAI_URI . 'assets/js/ai.js',['jquery'],'1.0.0',true);

      // Localize script with nonce and AJAX URL
      wp_localize_script(
          'ai-js','admin',
          array('ajaxurl' => admin_url('admin-ajax.php'),'nonce' => wp_create_nonce('ai-ajax-nonce'))
      );

      wp_enqueue_script('ai-js');

    }


    // File get contents

  public function file_get_contents($url, $postData = null, $headers = []) {
      $ch = curl_init($url);
      
      // Basic cURL settings with improvements
      curl_setopt_array($ch, [
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HEADER => true,
          CURLINFO_HEADER_OUT => true,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_MAXREDIRS => 5, // Limit redirects
          CURLOPT_FILETIME => true,
          CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
          CURLOPT_CONNECTTIMEOUT => 30,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FAILONERROR => false,
          CURLOPT_ENCODING => "",
          CURLOPT_AUTOREFERER => true,
          CURLOPT_SSL_VERIFYPEER => true, // Enable SSL verification
          CURLOPT_SSL_VERIFYHOST => 2,
          CURLOPT_CAINFO => '/path/to/cacert.pem', // Optional: specify CA bundle
          CURLOPT_BUFFERSIZE => 16384, // Larger buffer for better performance
      ]);

      // Handle POST data
      if (!is_null($postData)) {
          curl_setopt($ch, CURLOPT_POST, true);
          if (is_array($postData)) {
              curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
          } else {
              curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
              if (empty($headers['Content-Type'])) {
                  $headers['Content-Type'] = 'application/json';
              }
          }
      }

      // Set custom headers
      if (!empty($headers)) {
          $headerArray = [];
          foreach ($headers as $key => $value) {
              $headerArray[] = "$key: $value";
          }
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
      }

      // Execute with error handling
      $data = curl_exec($ch);
      $result = [
          'data' => '',
          'headers' => '',
          'headers_array' => [], // Parsed headers
          'info' => [],
          'error' => null,
          'error_no' => 0,
          'http_code' => 0
      ];

      if (curl_errno($ch)) {
          $result['error'] = 'cURL Error: ' . curl_error($ch);
          $result['error_no'] = curl_errno($ch);
      } else {
          $info = curl_getinfo($ch);
          $result['info'] = $info;
          $result['http_code'] = $info['http_code'];

          $headerSize = $info['header_size'];
          $result['headers'] = substr($data, 0, $headerSize);
          $result['data'] = substr($data, $headerSize);
          
          // Parse headers into array
          $result['headers_array'] = $this->parseHeaders($result['headers']);
      }

      curl_close($ch);
      return $result;
  }

  // Helper function to parse headers
  private function parseHeaders($headers) {
      $headersArray = [];
      $headers = explode("\r\n", $headers);
      
      foreach ($headers as $header) {
          if (strpos($header, ':') !== false) {
              list($key, $value) = explode(':', $header, 2);
              $headersArray[trim($key)] = trim($value);
          } elseif (!empty(trim($header))) {
              $headersArray[] = trim($header); // Status line
          }
      }
      
      return $headersArray;
  }


    
    


}
