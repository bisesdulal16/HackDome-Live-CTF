<?php
/* Template Name: Forgot Password */
get_header();
?>

<div class="terminal-container">
    <div class="terminal-header">
        <span class="terminal-title">Forgot Password</span>
    </div>

    <div class="terminal-body">

        <?php if (isset($_GET['reset']) && $_GET['reset'] === 'true') : ?>
            <p class="terminal-text success"><i class="fa fa-check-circle"></i> Request submitted!</p>
            <p class="terminal-text">If your account exists, you will receive an email.</p>
            <p class="terminal-text">Check your spam folder or try another email.</p>
            <div class="main-button-login" style="margin-top: 20px;">
                <a href="<?php echo wp_login_url(); ?>" style="color: #fff; text-decoration: none;">Back to Login</a>
            </div>
        <?php else : ?>
            <p class="terminal-text">Enter your email below to receive a reset link.</p>

            <form action="" method="post" class="register-form">
                <label for="user_login">Email Address</label>
                <input type="email" name="user_login" id="user_login" required placeholder="you@example.com" />

                <button type="submit" name="submit" class="main-button-login">Request Reset Link</button>
            </form>
        <?php endif; ?>

        <?php
        // Form Submission
        if (isset($_POST['user_login'])) {
            $user_login = sanitize_email($_POST['user_login']);
            if (!empty($user_login)) {
                $user = get_user_by('email', $user_login);
                if ($user) {
                    // Send password reset email
                    $reset = retrieve_password($user_login);
                    if ($reset) {
                        wp_redirect(add_query_arg('reset', 'true', get_permalink()));
                        exit;
                    } else {
                        echo '<p class="terminal-text error">Something went wrong. Try again.</p>';
                    }
                } else {
                    // Still redirect to avoid revealing user presence
                    wp_redirect(add_query_arg('reset', 'true', get_permalink()));
                    exit;
                }
            } else {
                echo '<p class="terminal-text error">Please enter a valid email.</p>';
            }
        }
        ?>

    </div>
</div>

<?php get_footer(); ?>
