/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

(function($) {

  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function() {
        // JavaScript to be fired on all pages
      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
      }
    },
    // Home page
    'home': {
      init: function() {
        // JavaScript to be fired on the home page
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
    },
    // Is gallery active
    'gallery_active': {
      init: function() {
        // JavaScript to be fired when gallery is active
      },
      finalize: function() {
        $('.slick-gallery').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          centerPadding: '15px',
          arrows: true,
          nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-down"></button>',
          prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-up"></button>',
          appendArrows: $('.slick-gallery-arrows'),
          fade: false,
          adaptiveHeight: true,
          infinite: false,
          useTransform: true,
          speed: 400,
          vertical: true,
          cssEase: 'cubic-bezier(0.77, 0, 0.18, 1)',
          responsive: [
            {
              breakpoint: 768,
              settings: {
                vertical: false,
                adaptiveHeight: false,
              }
            },
          ]
        });
        $('.slick-gallery-nav')
        .on('init', function(event, slick) {
          $('.slick-gallery-nav .slick-slide.slick-current').addClass('is-active');
        })
        .slick({
          slidesToShow: 3,
          slidesToScroll: 3,
          centerPadding: '9px',
          dots: false,
          focusOnSelect: false,
          infinite: false,
          arrows: false,
          vertical: true,
          responsive: [
            {
              breakpoint: 768,
              settings: {
                centerPadding: '4px',
                vertical: false,
                adaptiveHeight: false,
              }
            },
            {
              breakpoint: 575,
              settings: {
                slidesToShow: 2,
                centerPadding: '4px',
                vertical: false,
                adaptiveHeight: false,
              }
            },
          ]
        });
        $('.slick-gallery').on('afterChange', function(event, slick, currentSlide) {
          $('.slick-gallery-nav').slick('slickGoTo', currentSlide);
          var currrentNavSlideElem = '.slick-gallery-nav .slick-slide[data-slick-index="' + currentSlide + '"]';
          $('.slick-gallery-nav .slick-slide.is-active').removeClass('is-active');
          $(currrentNavSlideElem).addClass('is-active');
        });
        $('.slick-gallery-nav').on('click', '.slick-slide', function(event) {
          event.preventDefault();
          var goToSingleSlide = $(this).data('slick-index');
          $('.slick-gallery').slick('slickGoTo', goToSingleSlide);
        });
      }
    },
  };

  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
    fire: function(func, funcname, args) {
      var fire;
      var namespace = Sage;
      funcname = (funcname === undefined) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';

      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function() {
      // Fire common init JS
      UTIL.fire('common');

      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, 'finalize');
      });

      // Fire common finalize JS
      UTIL.fire('common', 'finalize');
    }
  };

  // Load Events
  $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
