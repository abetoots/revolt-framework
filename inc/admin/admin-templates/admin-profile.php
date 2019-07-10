<h1>Customize the profile sidebar that appears on the front-end</h1>
<?php settings_errors(); ?>
<?php 
    $profilePic = esc_attr( get_option( 'profile_picture' ) );
    $firstName = esc_attr( get_option( 'first_name' ) );
    $lastName = esc_attr( get_option('last_name') );
    $fullName = $firstName . ' ' . $lastName;
    $description = esc_attr( get_option('description') );
    $twitterHandler = esc_attr( get_option( 'twitter_handler' ));
    $src = 'src = "'.$profilePic.'"';
?>


<div class="profile-wrapper">
    <h2 class="preview-text">Front End Preview</h2>
    <div class="preview-container">
        <div class="user-wrap">
            
            <figure class="image-container">
                <img id="profile-picture-preview" <?php if( !empty($profilePic)) : echo $src; endif; ?> class="profile-image"> 
            </figure>

            <h2 class="profile-username">
                <?php 
                if ($firstName == '' && $lastName == ''){
                    echo 'Lofi User';
                } else{
                    print $fullName;
                } ?>
            </h2>
            <h3 class="profile-description">
            <?php 
                if ($description == ''){
                    echo 'A simple description about yourself';
                } else{
                    print $description;
                } ?>
            </h3>
            <div class="social-icons-wrapper">

            </div>
        
            </div> <!-- user wrap end -->
    </div> <!-- profile container end -->
</div> <!-- profile wrapper end -->

<form method="post" action="options.php" class='form-inline lofi-profile-form'>
    <?php settings_fields( 'lofi-profile-settings' ); ?>
    <?php do_settings_sections( 'lofi_profile' ); ?>
    <?php submit_button( 'Save Changes', 'primary', 'btnSubmit'); ?>
</form>
