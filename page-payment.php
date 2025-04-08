<?php
/* Template Name: Payment */
get_header();
?>

<!-- MATRIX RAIN BACKGROUND -->
<canvas id="matrixRain"></canvas>

<!-- PAYMENT TERMINAL WINDOW -->
<div class="terminal-container">
    <div class="terminal-header">
        <span class="terminal-title">HackDome Payment Portal</span>
    </div>
    <div class="terminal-body">

        <div class="logo-container">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="HackDome Logo" class="logo">
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'cancel') : ?>
            <div class="payment-error">
                <p class="terminal-text error">
                    <i class="fa fa-times-circle"></i> Payment was cancelled. Please try again.
                </p>
            </div>
        <?php endif; ?>

        <p class="terminal-text">[+] Your account has been created. Please complete your payment to activate HackDome access.</p>

        <!-- Stripe Payment Integration -->
        <div class="payment-section">
            <?php
            echo do_shortcode('[accept_stripe_payment 
                name="HackDome Membership" 
                price="9.99" 
                currency="USD" 
                description="Monthly HackDome Subscription" 
                button_text="Complete Payment" 
                success_url="' . esc_url(home_url('/post-payment')) . '" 
                cancel_url="' . esc_url(home_url('/payment?status=cancel')) . '"
                class="custom-stripe-button"]');
            ?>
        </div>

    </div>
</div>

<!-- Scripts -->
<script src="<?php echo get_template_directory_uri(); ?>/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/matrix-rain.js"></script>

<?php get_footer(); ?>
