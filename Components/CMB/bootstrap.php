<?php 
namespace AIintegration\Components\CMB;
define('CMB_PATH', trailingslashit(dirname(__FILE__)).'');

trait Bootstrap {

    function cmb__construct() {
        add_action('add_meta_boxes', [$this, 'custom_meta_box']);
        add_action('save_post', [$this, 'save_custom_meta_box_data']);
        $this->add_taxonomy_custom_fields();

        add_action('show_user_profile', [$this, 'custom_user_meta_box']); 
        add_action('edit_user_profile', [$this, 'custom_user_meta_box']);
        add_action('personal_options_update', [$this, 'save_custom_user_meta_data']);
        add_action('edit_user_profile_update', [$this, 'save_custom_user_meta_data']);
          add_action('admin_enqueue_scripts', function($hook) {
            // Check if we're on the term edit page with taxonomy and tag_ID parameters
            if (isset($_GET['taxonomy']) && isset($_GET['tag_ID']) || isset($_GET['user_id'])) {
                wp_enqueue_media();
            }
        });
    }


    function custom_user_meta_box($user) {
        $directory = CMB_PATH . 'users';
        $files = $this->extract_files($directory);

        if (!empty($files)) {
            $fields = isset($files[0]['options']) ? $files[0]['options'] : [];
            if (!empty($fields)) {
                echo '<div class="taxonomy-meta-box">';
                echo '<div class="taxonomy-title">';
                echo isset($files[0]['title']) ? $files[0]['title'] : '';
                echo '</div>';

                foreach ($fields as $el) {
                    $options = isset($el['options']) ? $el['options'] : '';
                    $value = get_user_meta($user->ID, $el['id'], 1);
                    $type = $el['type'];
                    $this->$type($el['id'], $el['id'], $value, $el['title'], $options);
                }
                echo '</div>';
            }
        }
    }

    function save_custom_user_meta_data($user_id) {
        if (current_user_can('edit_user', $user_id)) {
            $files = $this->extract_files(CMB_PATH . 'users');
            if (!empty($files)) {
                $fields = isset($files[0]['options']) ? $files[0]['options'] : [];
                if (!empty($fields)) {
                    foreach ($fields as $el) {
                        $value = sanitize_text_field($_POST[$el['id']]);
                        if (trim($value) == '') {
                            delete_user_meta($user_id, $el['id']);
                        } else {
                            update_user_meta($user_id, $el['id'], $value);
                        }
                    }
                }
            }
        }
    }

    function add_taxonomy_custom_fields() {
        // if (isset($_GET['taxonomy']) && isset($_GET['tag_ID'])) {
        //     wp_enqueue_media();
        // }
        
        $files = $this->extract_files(CMB_PATH . 'taxonomies');

        if (!empty($files)) {
            foreach ($files as $el) {
                if (isset($el['taxonomies'])) {
                    foreach ($el['taxonomies'] as $taxonomy) {
                        add_action("{$taxonomy}_edit_form_fields", [$this, 'edit_taxonomy_custom_field_form'], 110, 2);
                        add_action("edited_{$taxonomy}", [$this, 'save_taxonomy_custom_field_data']);
                    }
                }
            }
        }
    }

  function edit_taxonomy_custom_field_form($term) {
        $files = $this->extract_files(CMB_PATH . 'taxonomies');
        if (!empty($files)) {
            foreach ($files as $file) {
                $fields = $file['options'];
                echo '<div class="taxonomy-meta-box">';
                echo '<div class="taxonomy-title">';
                echo esc_html($file['title']);
                echo '</div>';
                foreach ($fields as $el) {
                    $options = isset($el['options']) ? $el['options'] : '';
                    $value = get_term_meta($term->term_id, $el['id'], true);
                    $type = $el['type'];
                    $this->$type($el['id'], $el['id'], $value, $el['title'], $options);
                }
                echo '</div>';
            }
        }
    }

    function save_taxonomy_custom_field_data($term_id) {
        $files = $this->extract_files(CMB_PATH . 'taxonomies');
        if (!empty($files)) {
            foreach ($files as $file) {
                $fields = $file['options'];
                foreach ($fields as $el) {
                    if (isset($_POST[$el['id']])) {
                        $value = sanitize_text_field($_POST[$el['id']]);
                        if (trim($value) == '') {
                            delete_term_meta($term_id, $el['id']);
                        } else {
                            update_term_meta($term_id, $el['id'], $value);
                        }
                    }
                }
            }
        }
    }

    function extract_files($directory) {
        $files = array();
        if (is_dir($directory)) {
            $phpFiles = glob($directory . '/*.php');
            if (!empty($phpFiles)) {
                foreach ($phpFiles as $file) {
                    $files[] = include($file);
                }
            }
        }
        return $files;
    }

    function custom_meta_box() {
     
        $directory = CMB_PATH . 'posts';
        $files = $this->extract_files($directory);

        if (!empty($files)) {
            foreach ($files as $el) {
                $fields = $el['options'];
                $callback = $el['callback'];
                $is_repeatable = isset($el['repeatable']) && $el['repeatable'] === true;

                if ($is_repeatable) {
                    // Handle repeatable meta boxes
                    add_meta_box(
                        $el['id'],
                        $el['title'],
                        function ($post) use ($fields, $el) {
                            $meta_box_id = $el['id'];
                            $value = get_post_meta($post->ID, $meta_box_id, true);
                            $meta_boxes = $value ? json_decode($value, true) : [];

                            echo '<div class="repeatable-meta-box" data-id="' . esc_attr($meta_box_id) . '">';
                            echo '<textarea style="display:none" name="' . esc_attr($meta_box_id) . '" id="' . esc_attr($meta_box_id) . '" class="repeatable-meta-box-data">' . esc_textarea($value) . '</textarea>';
                            echo '<div class="meta-boxes">';

                            if (!empty($meta_boxes)) {
                                foreach ($meta_boxes as $index => $meta_box_data) {
                                    echo '<div class="meta-box">';
                                    echo '<div class="flex-between">';
                                    echo '<span><num>' . ($index + 1) . '</num>' . (isset($meta_box_data[$fields[0]['id']]) ? esc_html($meta_box_data[$fields[0]['id']]) : '') . '</span>';
                                    echo '<i class="fa-solid fa-arrow-left"></i>';
                                    echo '</div>';
                                    echo '<div class="all-fields">';
                                    foreach ($fields as $fd) {
                                        $method = $fd['type'];
                                        $options = isset($fd['options']) ? $fd['options'] : '';
                                        $field_value = isset($meta_box_data[$fd['id']]) ? $meta_box_data[$fd['id']] : '';
                                        $this->$method($fd['id'], $fd['id'], $field_value, $fd['title'], $options);
                                    }
                                    if ($index > 0) {
                                        echo '<i class="fa-solid fa-xmark remove-meta-box"></i>';
                                    }
                                    echo '</div>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<div class="meta-box">';
                                echo '<div class="flex-between">';
                                echo '<span>' . (is_rtl() ? 'المكون' : 'component') . '</span>';
                                echo '<i class="fa-solid fa-arrow-left"></i>';
                                echo '</div>';
                                echo '<div class="all-fields">';
                                foreach ($fields as $fd) {
                                    $method = $fd['type'];
                                    $options = isset($fd['options']) ? $fd['options'] : '';
                                    $this->$method($fd['id'], $fd['id'], '', $fd['title'], $options);
                                }
                                echo '</div>';
                                echo '</div>';
                            }

                            echo '</div>';
                            echo '<div class="add-more-meta-box">';
                            echo '<i class="fa-solid fa-plus"></i>';
                            echo is_rtl() ? 'إضافة صندوق جديد' : 'Add new meta box';
                            echo '</div>';
                            echo '</div>';
                        },
                        $el['ptype'],
                        $el['context'],
                        $el['priority']
                    );
                } else {
                    // Existing non-repeatable meta box logic
                    add_meta_box(
                        $el['id'],
                        $el['title'],
                        function ($post) use ($fields, $callback) {
                            if (!empty($fields)) {
                                foreach ($fields as $fd) {
                                    $method = $fd['type'];
                                    $value = get_post_meta($post->ID, $fd['id'], 1);
                                    $options = isset($fd['options']) ? $fd['options'] : '';
                                    $this->$method($fd['id'], $fd['id'], $value, $fd['title'], $options);
                                }
                            } else {
                                if (!empty($callback)) {
                                    require $callback;
                                }
                            }
                        },
                        $el['ptype'],
                        $el['context'],
                        $el['priority']
                    );
                }
            }
        }
    }

    function save_custom_meta_box_data($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;

        $directory = CMB_PATH . 'posts';
        $files = $this->extract_files($directory);
        if (!empty($files)) {
            foreach ($files as $row) {
                $is_repeatable = isset($row['repeatable']) && $row['repeatable'] === true;
                if ($is_repeatable) {
                    // Handle repeatable meta boxes
                    $meta_box_id = $row['id'];
                    if (isset($_POST[$meta_box_id])) {
                        $value = $_POST[$meta_box_id];
                        if (trim($value) == '') {
                            delete_post_meta($post_id, $meta_box_id);
                        } else {
                            update_post_meta($post_id, $meta_box_id, $value);
                        }
                    }
                } else {
                    // Existing non-repeatable meta box logic
                    foreach ($row['options'] as $el) {
                        if (isset($_POST[$el['id']])) {
                            $value = $_POST[$el['id']];
                            if (trim($value) == '') {
                                delete_post_meta($post_id, $el['id']);
                            } else {
                                update_post_meta($post_id, $el['id'], $value);
                            }
                        }
                    }
                }
            }
        }
    }


}

