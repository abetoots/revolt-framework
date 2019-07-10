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
                        <i class="far fa-money-bill-alt"></i> Payment verified
                    </span>
                </span>
            </h2>

        </div><!-- end dashboard-headline -->
    </div>
    <!-- end cover__container -->
</section>
<!-- end cover component-->

<main class="grid-layout grid-layout--edit-profile">

    <!-- start menu component -->
    <nav class="dashboard-menu js-grow-element-mobile">
        <div class="dashboard-menu__height js-height-mobile">
            <ul class="dashboard-menu__ul-wrap">
                <li>
                    <p class="dashboard-menu__label dashboard-menu__label--bottom-line">Profile Settings</p>
                </li>
                <li class="dashboard-menu__item">
                    <i class="fas fa-user"></i>
                    <a class="dashboard-menu__links" href="<?php echo home_url( 'employer' ); ?>">Edit
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

    <!-- start form-employer-component -->
    <section class="edit-profile">
        <div class="edit-profile__container">
            <form action="<?php echo admin_url('admin-post.php'); ?>" method="post" class="form-employer"
                id="form-employer">
                <div class="form-employer__block-wrap">

                    <input type="hidden" name="action" value="lofi_employer_edit_profile_action">
                    <?php wp_nonce_field( 'lofi_employer_edit_profile_action', 'lofi-edit-profile-nonce' ); ?>

                    <h3 class="form-employer__heading">My Profile</h3>
                    <figure class="form-employer__figure">
                        Profile Image
                        <img class="form-employer__image">
                    </figure>

                    <input type="hidden" name="employer_photo">

                    <div class="form-employer__input-wrap">
                        <label for="employer-name" class="form-employer__label">Employer / Company Name</label>
                        <input id="employer-name" type="text" name="" class="form-employer__input">
                    </div>

                    <div class="form-employer__input-wrap">
                        <label for="employer-headline" class="form-employer__label">Headline</label>
                        <input id="employer-headline" type="text" name="" class="form-employer__input">
                    </div>

                    <div class="form-employer__input-wrap">
                        <label for="employer-since" class="form-employer__label">Established Since</label>
                        <input id="employer-since" type="number" name="" class="form-employer__input">
                    </div>

                    <div class="form-employer__input-wrap">
                        <label for="employer-size" class="form-employer__label">No. of Employees</label>
                        <input id="employer-size" type="number" name="" class="form-employer__input">
                    </div>

                    <div class="form-employer__input-wrap">
                        <label for="employer-website" class="form-employer__label">Website</label>
                        <input id="employer-website" type="url" name="" class="form-employer__input">
                    </div>

                    <div class="form-employer__input-wrap">
                        <label for="employer-description" class="form-employer__label">About / Description</label>
                        <textarea id="employer-description" name="" class="form-employer__input"> </textarea>
                    </div>
                </div>
                <!-- end form-employer__block-wrap -->

                <div class="form-employer__block-wrap">
                    <h3 class="form-employer__heading">Edit Social Profiles</h3>

                    <div class="form-employer__input-wrap">
                        <label for="employer-facebook" class="form-employer__label">Facebook</label>
                        <input id="employer-facebook" type="url" name="" class="form-employer__input">
                    </div>


                    <div class="form-employer__input-wrap">
                        <label for="employer-headline" class="form-employer__label">Twitter</label>
                        <input id="employer-headline" type="url" name="" class="form-employer__input">
                    </div>

                    <div class="form-employer__input-wrap">
                        <label for="employer-contact" class="form-employer__label">LinkedIn</label>
                        <input id="employer-contact" type="url" name="" class="form-employer__input">
                    </div>

                </div>
                <!-- end form-employer__block-wrap -->

                <div class="form-employer__block-wrap">
                    <h3 class="form-employer__heading">Edit Contact Information</h3>

                    <div class="form-employer__input-wrap">
                        <label for="employer-phone" class="form-employer__label">Phone Number</label>
                        <input id="employer-phone" type="number" name="" class="form-employer__input">
                    </div>

                    <div class="form-employer__input-wrap">
                        <label for="employer-headline" class="form-employer__label">Email</label>
                        <input id="employer-headline" type="text" name="" class="form-employer__input">
                    </div>

                    <div class="form-employer__input-wrap">
                        <label for="employer-contact" class="form-employer__label">Location</label>
                        <input id="employer-contact" type="text" name="" class="form-employer__input">
                    </div>

                </div>
                <!-- end form-employer__block-wrap -->

                <input type="submit" class="form-employer__submit" value="Update" name="form_employer_submit">
            </form>
            <!-- end employer form -->
        </div>
        <!-- end form-employer__container -->
    </section>
    <!-- end form-employer-component -->

    <!-- start notifications component -->
    <section class="notifications">
        <div class="notifications__text-container">
            <h3 class="notifications__text">0 Notifications</h3>
            <button type="button" class="notifications__button"><i class="fas fa-bell"></i></button>
        </div>

        <div class="notifications__area-container">
            <div class="notifications__area"></div>
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
                        <div class="touch-button touch-button--email">
                            <a href="" class="touch-button__link">
                                <button type="button" class="touch-button__button">
                                    <i class="fas fa-envelope"></i>
                                    <p class="touch-button__text">Inbox</p>
                                </button>
                            </a>
                        </div>
                        <!-- end touch-button-container -->

                        <div class="touch-button touch-button--email">
                            <a href="" class="touch-button__link">
                                <button type="button" class="touch-button__button">
                                    <i class="fas fa-paper-plane"></i>
                                    <p class="touch-button__text">Send Email</p>
                                </button>
                            </a>
                        </div>
                        <!-- end touch-button-container -->

                        <div class="touch-button touch-button--email">
                            <a href="" class="touch-button__link">
                                <button type="button" class="touch-button__button ">
                                    <i class="fas fa-folder-open"></i>
                                    <p class="touch-button__text">Templates</p>
                                </button>
                            </a>
                        </div>
                        <!-- end touch-button-container -->
                    </div>
                    <!-- end email height -->
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