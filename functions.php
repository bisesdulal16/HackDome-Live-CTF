<?php
// ==============================
// HackDome Theme Functions
// ==============================

// 1️⃣ -- Theme Setup
function hackdome_theme_setup() {
    add_theme_support('post-thumbnails');
    register_nav_menus(array(
        'primary_menu' => __('Primary Menu', 'hackdome'),
        'footer_menu'  => __('Footer Menu', 'hackdome'),
    ));
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
}
add_action('after_setup_theme', 'hackdome_theme_setup');


// 2️⃣ -- Enqueue CSS & JS Files
function hackdome_enqueue_scripts() {
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap', array(), null);
    wp_enqueue_style('bootstrap-css', get_template_directory_uri() . '/vendor/bootstrap/css/bootstrap.min.css', array(), '4.5.2');
    wp_enqueue_style('fontawesome-css', get_template_directory_uri() . '/assets/css/fontawesome.css', array(), '5.15.4');
    wp_enqueue_style('animate-css', get_template_directory_uri() . '/assets/css/animate.css', array(), '4.1.1');
    wp_enqueue_style('owl-css', get_template_directory_uri() . '/assets/css/owl.css', array(), '2.3.4');
    wp_enqueue_style('flex-slider-css', get_template_directory_uri() . '/assets/css/flex-slider.css', array(), '2.7.2');
    wp_enqueue_style('templatemo-cyborg-css', get_template_directory_uri() . '/assets/css/templatemo-cyborg-gaming.css', array(), '1.0');
    wp_enqueue_style('hackdome-style', get_stylesheet_uri());
    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/vendor/bootstrap/js/bootstrap.min.js', array('jquery'), '5.3.0', true);
    wp_enqueue_script('isotope', get_template_directory_uri() . '/assets/js/isotope.min.js', array('jquery'), '3.0.6', true);
    wp_enqueue_script('owl-carousel', get_template_directory_uri() . '/assets/js/owl-carousel.js', array('jquery'), '2.3.4', true);
    wp_enqueue_script('tabs', get_template_directory_uri() . '/assets/js/tabs.js', array('jquery'), '1.0', true);
    wp_enqueue_script('popup', get_template_directory_uri() . '/assets/js/popup.js', array('jquery'), '1.0', true);
    wp_enqueue_script('custom', get_template_directory_uri() . '/assets/js/custom.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'hackdome_enqueue_scripts');


// 3️⃣ -- Register Custom Post Type: CTF Challenges
function hackdome_register_ctf_post_type() {
    $labels = array(
        'name' => __('CTF Challenges', 'hackdome'),
        'singular_name' => __('CTF Challenge', 'hackdome'),
        'add_new' => __('Add New Challenge', 'hackdome'),
        'add_new_item' => __('Add New CTF Challenge', 'hackdome'),
        'edit_item' => __('Edit CTF Challenge', 'hackdome'),
        'new_item' => __('New CTF Challenge', 'hackdome'),
        'view_item' => __('View CTF Challenge', 'hackdome'),
        'all_items' => __('All CTF Challenges', 'hackdome'),
        'search_items' => __('Search CTF Challenges', 'hackdome'),
        'not_found' => __('No CTF Challenges found', 'hackdome'),
        'not_found_in_trash' => __('No CTF Challenges found in Trash', 'hackdome'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'ctf-challenges'),
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'menu_icon' => 'dashicons-shield-alt',
    );

    register_post_type('ctf_challenge', $args);
}
add_action('init', 'hackdome_register_ctf_post_type');


// 4️⃣ -- Register Widget Areas
function hackdome_register_sidebars() {
    register_sidebar(array(
        'name' => __('Sidebar', 'hackdome'),
        'id' => 'main-sidebar',
        'description' => __('Main Sidebar', 'hackdome'),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => __('Footer Widgets', 'hackdome'),
        'id' => 'footer-widgets',
        'description' => __('Widgets in the footer', 'hackdome'),
        'before_widget' => '<div class="footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h5 class="widget-title">',
        'after_title' => '</h5>',
    ));
}
add_action('widgets_init', 'hackdome_register_sidebars');


// 5️⃣ -- Add Custom Excerpt Length
function hackdome_custom_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'hackdome_custom_excerpt_length');


// 6️⃣ -- Enable AJAX for Dynamic CTF Filtering
function hackdome_enqueue_ajax_scripts() {
    wp_localize_script('custom', 'hackdome_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'hackdome_enqueue_ajax_scripts');

add_action('wp_ajax_filter_ctfs', 'hackdome_filter_ctfs');
add_action('wp_ajax_nopriv_filter_ctfs', 'hackdome_filter_ctfs');

function hackdome_filter_ctfs() {
    $category = $_POST['category'];
    $args = array('post_type' => 'ctf_challenge', 'posts_per_page' => -1);
    if ($category != 'all') {
        $args['meta_query'] = array(
            array(
                'key' => 'ctf_category',
                'value' => $category,
                'compare' => '=',
            ),
        );
    }
    $ctf_query = new WP_Query($args);
    if ($ctf_query->have_posts()) :
        while ($ctf_query->have_posts()) : $ctf_query->the_post(); ?>
            <div class="ctf-item" data-category="<?php echo esc_attr(get_post_meta(get_the_ID(), 'ctf_category', true)); ?>">
                <h4><?php the_title(); ?></h4>
                <p><?php the_excerpt(); ?></p>
                <a href="<?php the_permalink(); ?>">Join Challenge</a>
            </div>
        <?php endwhile;
    else :
        echo '<p>No CTFs found.</p>';
    endif;
    wp_die();
}

// 7️⃣ -- Disable WordPress Emoji Script
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// 8️⃣ -- Hide WordPress Version Number
remove_action('wp_head', 'wp_generator');

function hackdome_login_redirect($redirect_to, $request, $user) {
    if (isset($user->roles) && is_array($user->roles)) {
        if (in_array('subscriber', $user->roles)) {
            return home_url('/index.php');
        }
    }
    return $redirect_to;
}
add_filter('login_redirect', 'hackdome_login_redirect', 10, 3);

add_action('woocommerce_thankyou', 'hackdome_redirect_after_payment');
function hackdome_redirect_after_payment($order_id){
    $order = wc_get_order($order_id);
    foreach ($order->get_items() as $item) {
        if ($item->get_product_id() == 123) {
            wp_safe_redirect(home_url('/payment-success'));
            exit;
        }
    }
}

// ✅ REST API for Flag Submission
add_action('rest_api_init', function () {
    register_rest_route('hackdome/v1', '/submit-flag', array(
        'methods' => 'POST',
        'callback' => 'hackdome_submit_flag',
        'permission_callback' => function () {
            return is_user_logged_in();
        },
    ));
});

function hackdome_submit_flag($request) {
    $flag = sanitize_text_field($request->get_param('flag'));
    $user_id = get_current_user_id();

    $correct_flags = array('flag{pwned123}', 'flag{redteam2025}');

    if (in_array($flag, $correct_flags)) {
        $current_score = (int) get_user_meta($user_id, 'ctf_points', true);
        update_user_meta($user_id, 'ctf_points', $current_score + 100);

        return new WP_REST_Response(array(
            'success' => true,
            'message' => 'Correct flag! 100 points awarded.',
        ), 200);
    } else {
        return new WP_REST_Response(array(
            'success' => false,
            'message' => 'Incorrect flag. Try again.',
        ), 200);
    }
}
