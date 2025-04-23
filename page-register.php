<?php
/* Template Name: Register */
get_header();
?>

<style>
    .password-hint {
        font-size: 12px;
        color: #888;
        margin-top: 5px;
    }
    .strength-text {
        font-size: 13px;
        margin-top: 5px;
        font-weight: 600;
    }
    .weak { color: #ff4c4c; }
    .medium { color: #ffc107; }
    .strong { color: #28a745; }
</style>

<canvas id="matrixRain"></canvas>

<div class="terminal-container">
    <div class="terminal-header">
        <span class="terminal-title">Register for HackDome</span>
    </div>

    <div class="terminal-body">
        <div class="logo-container">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="HackDome Logo" class="logo">
        </div>

        <?php
        $has_paid = get_user_meta(get_current_user_id(), 'hackdome_payment_status', true) === 'completed';

        if (is_user_logged_in() && $has_paid) : ?>
            <p class="terminal-text error">You are already registered and subscribed. <a href="<?php echo esc_url(home_url('/')); ?>">Go to Homepage</a></p>

        <?php elseif (is_user_logged_in() && !$has_paid) : ?>
            <p class="terminal-text error">You are already registered but haven’t completed payment. <a href="<?php echo esc_url(home_url('/payment')); ?>">Complete Payment</a> or <a href="<?php echo wp_logout_url(home_url('/register')); ?>">Logout</a></p>

        <?php else : ?>
            <form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post">
                <label for="username">Username</label>
                <input type="text" name="username" placeholder="Username" required>

                <label for="email">Email Address</label>
                <input type="email" name="email" placeholder="example@example.com" required>

                <label for="password">Password</label>
                
                <div class="password-container" style="position: relative;">
                    <input type="password" name="password" id="password" placeholder="Password" required onkeyup="checkPasswordStrength()">
                    <span id="togglePassword" class="show-password">🙈</span>
                    <div id="password-strength-text" class="strength-text"></div>
                </div>
                <div class="password-hint terminal-text">Must be at least 8 characters, with a mix of letters and numbers.</div>

                <label for="plan">Select a Plan</label>
                <select name="plan" required>
                    <option value="basic">Basic - $9.99/month</option>
                    <option value="pro" disabled>Pro - Coming Soon</option>
                    <option value="elite" disabled>Elite - Coming Soon</option>
                </select>

                <div class="g-recaptcha" data-sitekey="6Lca9yArAAAAACp26jolLMHFyTFzu-1y2K1NVcoD"></div><br>
                <button type="submit" name="submit_registration" class="main-button-login">Sign Up</button>
                <p class="terminal-text">Forgot password? <a href="<?php echo esc_url(home_url('/forgot-password')); ?>">Reset here</a>.</p>
            </form>

            <?php
            if (isset($_POST['submit_registration'])) {

                $recaptcha_response = $_POST['g-recaptcha-response'];
                $secret_key = '6Lca9yArAAAAAE5I9zbiJJGoVZNDd8lCkQhC8P2z';

                $response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$recaptcha_response");
                $response_body = wp_remote_retrieve_body($response);
                $result = json_decode($response_body);

                if (!$result->success) {
                    echo '<p class="terminal-text error">❌ CAPTCHA failed. Please try again.</p>';
                    return;
                }

                $username = sanitize_user($_POST['username']);
                $email    = sanitize_email($_POST['email']);
                $password = $_POST['password'];
                $plan     = sanitize_text_field($_POST['plan']);

                if (username_exists($username) || email_exists($email)) {
                    echo '<p class="terminal-text error">❌ Username or Email already exists.</p>';
                } else {
                    $user_id = wp_create_user($username, $password, $email);
                    if (!is_wp_error($user_id)) {
                        wp_set_current_user($user_id);
                        wp_set_auth_cookie($user_id);
                        echo '<script>window.location.href = "' . home_url('/payment?plan=') . $plan . '";</script>';
                        exit;
                    } else {
                        echo '<p class="terminal-text error">❌ Error creating account. Please try again.</p>';
                    }
                }
            }
            ?>

            <p class="terminal-text">[+] Already have an account? <a href="<?php echo home_url('/login'); ?>">Log in here</a>.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Password Strength + Toggle -->
<script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    function checkPasswordStrength() {
        const strengthText = document.getElementById("password-strength-text");
        const password = document.getElementById("password").value;
        let strength = 0;

        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[\W]/.test(password)) strength++;

        if (strength <= 2) {
            strengthText.textContent = "Weak 🔴";
            strengthText.style.color = "#ff4c4c";
        } else if (strength === 3 || strength === 4) {
            strengthText.textContent = "Moderate 🟠";
            strengthText.style.color = "#ff9900";
        } else {
            strengthText.textContent = "Strong 🟢";
            strengthText.style.color = "#00cc66";
        }
    }
</script>

<script src="<?php echo get_template_directory_uri(); ?>/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/matrix-rain.js"></script>

<?php get_footer(); ?>
