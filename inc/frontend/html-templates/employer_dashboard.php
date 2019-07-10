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
    <div class="cover__container cover__container--dashboard-only">

        <div class="dashboard-headline">

            <figure class="dashboard-headline__figure">
                <img class="dashboard-headline__img dashboard-headline__img--rounded"
                    src="http://localhost/lofi/wp-content/uploads/2019/05/pexels-photo-1547971.jpeg">
            </figure>

            <h2 class="dashboard-headline__username"><?php echo esc_html($username); ?>
                <span class="stamp stamp--verified">Verified <i class="fas fa-user-check"></i>
                    <span class="stamp__tooltip stamp__tooltip--verified stamp__tooltip--mobile">
                        <i class="far fa-money-bill-alt"></i> Payment verified</span>
                </span>
            </h2>

        </div><!-- end dashboard-headline -->

    </div><!-- end cover__container -->
</section>
<!-- end cover component-->

<main class="grid-layout grid-layout--employer">
    <!-- start stats component -->
    <section class="stats">
        <div class="stats__container-wrap">
            <div class="stats__container stats__container--style-1">
                <div class="stats__block-wrap">
                    <div class="stats__icon-wrap">
                        <i class="fas fa-briefcase"></i>
                        <span class="stats__number">1</span>
                    </div>

                    <p class="stats__text">Jobs Posted</p>
                </div>

                <div class="touch-button touch-button--align-center">
                    <a href="<?php echo home_url('edit-profile'); ?>" class="touch-button__link">
                        <button type="button"
                            class="touch-button__button touch-button__button--width-max touch-button__button--post">
                            <i class="fa fa-plus"></i>
                            <span class="touch-button__text">Post A Job</span>
                        </button>
                    </a>
                </div>

            </div>
            <!-- end container 1 -->

            <div class="stats__container stats__container--style-2">
                <div class="stats__block-wrap">
                    <div class="stats__icon-wrap">
                        <i class="fas fa-file"></i>
                        <span class="stats__number">1</span>
                    </div>

                    <p class="stats__text">Resumes</p>
                </div>

                <div class="touch-button touch-button--align-center">
                    <a href="" class="touch-button__link">
                        <button type="button"
                            class="touch-button__button touch-button__button--atf touch-button__button--manage">
                            <span class="touch-button__text">Manage Resumes</span>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </a>
                </div>
            </div>
            <!-- end container 2 -->

            <div class="stats__container stats__container--style-3">
                <div class="stats__block-wrap">
                    <div class="stats__icon-wrap">
                        <i class="fas fa-user-tie"></i>
                        <span class="stats__number">1</span>
                    </div>

                    <p class="stats__text">For Interview</p>
                </div>

                <div class="touch-button touch-button--align-center">
                    <a href="" class="touch-button__link">
                        <button type="button"
                            class="touch-button__button touch-button__button--atf touch-button__button--schedule">
                            <span class="touch-button__text">Schedule</span>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </a>
                </div>
            </div>
            <!-- end container 3 -->
        </div>
        <!-- end stats-container-wrap -->
    </section>
    <!-- end stats component -->

    <!-- start menu component -->
    <nav class="dashboard-menu js-grow-element-mobile">
        <div class="dashboard-menu__height js-height-mobile">
            <ul class="dashboard-menu__ul-wrap">
                <li>
                    <p class="dashboard-menu__label dashboard-menu__label--bottom-line">Profile Settings</p>
                </li>
                <li class="dashboard-menu__item">
                    <i class="fas fa-user"></i>
                    <a class="dashboard-menu__links" href="<?php echo home_url( 'edit-profile' ); ?>">Edit
                        Profile</a>
                </li>
                <li class="dashboard-menu__item">
                    <i class="fas fa-eye"></i>
                    <a class="dashboard-menu__links" href="<?php echo home_url( 'employer-profile' ); ?>">View
                        Profile</a>
                </li>
                <li class="dashboard-menu__item">
                    <i class="fas fa-key"></i><a class="dashboard-menu__links"
                        href="<?php echo home_url( 'change-password' ); ?>">Change Password</a>
                </li>

                <li>
                    <p class="dashboard-menu__label dashboard-menu__label--bottom-line">Manage</p>
                </li>
                <li class="dashboard-menu__item">
                    <i class="fas fa-briefcase"></i><a class="dashboard-menu__links"
                        href="<?php echo home_url( 'manage-jobs' ); ?>">Manage Jobs</a>
                </li>
                <li class="dashboard-menu__item">
                    <i class="fas fa-users"></i><a class="dashboard-menu__links"
                        href="<?php echo home_url( 'candidates' ); ?>">Candidates</a>
                </li>
                <li class="dashboard-menu__item">
                    <i class="fas fa-calculator"></i><a class="dashboard-menu__links"
                        href="<?php echo home_url( 'package-plans' ); ?>">Package Plans</a>
                </li>
            </ul>
        </div>
        <!-- end height wrapper -->
    </nav>
    <!-- end menu component -->

    <!-- mobile only touch button -->
    <div class="touch-button touch-button--text-center touch-button--mobile-only">
        <button type="button" class="touch-button__button touch-button__button--mobile-helper js-toggle-button-mobile">
            <i class="fas fa-chevron-down"></i>
        </button>
    </div>
    <!-- end mobile only touch target -->

    <!-- start notifications component -->
    <section class="notifications">
        <div class="notifications__container">
            <div class="notifications__text-container">
                <h3 class="notifications__text">0 Notifications</h3>
                <button type="button" class="notifications__button"><i class="fas fa-bell"></i></button>
            </div>

            <div class="notifications__area-container">
                <div class="notifications__area"></div>
            </div>
        </div>
    </section>
    <!-- end notifications component -->

    <!-- start email component  -->
    <section class="email">
        <div class="email__container">

            <div class="email__text-container">
                <h3 class="email__text">Emails</h3>
                <button type="button" class="email__button js-toggle-button-email"><i
                        class="fas fa-chevron-down"></i></button>
            </div>

            <div class="email__targets-container js-grow-element-email">
                <div class="email__targets-wrapper">
                    <div class="email__height js-height-email">
                        <div class="touch-button">
                            <a href="" class="touch-button__link">
                                <button type="button" class="touch-button__button touch-button__button--email">
                                    <i class="fas fa-envelope"></i>
                                    <p class="touch-button__text">Inbox</p>
                                </button>
                            </a>
                        </div>
                        <!-- end touch-button-container -->

                        <div class="touch-button">
                            <a href="" class="touch-button__link">
                                <button type="button" class="touch-button__button touch-button__button--email">
                                    <i class="fas fa-paper-plane"></i>
                                    <p class="touch-button__text">Send Email</p>
                                </button>
                            </a>
                        </div>
                        <!-- end touch-button-container -->

                        <div class="touch-button">
                            <a href="" class="touch-button__link">
                                <button type="button" class="touch-button__button touch-button__button--email">
                                    <i class="fas fa-folder-open"></i>
                                    <p class="touch-button__text">Templates</p>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- end email-targets-wrapper -->
            </div>
            <!-- end email-targets container-->
        </div>
        <!-- end email container -->
    </section>
    <!-- end email component -->

    <!-- start recent component -->
    <section class="recent">
        <div class="recent__container">
            <div class="recent__text-container">
                <h3 class="recent__text">Recent Activity</h3>
            </div>
            <div class="recent__activities-container">
                <div class="recent__activities"></div>
            </div>
        </div>
    </section>
    <!-- end recent component -->

</main>

<a href="<?php echo wp_logout_url( home_url() ); ?>" title="signout">Sign Out </a>