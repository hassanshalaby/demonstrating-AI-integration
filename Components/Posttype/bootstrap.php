<?php 
namespace AIintegration\Components\Posttype;

trait Bootstrap {

    function posttype__construct(){
        add_action( 'init', array( $this, 'init_post_types' ) );      
    }

    function init_post_types(){
        $this->add_post_type('Community Discussions','Community Discussion','cmd',true,['title','editor']);
    }

    function add_post_type($name="", $singular="", $id="", $rewrite=false, $supports=array(), $public=true, $taxonomies=array(), $capabilities = [], $show_in_menu = true) {
        $labels = array(
            'name'                  => $name,
            'singular_name'         => $singular,
            'menu_name'             => $name,
            'name_admin_bar'        => $singular,
            'add_new'               => 'Add New',
            'add_new_item'          => 'Add New ' . $singular,
            'new_item'              => 'New ' . $singular,
            'edit_item'             => 'Edit ' . $singular,
            'view_item'             => 'View ' . $singular,
            'all_items'             => 'All ' . $name,
            'search_items'          => 'Search ' . $name,
            'parent_item_colon'     => 'Parent ' . $singular . ':',
            'not_found'             => 'No ' . $name . ' found',
            'not_found_in_trash'    => 'No ' . $name . ' found in Trash',
            'archives'              => $singular . ' Archives',
            'insert_into_item'      => 'Insert into ' . $singular,
            'uploaded_to_this_item' => 'Uploaded to this ' . $singular,
            'filter_items_list'     => 'Filter ' . $name . ' list',
            'items_list_navigation' => $name . ' list navigation',
            'items_list'            => $name . ' list'
        );
    
        $args = [
            'labels'              => $labels,
            'hierarchical'        => false,
            'description'         => 'description',
            'taxonomies'          => $taxonomies,
            'public'              => $public,
            'show_ui'             => true,
            'show_in_menu'        => $show_in_menu,
            'show_in_admin_bar'   => true,
            'menu_position'       => null,
            'menu_icon'           => null,
            'show_in_nav_menus'   => true,
            'publicly_queryable'  => true,
            'exclude_from_search' => false,
            'map_meta_cap'        => true,
            'has_archive'         => true,
            'query_var'           => true,
            'can_export'          => true,
            'rewrite'             => $rewrite,
            'supports'            => $supports,
            'capability_type'     => 'post'
        ];
    
        if (!empty($capabilities)) {
            $args['capabilities'] = $capabilities;
        }
    
        register_post_type($id, $args);
    }

}
