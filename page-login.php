<?php
/* Template Name: Login */
get_header();
?>



<!-- MATRIX RAIN BACKGROUND -->
<canvas id="matrixRain"></canvas>

<!-- LOGIN TERMINAL WINDOW -->
<div class="terminal-container">
    <div class="terminal-header">
        <span class="terminal-title">HackDome Secure Login</span>
    </div>

    <div class="terminal-body">

        <!-- LOGO -->
        <div class="logo-container">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="HackDome Logo" class="logo">
        </div>

        <p class="terminal-text">[+] Welcome to HackDome. Please authenticate.</p>

        <?php
        if (is_user_logged_in()) {
            echo '<p class="terminal-text">[+] You are already logged in. <a href="' . esc_url(home_url('/')) . '">Go to Homepage</a></p>';
        } else {
            ?>
            <form method="post" id="custom-login-form">
                <label for="log">Username</label>
                <input type="text" name="log" id="log" placeholder="Enter Username" required>

                <label for="pwd">Password</label>
                    <div class="password-container">
                        <input type="password" name="pwd" id="pwd" placeholder="Enter Password" required>
                        <span id="togglePassword" class="show-password">🙈</span>
                    </div>

                <label>
                    <input type="checkbox" name="rememberme" value="forever"> Remember Me
                </label>
                <div class="g-recaptcha" data-sitekey="6Lca9yArAAAAACp26jolLMHFyTFzu-1y2K1NVcoD"></div><br>
                <button type="submit" name="wp-submit" class="main-button-login">Login</button>
            
                <p class="forgot-password"><a href="<?php echo site_url('/forgot-password'); ?>">Forgot Password?</a></p> 
            </form>

            <?php
        }

        // Handle login
        if (isset($_POST['log']) && isset($_POST['pwd'])) {
            // ✅ Add reCAPTCHA validation here
            $recaptcha_response = $_POST['g-recaptcha-response'];
            $secret_key = '6Lca9yArAAAAAE5I9zbiJJGoVZNDd8lCkQhC8P2z';

            $response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$recaptcha_response");
            $response_body = wp_remote_retrieve_body($response);
            $result = json_decode($response_body);

            if (!$result->success) {
                echo '<p class="terminal-text error">❌ CAPTCHA failed. Please try again.</p>';
                return;
            }

            $creds = array(
                'user_login'    => sanitize_user($_POST['log']),
                'user_password' => $_POST['pwd'],
                'remember'      => isset($_POST['rememberme'])
            );

            $user = wp_signon($creds, false);

            if (is_wp_error($user)) {
                echo '<p class="terminal-text error">❌ Invalid login. Please try again.</p>';
            } else {
                // Check miniOrange 2FA status
                $has_2fa = get_user_meta($user->ID, 'mo2f_2FA_status', true);

                if ($has_2fa !== 'enabled') {
                    // Directly trigger the miniOrange 2FA setup flow via shortcode
                    echo '<div class="terminal-text">[+] Setup your 2FA:</div>';
                    echo do_shortcode('[mo2f_setup_2fa]'); // This renders the miniOrange 2FA setup
                } else {
                    // Show 2FA Code Prompt on the same page
                    echo '<form method="post" class="mfa-form">';
                    echo '<p class="terminal-text">[+] Enter 2FA Code from Google Authenticator:</p>';
                    echo '<input type="text" name="mfa_code" placeholder="Enter 6-digit code" required />';
                    echo '<button type="submit" class="main-button-login">Verify</button>';
                    echo '</form>';
                }
            }
        }

        // Verify 2FA code using miniOrange
        if (isset($_POST['mfa_code'])) {
            $mfa_code = sanitize_text_field($_POST['mfa_code']);
            $user = wp_get_current_user();

            if (function_exists('mo2f_verify_otp')) {
                $result = mo2f_verify_otp($user->ID, $mfa_code);

                if ($result['status'] === 'success') {
                    echo '<p class="terminal-text success"><i class="fa fa-check-circle"></i> 2FA Verification Successful! Redirecting...</p>';
                    echo '<script>setTimeout(function(){ window.location.href="' . home_url('/') . '"; }, 2000);</script>';
                } else {
                    echo '<p class="terminal-text error">❌ Invalid 2FA Code. Please try again.</p>';
                }
            } else {
                echo '<p class="terminal-text error">❌ 2FA verification failed. Please contact support.</p>';
            }
        }
        ?>

        <?php if (!is_user_logged_in()) : ?>
            <p class="terminal-text">[+] New Here? <a href="<?php echo site_url('/register'); ?>">Create an account</a> or <a href="#subscription-section">Choose a plan</a></p>
        <?php endif; ?>
    </div>
</div>
<!-- SUBSCRIPTION TERMINAL -->
<div id="subscription-section" class="terminal-container-subscription">
    <div class="terminal-header">
        <span class="terminal-title">HackDome Subscription Plans</span>
    </div>
    <div class="terminal-body">
        <p class="terminal-text">[+] Choose your plan to access exclusive features.</p>

        <div class="subscription-options">
            <div class="subscription-option">
                <h3>Basic Plan</h3>
                <p class="price">$9.99 / month</p>
                <ul>
                    <li><span>&#10004;</span> Access to Live CTFs</li><br>
                    <li><span>&#10004;</span> Basic Challenge Library</li><br>
                    <li><span>&#10004;</span> Community Support</li><br>
                </ul>
                <a href="<?php echo site_url('/register'); ?>" class="subscribe-button">Subscribe</a>
            </div>

            <div class="subscription-option coming-soon">
                <h3>Pro Plan (Coming Soon)</h3>
                <p class="price">$19.99 / month</p>
                <ul>
                    <li><span>&#10004;</span> Advanced CTF Challenges</li><br>
                    <li><span>&#10004;</span> Priority Support</li><br>
                    <li><span>&#10004;</span> Exclusive Webinars</li><br>
                </ul>
                <button class="subscribe-button disabled">Coming Soon</button>
            </div>

            <div class="subscription-option coming-soon">
                <h3>Elite Plan (Coming Soon)</h3>
                <p class="price">$29.99 / month</p>
                <ul>
                    <li><span>&#10004;</span> All Features Unlocked</li><br>
                    <li><span>&#10004;</span> One-on-One Coaching</li><br>
                    <li><span>&#10004;</span> Custom Lab Access</li><br>
                </ul>
                <button class="subscribe-button disabled">Coming Soon</button>
            </div>
        </div>
    </div>
</div>

<!-- SHOW PASSWORD SCRIPT -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('pwd');

        togglePassword.addEventListener('click', function () {
            if (passwordField.type === "password") {
                passwordField.type = "text";
                togglePassword.innerHTML = "👁️"; // Eye icon
            } else {
                passwordField.type = "password";
                togglePassword.innerHTML = "🙈"; // Closed eye icon
            }
        });

        // Prevent empty form submission
        document.getElementById('custom-login-form').addEventListener('submit', function (e) {
            const username = document.getElementById('log').value;
            const password = document.getElementById('pwd').value;
            if (username.trim() === '' || password.trim() === '') {
                e.preventDefault();
                alert('Both Username and Password fields are required!');
            }
        });
    });
</script>
<script src="<?php echo get_template_directory_uri(); ?>/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/matrix-rain.js"></script>
<?php get_footer(); ?>
