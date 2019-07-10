<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
$user_id = get_current_user_id();
$user_info = get_userdata( $user_id );

$username = $user_info->user_nicename;
$first_name = $user_info->first_name;
$last_name = $user_info->last_name;
$company_name = $user_info->company_name;
$tagline = $user_info->tagline;
$employer_name = (isset($first_name) && isset($last_name)) ? $first_name . $last_name: $company_name ;

?>
<!-- start cover component -->
<section class="cover">
    <div class="cover__container cover__container--front-end">
        <!-- start profile-headline component -->
        <div class="profile-headline">

            <div class="profile-headline__figure-wrap">
                <figure class="profile-headline__figure">
                    <img class="profile-headline__img profile-headline__img--rounded"
                        src="http://localhost/lofi/wp-content/uploads/2019/05/pexels-photo-1547971.jpeg">
                </figure>
            </div>
            <!-- end figure-wrap -->

            <div class="profile-headline__grid-wrap">
                <div class="profile-headline__grid-area-text">
                    <h2 class="profile-headline__username"><?php echo esc_html($username); ?>
                        <span class="stamp stamp--verified">Verified <i class="fas fa-user-check"></i>
                            <span class="stamp__tooltip stamp__tooltip--verified stamp__tooltip--mobile">
                                <i class="far fa-money-bill-alt"></i> Payment verified</span>
                        </span>
                    </h2>

                    <p class="profile-headline__tagline">I am a tagline</p>

                    <!-- start contact-info component -->
                    <div class="contact-info contact-info--mobile-grid">
                        <div class="contact-info__block-wrap contact-info__block-wrap--1">
                            <i class="fas fa-link"></i>
                            <a class="contact-info__item contact-info__website">sample</a>
                        </div>

                        <div class="contact-info__block-wrap contact-info__block-wrap--2">
                            <i class="fas fa-phone"></i>
                            <span class="contact-info__item contact-info__phone-number">sample</span>
                        </div>

                        <div class="contact-info__block-wrap contact-info__block-wrap--3">
                            <i class="fas fa-envelope"></i>
                            <span class="contact-info__item contact-info__email">sample</span>
                        </div>
                    </div>
                    <!-- end contact-info component -->
                </div>
                <!-- end profile-headline__grid-container -->

                <div class="profile-headline__grid-area-social">
                    <!-- start social-profiles component -->
                    <div class="social-profiles social-profiles--flex">

                        <div class="touch-button touch-button--social-profiles">
                            <a href="" class="touch-button__link">
                                <button type="button" class="touch-button__button touch-button__button--fb">
                                    <i class="fab fa-facebook-f"></i>
                                </button>
                            </a>
                        </div>

                        <div class="touch-button touch-button--social-profiles">
                            <a href="" class="touch-button__link">
                                <button type="button" class="touch-button__button touch-button__button--twitter">
                                    <i class="fab fa-twitter"></i>
                                </button>
                            </a>
                        </div>

                        <div class="touch-button touch-button--social-profiles">
                            <a href="" class="touch-button__link">
                                <button type="button" class="touch-button__button touch-button__button--linkedin">
                                    <i class="fab fa-linkedin-in"></i>
                                </button>
                            </a>
                        </div>

                    </div>
                    <!-- end social-profiles component -->
                </div>
                <!-- end profile-headline__grid-wrap-container -->
            </div>
            <!-- end profile-headline__grid-wrap -->
        </div>
        <!-- end profile-headline component -->
    </div>
    <!-- end cover__container -->
</section>
<!-- end cover component-->

<main class="grid-layout grid-layout--mobile-row-gap">

    <!-- start about component -->
    <section class="about">
        <div class="about__container">
            <h3 class="about__headline">About Us</h3>
            <p class="about__description">Insert description here</p>
        </div>
    </section>
    <!-- end about component -->

    <!-- start open-positions component -->
    <section class="open-positions">
        <div class="open-positions__container">
            <h3 class="open-positions__headline">Open Positions</h3>

            <div class="open-positions__job-block-wrap">
                <h4 class="open-positions__job-title">Title</h4>
                <p class="open-positions__job-type">Type</p>
                <p class="open-positions__job-posted">Date</p>

                <div class="touch-button">
                    <a href="" class="touch-button__link">
                        <button type="button" class="touch-button__button">
                            <span class="touch-button__text">Apply Now</span>
                        </button>
                    </a>
                </div>

            </div>
            <!-- end job-block-wrap -->

        </div>
        <!-- end open-positions__container -->
    </section>
    <!-- end open-positions component -->

    <!-- start company-info component -->
    <section class="company-info">
        <div class="company-info__container">
            <h3 class="company-info__headline">Company Information</h3>

            <div class="company-info__block-wrap">
                <h4>Jobs Posted</h4>
                <p>22</p>
            </div>

            <div class="company-info__block-wrap">
                <h4>Website</h4>
                <p></p>
            </div>

            <div class="company-info__block-wrap">
                <h4>Industry</h4>
                <p>Software</p>
            </div>

            <div class="company-info__block-wrap">
                <h4>Headquarters</h4>
                <p>USA</p>
            </div>

            <div class="company-info__block-wrap">
                <h4>Team Members</h4>
                <p>25+</p>
            </div>

            <div class="company-info__block-wrap">
                <h4>Founded</h4>
                <p>1990</p>
            </div>

        </div>
    </section>
    <!-- end company-info component -->
</main>