<?php
/* Template Name: Forgot Password */
get_header();
?>

<!-- MATRIX RAIN BACKGROUND -->
<canvas id="matrixRain"></canvas>

<!-- FORGOT PASSWORD TERMINAL WINDOW -->
<div class="terminal-container">
    <div class="terminal-header">
        <span class="terminal-title">Reset Your Password</span>
    </div>

    <div class="terminal-body">
        <div class="logo-container">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="HackDome Logo" class="logo">
        </div>

        <?php if (isset($_GET['checkemail']) && $_GET['checkemail'] === 'confirm') : ?>
            <p class="terminal-text success">✅ If the email exists, a reset link has been sent.</p>
        <?php else : ?>
            <p class="terminal-text">[+] Enter your username or email to receive a reset link:</p>
            <form method="post" action="<?php echo esc_url(site_url('wp-login.php?action=lostpassword', 'login_post')); ?>">
                <label for="user_login">Email or Username</label>
                <input type="text" name="user_login" id="user_login" required placeholder="Enter email or username">

                <button type="submit" class="main-button-login">Send Reset Link</button>
            </form>
        <?php endif; ?>

        <p class="terminal-text">[+] Back to <a href="<?php echo esc_url(home_url('/login')); ?>">Login</a>.</p>
    </div>
</div>

<script src="<?php echo get_template_directory_uri(); ?>/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/matrix-rain.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[action*="lostpassword"]');
    if (form) {
        form.addEventListener('submit', function (e) {
            setTimeout(() => {
                window.location.href = window.location.href.split('?')[0] + '?checkemail=confirm';
            }, 500);
        });
    }
});
</script>

<?php get_footer(); ?>
