<?php
/* Template Name: Single CTF Challenge */
get_header();
?>

<style>
.single-ctf-container {
    max-width: 900px;
    margin: 60px auto;
    background: #1f1f1f;
    border-radius: 15px;
    padding: 40px;
    box-shadow: 0 0 20px rgba(255, 0, 255, 0.1);
    color: #fff;
    font-family: 'Poppins', sans-serif;
}
.single-ctf-container .ctf-header {
    display: flex;
    align-items: center;
    gap: 25px;
    margin-bottom: 30px;
}
.single-ctf-container .ctf-header img {
    width: 180px;
    height: 180px;
    border-radius: 10px;
    object-fit: cover;
    border: 2px solid #ff4c9b;
}
.single-ctf-container .ctf-header .info {
    flex: 1;
}
.single-ctf-container .ctf-header .info h1 {
    font-size: 28px;
    color: #ff4c9b;
    margin-bottom: 10px;
}
.single-ctf-container .ctf-header .info .meta {
    font-size: 14px;
    color: #ccc;
}
.single-ctf-container .description {
    margin-bottom: 30px;
    font-size: 16px;
    line-height: 1.6;
}
.single-ctf-container .launch-btn {
    display: block;
    width: fit-content;
    margin: 0 auto 30px;
    padding: 12px 25px;
    background: #ff4c9b;
    color: white;
    font-weight: bold;
    border-radius: 8px;
    text-decoration: none;
    text-align: center;
}
.flag-form {
    max-width: 500px;
    margin: 0 auto;
    text-align: center;
}
.flag-form label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
}
.flag-form input {
    width: 100%;
    padding: 12px;
    border-radius: 6px;
    border: none;
    background: #2b2b2b;
    color: white;
    margin-bottom: 15px;
}
.flag-form button {
    width: 100%;
    padding: 12px;
    font-weight: bold;
    background: #00ff99;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    color: #000;
}
.success-message {
    margin-top: 15px;
    font-weight: bold;
    text-align: center;
}
.success-message.pass {
    color: #00ff99;
}
.success-message.fail {
    color: #ff4c4c;
}
</style>

<div class="single-ctf-container">
    <div class="ctf-header">
        <div class="thumb">
            <?php
            if (has_post_thumbnail()) {
                the_post_thumbnail('medium');
            } else {
                echo '<img src="' . get_template_directory_uri() . '/assets/images/default-box.jpg" alt="CTF Image">';
            }
            ?>
        </div>
        <div class="info">
            <h1><?php the_title(); ?></h1>
            <div class="meta">
                Difficulty: <?php echo esc_html(get_field('difficulty')); ?> |
                Players: <?php echo esc_html(get_field('players_count')); ?> |
                Rating: <?php echo esc_html(get_field('rating')); ?>
            </div>
        </div>
    </div>

    <div class="description">
        <?php the_excerpt(); ?>
    </div>

    <?php
    $challenge_url = get_field('challenge_url');
    if ($challenge_url) :
    ?>
        <a href="<?php echo esc_url($challenge_url); ?>" class="launch-btn" target="_blank" rel="noopener noreferrer">
            🚀 Launch Challenge
        </a>
    <?php endif; ?>

    <div class="flag-form">
        <form method="post">
            <label for="flag">🔑 Submit Flag</label>
            <input type="text" name="flag" placeholder="Enter your flag here..." required>
            <button type="submit" name="submit_flag">Verify Flag</button>
        </form>

        <?php
        if (isset($_POST['submit_flag'])) {
            $submitted = trim($_POST['flag']);
            $correct = trim(get_field('challenge_flag'));
            if ($submitted === $correct) {
                echo '<p class="success-message pass">🎉 Congratulations! You have successfully completed this box.</p>';
            } else {
                echo '<p class="success-message fail">❌ Incorrect flag. Try again!</p>';
            }
        }
        ?>
    </div>
</div>

<?php get_footer(); ?>
