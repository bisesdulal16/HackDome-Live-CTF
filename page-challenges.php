<?php
/* Template Name: Challenges */
get_header();
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-content">

                <!-- ***** Featured CTF Challenges ***** -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="featured-games header-text">
                            <div class="heading-section">
                                <h4><em>Popular</em> Live Boxes</h4>
                            </div>
                            <div class="owl-features owl-carousel">
                                <?php
                                $featured_args = array(
                                    'post_type'      => 'ctf_challenge',
                                    'posts_per_page' => 6,
                                    'meta_query'     => array(
                                        array(
                                            'key'     => 'is_featured',
                                            'value'   => '1',
                                            'compare' => '='
                                        )
                                    )
                                );
                                $featured_query = new WP_Query($featured_args);
                                if ($featured_query->have_posts()) :
                                    while ($featured_query->have_posts()) : $featured_query->the_post();
                                        $desc      = get_field('short_description');
                                        $players   = get_field('players_allowed');
                                        $difficulty = get_field('difficulty');
                                        $rating     = get_field('rating');
                                        $plays      = get_field('play_count');
                                ?>
                                        <div class="item">
                                            <div class="thumb">
                                                <?php 
                                                if (has_post_thumbnail()) {
                                                    the_post_thumbnail('medium');
                                                } else {
                                                    echo '<img src="' . get_template_directory_uri() . '/assets/images/default-box.jpg" alt="CTF Image">';
                                                }
                                                ?>
                                                <div class="hover-effect">
                                                    <h6><a href="<?php the_permalink(); ?>">Join Box</a></h6>
                                                </div>
                                            </div>
                                            <h4><?php the_title(); ?><br><span><?php echo esc_html($desc); ?></span></h4>
                                            <span class="stats">
                                                <i class="fa fa-users"></i> Players: <?php echo esc_html($players); ?> <br>
                                                <i class="fa fa-tachometer-alt"></i> Difficulty: <?php echo esc_html($difficulty); ?> <br>
                                                <i class="fa fa-star"></i> <?php echo esc_html($rating); ?> <br>
                                                <i class="fa fa-gamepad"></i> <?php echo esc_html($plays); ?> Times
                                            </span>
                                        </div>
                                <?php
                                    endwhile;
                                    wp_reset_postdata();
                                else :
                                    echo "<p class='terminal-text'>No featured challenges available.</p>";
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>



                    <!-- ***** CTF Leaderboard ***** -->
                    <div class="col-lg-4">
                        <div class="top-streamers">
                            <div class="heading-section">
                                <h4><em>CTF</em> Leaderboard</h4>
                            </div>
                            <ul>
                                <?php
                                // Dummy leaderboard data
                                $leaderboard = [
                                    ['rank' => '01', 'name' => 'BishopX', 'score' => '2250', 'color' => 'gold'],
                                    ['rank' => '02', 'name' => 'ShadowRoot', 'score' => '1980', 'color' => 'silver'],
                                    ['rank' => '03', 'name' => 'CryptoPhantom', 'score' => '1850', 'color' => 'bronze'],
                                ];

                                foreach ($leaderboard as $player) :
                                ?>
                                    <li>
                                        <span><?php echo esc_html($player['rank']); ?></span>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/avatar-0<?php echo esc_html($player['rank']); ?>.jpg" alt="" style="max-width: 46px; border-radius: 50%; margin-right: 15px;">
                                        <h6><i class="fa fa-trophy" style="color: <?php echo esc_html($player['color']); ?>;"></i> <?php echo esc_html($player['name']); ?></h6>
                                        <div class="main-button">
                                            <a href="#"><?php echo esc_html($player['score']); ?> Pts</a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="see-more">
                                <a href="<?php echo esc_url(home_url('/leaderboard')); ?>">See More</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ***** Ongoing CTFs with Filters ***** -->
                <div class="live-stream">
                    <div class="col-lg-12">
                        <div class="heading-section">
                            <h4><em>Ongoing</em> Live CTFs</h4>
                        </div>

                        <!-- CTF Filters -->
                        <div class="ctf-filter">
                            <div class="filter-buttons">
                                <button class="filter-btn active" data-category="all">All</button>
                                <button class="filter-btn" data-category="offensive">Offensive</button>
                                <button class="filter-btn" data-category="defensive">Defensive</button>
                                <button class="filter-btn" data-category="forensics">Forensics</button>
                                <button class="filter-btn" data-category="crypto">Cryptography</button>
                                <button class="filter-btn" data-category="osint">OSINT</button>
                            </div>
                        </div>

                        <!-- CTF Items -->
                        <div class="row" id="ctf-container">
                            <?php
                            $args = array(
                                'post_type'      => 'ctf_challenge',
                                'posts_per_page' => -1,
                                'meta_query'     => array(
                                    array(
                                        'key'     => 'is_ongoing',
                                        'value'   => '1',
                                        'compare' => '='
                                    )
                                )
                            );
                            $ongoing = new WP_Query($args);
                            if ($ongoing->have_posts()) :
                                while ($ongoing->have_posts()) : $ongoing->the_post();
                                    $players    = get_field('players_allowed');
                                    $difficulty = get_field('difficulty');
                                    $rating     = get_field('rating');
                                    $category   = strtolower(get_field('category')); // assumed ACF, change if taxonomy
                            ?>
                                <div class="col-lg-3 col-sm-6 ctf-item" data-category="<?php echo esc_attr($category); ?>">
                                    <div class="item">
                                        <div class="thumb">
                                            <?php 
                                            if (has_post_thumbnail()) {
                                                the_post_thumbnail('medium');
                                            } else {
                                                echo '<img src="' . get_template_directory_uri() . '/assets/images/default-box.jpg" alt="CTF">';
                                            }
                                            ?>
                                            <div class="hover-effect">
                                                <div class="content">
                                                    <div class="live"><a href="#">Live</a></div>
                                                    <ul>
                                                        <li><a href="<?php the_permalink(); ?>"><i class="fa-solid fa-right-to-bracket"></i> Join Box </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <h4><?php the_title(); ?><br><span><?php echo wp_trim_words(get_the_content(), 10); ?></span></h4>
                                        <div class="stats">
                                            <li>
                                                <i class="fa fa-users"></i> Players: <?php echo esc_html($players); ?> <br>
                                                <i class="fa fa-tachometer-alt"></i> Difficulty: <?php echo esc_html($difficulty); ?> <br>
                                                <i class="fa fa-star"></i> <?php echo esc_html($rating); ?>
                                            </li>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            else :
                                echo '<p class="terminal-text">No live CTFs available at the moment.</p>';
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".filter-btn");
    const boxes = document.querySelectorAll(".ctf-item");

    buttons.forEach(btn => {
        btn.addEventListener("click", function () {
            const category = this.getAttribute("data-category");

            buttons.forEach(b => b.classList.remove("active"));
            this.classList.add("active");

            boxes.forEach(box => {
                const boxCat = box.getAttribute("data-category");
                if (category === "all" || boxCat === category) {
                    box.style.display = "block";
                } else {
                    box.style.display = "none";
                }
            });
        });
    });
});
</script>

<?php get_footer(); ?>
