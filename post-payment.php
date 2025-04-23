<?php
// Ensure user is logged in
if (is_user_logged_in()) {
    $user_id = get_current_user_id();

    // Check if meta already marked as paid
    $already_paid = get_user_meta($user_id, 'hackdome_payment_status', true);

    // If not already set, mark payment as completed
    if ($already_paid !== 'completed') {
        update_user_meta($user_id, 'hackdome_payment_status', 'completed');
    }
}
?>

<?php
/* Template Name: Post Payment */
get_header();
?>

<!-- MATRIX RAIN BACKGROUND -->
<canvas id="matrixRain"></canvas>

<!-- POST PAYMENT SUCCESS WINDOW -->
<div class="terminal-container">
    <div class="terminal-header">
        <span class="terminal-title">Payment Successful</span>
    </div>
    <div class="terminal-body">
        <div class="logo-container">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="HackDome Logo" class="logo">
        </div>

        <p class="terminal-text success"><i class="fa fa-check-circle"></i> Payment completed successfully.</p>
        <p class="terminal-text">[+] You're now logged in. Start exploring the challenges and your profile.</p>

        <div class="main-button-login">
            <a href="<?php echo home_url('/challenges'); ?>" style="color: #fff; text-decoration: none;">
                <i class="fa fa-flag-checkered"></i> Go to Challenges
            </a>
        </div>

        <br>
        <div class="main-button-login" style="background-color: #333;">
            <a href="<?php echo home_url('/profile'); ?>" style="color: #fff; text-decoration: none;">
                <i class="fa fa-user-circle"></i> View Profile
            </a>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="<?php echo get_template_directory_uri(); ?>/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/matrix-rain.js"></script>

<?php get_footer(); ?>
