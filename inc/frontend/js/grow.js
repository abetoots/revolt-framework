(function ($){
    $(document).ready(function(){
    
      $button = $('.js-toggle-button-mobile');
      $elementToGrow = $('.js-grow-element-mobile');
    
      $button.on('click', function(){
        //$mobileMenu.toggleClass( 'transform' );
        //Grow animation for nav menu when mobile
        let theHeight = $('.js-height-mobile').height();
        if ($elementToGrow.height() > 0) {
          $elementToGrow.height(0);
        } else {
          $elementToGrow.height(theHeight);
        }
      });

      $button2 = $('.js-toggle-button-email');
      $elementToGrow2 = $('.js-grow-element-email');
      
      $button2.on('click', function(){
        //$mobileMenu.toggleClass( 'transform' );
        //Grow animation for nav menu when mobile
        let theHeight2 = $('.js-height-email').height();
        if ($elementToGrow2.height() > 0) {
          $elementToGrow2.height(0);
        } else {
          $elementToGrow2.height(theHeight2);
        }
      });
    
    });
    
    })( jQuery );