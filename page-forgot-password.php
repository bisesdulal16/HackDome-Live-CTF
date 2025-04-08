<?php
/* Template Name: Forgot Password */
get_header();
?>

<div class="forgot-password-container">
    <h2>Forgot Password</h2>
    <p>Enter your email below to receive a link to reset your password.</p>

    <?php if (isset($_GET['reset']) && $_GET['reset'] === 'true') : ?>
        <div class="success-message">
            <h3>Check your inbox</h3>
            <p>Request submitted!<br>If your account exists, you will receive an email.</p>
            <a href="<?php echo wp_login_url(); ?>">Back to Login</a>
            <p class="note">Didn't receive an email?<br>Check your spam folder or try another email.</p>
        </div>
    <?php else : ?>
        <form method="post">
            <label for="user_email">Email Address</label>
            <input type="email" name="user_email" id="user_email" required>
            <button type="submit" name="submit">Request Reset Link</button>
        </form>
    <?php endif; ?>
</div>

<?php
// Handle form submission
if (isset($_POST['submit'])) {
    $user_email = sanitize_email($_POST['user_email']);
    if (!empty($user_email)) {
        $user = get_user_by('email', $user_email);
        if ($user) {
            $reset_link = wp_lostpassword_url();
            wp_redirect(home_url('/forgot-password?reset=true'));
            exit;
        } else {
            // Still redirect to avoid email enumeration
            wp_redirect(home_url('/forgot-password?reset=true'));
            exit;
        }
    }
}

get_footer();
?>
