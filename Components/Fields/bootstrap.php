<?php 
namespace AIintegration\Components\Fields;

trait Bootstrap {
    function taxonomy_select($id, $name, $value, $title, $taxonomy = '', $parent = '') {
        $args = ['taxonomy' => $taxonomy, 'hide_empty' => 0];
        if ($parent == 0) {
            $args['parent'] = 0;
        }
        $options = get_categories($args);

        echo '<p>';
        echo '<label for="' . $id . '">' . _e($title) . '</label> ';
        echo '<select class="widefat" id="' . $id . '" name="' . $name . '">';
        echo '<option value="">' . (is_rtl() ? 'اختر' : 'choose') . ' </option>';
        foreach ($options as $c) {
            echo '<option ' . ($value == $c->term_id ? 'selected' : '') . ' value="' . $c->term_id . '">' . $c->name . '</option>';
        }
        echo '</select>';
        echo '</p>';
    }

    function select($id, $name, $value, $title, $options) {
        echo '<p>';
        echo '<label for="' . $id . '">' . _e($title) . '</label> ';
        echo '<select class="widefat" id="' . $id . '" name="' . $name . '">';
        echo '<option value="">' . (is_rtl() ? 'اختر' : 'choose') . ' </option>';
        foreach ($options as $key => $v) {
            echo '<option ' . ($value == $key ? 'selected' : '') . ' value="' . $key . '">' . $v . '</option>';
        }
        echo '</select>';
        echo '</p>';
    }

    function checkbox($id, $name, $value, $title, $options) {
        echo '<p>';
        echo '<label for="' . $id . '">' . _e($title) . '</label> ';
        echo '<input class="widefat" id="' . $id . '" name="' . $name . '" type="hidden" value="' . $value . '">';
        echo '<div class="wid-toggle ' . ($value == 'on' ? 'active' : '') . '"></div>';
        echo '</p>';
    }

    function text($id, $name, $value, $title, $options = []) {
        echo '<p>';
        echo '<label for="' . $id . '">' . _e($title) . '</label> ';
        echo '<input class="widefat" id="' . $id . '" name="' . $name . '" type="text" value="' . $value . '" />';
        echo '</p>';
    }    
    function button($id, $name, $value, $title, $options = []) {
        echo '<p>';
        echo '<input class="widefat btn" id="' . $id . '" name="' . $name . '" type="button" value="' . $title . '" />';
        echo '</p>';
    }

    function date($id, $name, $value, $title, $options = []) {
        echo '<p>';
        echo '<label for="' . $id . '">' . _e($title) . '</label> ';
        echo '<input class="widefat" id="' . $id . '" name="' . $name . '" type="date" value="' . $value . '" />';
        echo '</p>';
    }
   function time($id, $name, $value, $title, $options = []) {
        echo '<p>';
        echo '<label for="' . $id . '">' . _e($title) . '</label> ';
        echo '<input class="widefat" id="' . $id . '" name="' . $name . '" type="time" value="' . esc_attr($value) . '" />';
        echo '</p>';
    }
    function textarea($id, $name, $value, $title, $options = []) {
        echo '<p>';
        echo '<label for="' . $id . '">' . _e($title) . '</label> ';
        echo '<textarea type="textarea" class="widefat" id="' . $id . '" name="' . $name . '">' . $value . '</textarea>';
        echo '</p>';
    }

    function color($id, $name, $value, $title, $options = []) {
        echo '<p>';
        echo '<label for="' . $id . '">' . _e($title) . '</label> ';
        echo '<input class="widefat" id="' . $id . '" name="' . $name . '" type="color" value="' . $value . '" />';
        echo '</p>';
    }

    function number($id, $name, $value, $title, $options = []) {
        echo '<p>';
        echo '<label for="' . $id . '">' . _e($title) . '</label> ';
        echo '<input class="widefat" id="' . $id . '" name="' . $name . '" type="number" value="' . $value . '" />';
        echo '</p>';
    }

    function group($id, $name, $value, $title, $fields) {
         echo '<div class="group-holder">';
            echo '<textarea style="display:none" name="' . $name . '" id="' . $id . '" class="group">' . esc_textarea($value) . '</textarea>';
            echo '<label class="wid-title">' . $title . '</label> ';
            echo '<div class="group-fields">';
            $arr = $value ? json_decode($value, true) : []; // Changed unserialize to json_decode
            if (!empty($arr)) {
                $i = 0;
                foreach ($arr as $i => $box_a) {
                    echo '<div class="box">';
                    echo '<div class="flex-between">';
                    echo '<span><num>' . ($i + 1) . '</num>' . (isset($box_a[$fields[0]['id']]) ? esc_html($box_a[$fields[0]['id']]) : '') . '</span>';
                    echo '<i class="fa-solid fa-arrow-left"></i>';
                    echo '</div>';
                    echo '<div class="all-fields">';
                    foreach ($fields as $box) {
                        $field = $box['type'];
                        $options = isset($box['options']) ? $box['options'] : [];
                        $field_value = isset($box_a[$box['id']]) ? $box_a[$box['id']] : '';
                        $this->$field($box['id'] . '_' . $i, $box['id'], $field_value, $box['title'], $options);
                    }
                    if ($i > 0) {
                        echo '<i class="fa-solid fa-xmark remove-box"></i>';
                    }
                    echo '</div>';
                    echo '</div>';
                    $i++;
                }
        } else {
            echo '<div class="box">';
            echo '<div class="flex-between">';
            echo '<span>' . (is_rtl() ? 'المكون' : 'component') . '</span>';
            echo '<i class="fa-solid fa-arrow-left"></i>';
            echo '</div>';
            echo '<div class="all-fields">';
            if (!empty($fields)) {
                foreach ($fields as $box) {
                    $field = $box['type'];
                    $options = isset($box['options']) ? $box['options'] : [];
                    $this->$field($box['id'] . '_0', $box['id'], '', $box['title'], $options);
                }
            }
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '<div class="add-more">';
        echo '<i class="fa-solid fa-plus"></i>';
        echo is_rtl() ? 'اضافة عنصر جديد' : 'add new box';
        echo '</div>';
        echo '</div>';
    }

    function checkbox_list($id, $name, $value, $title, $options = []) {
        echo '<div class="widget-box checkbox-list-' . $id . '">';
        echo '<label class="wid-title" for="' . $id . '">' . $title . '</label> ';
        echo '<input class="widefat" id="' . $id . '" name="' . $name . '" type="hidden" value="' . esc_attr($value) . '" />';
        echo '<div class="checkbox-list-holder">';
        $i = 0;
        $arr = [];
        if ($value != '') {
            $arr = array_filter(explode(',', $value));
        }
        foreach ($options as $key => $op) {
            echo '<div class="list-inline ' . (in_array($key, $arr) ? 'active' : '') . '" data-key="' . $key . '"><span><i class="fa-solid fa-check"></i></span>' . esc_html($op) . '</div>';
            $i++;
        }
        echo '</div>';
        echo '</div>';
    }

    function radio($id, $name, $value, $title, $options = []) {
        echo '<div class="widget-box checkbox-list-' . $id . '">';
        echo '<label class="wid-title" for="' . $id . '">' . $title . '</label> ';
        echo '<input class="widefat" id="' . $id . '" name="' . $name . '" type="hidden" value="' . $value . '" />';
        $i = 0;
        $arr = [];
        if ($value != '') {
            $arr = explode(',', $value);
        }
        foreach ($options as $key => $op) {
            echo '<div class="list-inline-radio ' . (in_array($key, $arr) ? 'active' : '') . '" data-key="' . $key . '"><span><i class="fa-solid fa-check"></i></span>' . $op . '</div>';
            $i++;
        }
        echo '</div>';
    }

    function file($id, $name, $value, $title, $options = []) {
        echo '<p>';
        echo '<label for="' . $id . '">' . _e($title) . '</label> ';
        echo '<input class="widefat" id="' . $id . '" name="' . $name . '" type="hidden" value="' . esc_attr($value) . '" />';
        echo '</p>';
        echo '<button class="btn btn-primary upload_btn" data-type="any">' .$title . '</button>';
        if ($value != '') {
            $filename = basename($value);
            $is_image = preg_match('/\.(jpg|jpeg|png|gif|bmp)$/i', $value);
            echo '<div class="file-preview">';
            echo '<span class="close"><i class="fa-solid fa-xmark"></i></span>';
            if ($is_image) {
                echo '<img src="' . esc_url($value) . '" style="max-width: 100px; max-height: 100px;" />';
            } else {
                echo '<a href="' . esc_url($value) . '" target="_blank">' . esc_html($filename) . '</a>';
            }
            echo '</div>';
        }
    }

    function file_list($id, $name, $value, $title, $options = []) {
        echo '<p>';
        echo '<label for="' . $id . '">' . _e($title) . '</label> ';
        echo '<input class="widefat" id="' . $id . '" name="' . $name . '" type="hidden" value="' . esc_attr($value) . '" />';
        echo '</p>';
        echo '<button class="btn btn-primary upload_btn" data-multiple="true" data-type="any">' . (is_rtl() ? 'رفع الملفات' : 'Upload Files') . '</button>';
        echo '<div class="all-files">';
        if ($value != '') {
            $arr = array_filter(explode(',', $value));
            foreach ($arr as $v) {
                $filename = basename($v);
                $is_image = preg_match('/\.(jpg|jpeg|png|gif|bmp)$/i', $v);
                echo '<div class="file-item">';
                echo '<span class="remove-file"><i class="fa-solid fa-xmark"></i></span>';
                if ($is_image) {
                    echo '<img src="' . esc_url($v) . '" style="max-width: 100px; max-height: 100px;" />';
                } else {
                    echo '<a href="' . esc_url($v) . '" target="_blank">' . esc_html($filename) . '</a>';
                }
                echo '</div>';
            }
        }
        echo '</div>';
    }


    function editor($id, $name, $value, $title, $options = []) {
        // Ensure WordPress editor scripts are enqueued
        wp_enqueue_editor();
        wp_enqueue_media(); // Required for media uploads
        wp_enqueue_script('jquery');
        wp_enqueue_script('wp-tinymce');
        wp_enqueue_style('editor-buttons');

        $unique_id = sanitize_html_class($id . '_' . uniqid());
        $settings = wp_parse_args($options, [
            'textarea_rows' => 10,
            'editor_class' => 'wp-editor-textarea',
            'media_buttons' => true, // Enable media buttons
            'tinymce' => [
                'wpautop' => false,
                'plugins' => 'lists,link,image,media,wplink,wpdialogs,wordpress,wptextpattern',
                'toolbar1' => 'bold italic bullist numlist link image media',
                'toolbar2' => 'formatselect alignleft aligncenter alignright',
                'image_advtab' => true, // Enable advanced image options
                'image_title' => true, // Enable image title field
                'image_caption' => true, // Enable image captions
            ],
            'quicktags' => true,
        ]);

        ?>
        <div class="editor-holder" data-editor-id="<?php echo esc_attr($unique_id); ?>">
            <p>
                <label for="<?php echo esc_attr($unique_id); ?>"><?php echo esc_html($title); ?></label>
                <input type="hidden" class="editor-content" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($value); ?>" />
                <textarea class="<?php echo esc_attr($settings['editor_class']); ?>" id="<?php echo esc_attr($unique_id); ?>" rows="<?php echo esc_attr($settings['textarea_rows']); ?>"><?php echo esc_textarea($value); ?></textarea>
            </p>
        </div>
        <script>
            (function($) {
                function initializeEditor() {
                    if (typeof wp !== 'undefined' && typeof wp.editor !== 'undefined' && typeof tinymce !== 'undefined') {
                        wp.editor.remove('<?php echo esc_js($unique_id); ?>');
                        wp.editor.initialize('<?php echo esc_js($unique_id); ?>', {
                            tinymce: <?php echo json_encode($settings['tinymce']); ?>,
                            quicktags: <?php echo json_encode($settings['quicktags']); ?>,
                            mediaButtons: <?php echo json_encode($settings['media_buttons']); ?>
                        });

                        // Custom media upload handler
                        $(document).on('click', '#<?php echo esc_js($unique_id); ?>-media-button', function(e) {
                            e.preventDefault();
                            var custom_uploader = wp.media({
                                title: 'Insert Media',
                                button: {
                                    text: 'Insert'
                                },
                                multiple: false
                            }).on('select', function() {
                                var attachment = custom_uploader.state().get('selection').first().toJSON();
                                var editor = tinymce.get('<?php echo esc_js($unique_id); ?>');
                                if (editor) {
                                    if (attachment.type === 'image') {
                                        editor.insertContent(
                                            '<img src="' + attachment.url + '"' +
                                            ' alt="' + (attachment.alt || '') + '"' +
                                            ' title="' + (attachment.title || '') + '"' +
                                            (attachment.caption ? ' class="wp-caption"' : '') + '>' +
                                            (attachment.caption ? '<p class="wp-caption-text">' + attachment.caption + '</p>' : '')
                                        );
                                    } else {
                                        editor.insertContent('<a href="' + attachment.url + '">' + attachment.title + '</a>');
                                    }
                                }
                            }).open();
                        });

                        // Sync content to hidden input
                        setTimeout(function() {
                            var editor = tinymce.get('<?php echo esc_js($unique_id); ?>');
                            if (editor) {
                                editor.on('change keyup', function() {
                                    $('#<?php echo esc_js($id); ?>').val(editor.getContent()).trigger('change');
                                });
                            } else {
                                console.error('TinyMCE editor not initialized for ID: <?php echo esc_js($unique_id); ?>');
                            }
                        }, 500);
                    } else {
                        console.warn('WP Editor or TinyMCE not available. Retrying in 500ms...');
                        setTimeout(initializeEditor, 500);
                    }
                }

                $(document).ready(function() {
                    initializeEditor();
                });
            })(jQuery);
        </script>
        <?php
    }

    function image($src, $alt) {
        return '<img src="' . esc_url($src) . '" alt="' . esc_attr($alt) . '">';
    }
    
}