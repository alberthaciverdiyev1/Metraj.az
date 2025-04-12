; (function ($) {

  $(document).ready(function () {

    showform();
    //========== HEADER ACTIVE STRATS ============= //
    if ($("#header").length > 0) {
      $(window).on("scroll", function (event) {
        var scroll = $(window).scrollTop();
        if (scroll < 1) {
          $(".header-area").removeClass("sticky");
        } else {
          $(".header-area").addClass("sticky");
        }
      });
    }
    //========== HEADER ACTIVE ENDS ============= //

    //========== SIDEBAR/SEARCH AREA ============= //
    $(".header-search-btn").on("click", function (e) {
      e.preventDefault();
      $(".header-search-form-wrapper").addClass("open");
      $('.header-search-form-wrapper input[type="search"]').focus();
      $('.body-overlay').addClass('active');
    });
    $(".tx-search-close").on("click", function (e) {
      e.preventDefault();
      $(".header-search-form-wrapper").removeClass("open");
      $("body").removeClass("active");
      $('.body-overlay').removeClass('active');
    });
    $('#mobile-menu-active').metisMenu();
    $('#mobile-menu-active .dropdown > a').on('click', function (e) {
      e.preventDefault();
    });
    $(".hamburger_menu").on("click", function (e) {
      e.preventDefault();
      $(".slide-bar").toggleClass("show");
      $("body").addClass("on-side");
      $('.body-overlay').addClass('active');
      $(this).addClass('active');
    });
    $(".close-mobile-menu > a").on("click", function (e) {
      e.preventDefault();
      $(".slide-bar").removeClass("show");
      $("body").removeClass("on-side");
      $('.body-overlay').removeClass('active');
      $('.hamburger_menu').removeClass('active');
    });
    //========== SIDEBAR/SEARCH AREA ============= //

    //========== PAGE PROGRESS STARTS ============= // 
    var progressPath = document.querySelector(".progress-wrap path");
    var pathLength = progressPath.getTotalLength();
    progressPath.style.transition = progressPath.style.WebkitTransition =
      "none";
    progressPath.style.strokeDasharray = pathLength + " " + pathLength;
    progressPath.style.strokeDashoffset = pathLength;
    progressPath.getBoundingClientRect();
    progressPath.style.transition = progressPath.style.WebkitTransition =
      "stroke-dashoffset 10ms linear";
    var updateProgress = function () {
      var scroll = $(window).scrollTop();
      var height = $(document).height() - $(window).height();
      var progress = pathLength - (scroll * pathLength) / height;
      progressPath.style.strokeDashoffset = progress;
    };
    updateProgress();
    $(window).scroll(updateProgress);
    var offset = 50;
    var duration = 550;
    jQuery(window).on("scroll", function () {
      if (jQuery(this).scrollTop() > offset) {
        jQuery(".progress-wrap").addClass("active-progress");
      } else {
        jQuery(".progress-wrap").removeClass("active-progress");
      }
    });
    jQuery(".progress-wrap").on("click", function (event) {
      event.preventDefault();
      jQuery("html, body").animate({ scrollTop: 0 }, duration);
      return false;
    });
    //========== PAGE PROGRESS STARTS ============= // 

    //========== VIDEO POPUP STARTS ============= //
    if ($(".popup-youtube").length > 0) {
      $(".popup-youtube").magnificPopup({
        type: "iframe",
      });
    }
    //========== VIDEO POPUP ENDS ============= //

    //========== AOS_ANIMATION STARTS ============= //
    AOS.init;
    AOS.init({ disable: 'mobile' });
    //========== AOS_ANIMATION ENDS ============= //

    //========== NICE SELECT ============= //
    $('select').niceSelect();

    //========== CASE IMAGE ============= //
    $('.cs_hover_active').hover(function () {
      $(this).addClass('active').siblings().removeClass('active');
    });

  });
  //========== COUNTER UP============= //
  const ucounter = $('.counter');
  if (ucounter.length > 0) {
    ucounter.countUp();
  };
  //========== COUNTER UP============= //

  //========== CLICK ============= //
  const color = $(".heart");
  color.on("click", function () {
    $(".heart");
    $(this).addClass("active");
  });
  //========== CLICK ============= //

  //========== TESTIMONIAL AREA ============= //

  //========== SLIDER ============= //
  $(".hero-area-slider").slick({
    autoplay: true,
    autoplaySpeed: 2000,
    speed: 1500,
    slidesToShow: 1,
    slidesToScroll: 1,
    pauseOnHover: false,
    dots: false,
    arrows: true,
    pauseOnDotsHover: true,
    cssEase: 'linear',
    fade: true,
    draggable: true,
    prevArrow: $(".testimonial-prev-arrow"),
    nextArrow: $(".testimonial-next-arrow"),
  });

  //========== SLIDER ============= //
  $(".slider2").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    asNavFor: ".slider1",
    dots: false,
    arrows: false,
    centerMode: false,
    focusOnSelect: true,
    fade: true,
    loop: true,
    autoplay: true,
    autoplaySpeed: 2000,
    infinite: true,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          infinite: true,
        }
      },
      {
        breakpoint: 769,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
  });

  $(".slider1").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    autoplay: true,
    autoplaySpeed: 2000,
    loop: true,
    focusOnSelect: true,
    asNavFor: ".slider2",
    infinite: true,
    prevArrow: $('.next-arrow'),
    nextArrow: $('.prev-arrow'),
  });


  //========== SLIDER ============= //
  $('.property-single-slider').owlCarousel({
    loop: true,
    margin: 30,
    nav: true,
    dots: false,
    items: 10,
    autoplay: true,
    navText: ["<i class='fa-solid fa-angle-left'></i>", "<i class='fa-solid fa-angle-right'></i>"],
    smartSpeed: 2000,
    autoplayTimeout: 3000,
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 3,
      },
      1000: {
        items: 5,
      }
    }
  });

  $('.box-slider').slick({
    slidesToScroll: 1,
    arrows: true,
    dots: false,
    autoplay: true,
    autoplaySpeed: 1500,
    speed: 1000,
    vertical: true,
    verticalSwiping: true,
    slidesToShow: 1,
    autoplay: true,
    prevArrow: $(".next-arrow"),
    nextArrow: $(".prev-arrow"),
    responsive: [
      {
        breakpoint: 1400,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          infinite: true,
        }
      },
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          infinite: true,
        }
      },

      {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        }
      },

      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 2,
        }
      },

      {
        breakpoint: 375,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        }
      },

      {
        breakpoint: 320,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        }
      },
    ]
  });

  //========== SLIDER ============= //
  $(".hero2-slider-sectionarea").slick({
    autoplay: true,
    autoplaySpeed: 2500,
    speed: 1500,
    slidesToShow: 1,
    slidesToScroll: 1,
    pauseOnHover: false,
    asNavFor: ".hero2-small-img",
    dots: false,
    arrows: false,
    pauseOnDotsHover: true,
    cssEase: 'linear',
    fade: true,
    draggable: true,
  });

  $(".hero2-small-img").slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    arrows: false,
    autoplay: true,
    vertical: true,
    verticalSwiping: true,
    autoplaySpeed: 2000,
    loop: true,
    focusOnSelect: true,
    asNavFor: ".hero2-slider-sectionarea",
    infinite: true,
  });

  //========== SLIDER ============= //
  $(".slider-galeria").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    infinite: false,
    infinite: true,
    asNavFor: '.slider-galeria-thumbs',
    prevArrow: $('.testimonial-next-arrow'),
    nextArrow: $('.testimonial-prev-arrow'),
  });
  $(".slider-galeria-thumbs").slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    items: 15,
    arrows: true,
    asNavFor: ".slider-galeria",
    vertical: true,
    infinite: true,
    verticalSwiping: true,
    focusOnSelect: true,
    infinite: false,
    prevArrow: $('.testimonial-next-arrow'),
    nextArrow: $('.testimonial-prev-arrow'),
  });

  //========== SLIDER ============= //
  $(".images-area").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    asNavFor: ".slider-content-area",
    dots: false,
    arrows: false,
    centerMode: false,
    focusOnSelect: true,
    fade: true,
    loop: true,
    autoplay: true,
    autoplaySpeed: 2000,
    infinite: true,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          infinite: true,
        }
      },
      {
        breakpoint: 769,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
  });

  $(".slider-content-area").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    autoplay: true,
    autoplaySpeed: 2000,
    loop: true,
    focusOnSelect: true,
    asNavFor: ".images-area",
    infinite: true,
    prevArrow: $('.testimonial-next-arrow'),
    nextArrow: $('.testimonial-prev-arrow'),
  });

  //========== SLIDER ============= //
  $('.testimonial-slider-box3').owlCarousel({
    loop: true,
    margin: 30,
    nav: true,
    dots: true,
    items: 10,
    navText: ["<i class='fa-solid fa-angle-up'></i>", "<i class='fa-solid fa-angle-down'></i>"],
    autoplay: true,
    smartSpeed: 1500,
    autoplayTimeout: 3000,
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 1,
      },
      1000: {
        items: 1,
      }
    }
  });

  //========== SLIDER ============= //
  $('.loaction-slider-property').owlCarousel({
    loop: true,
    margin: 30,
    nav: true,
    dots: true,
    items: 10,
    navText: ["<i class='fa-solid fa-angle-left'></i>", "<i class='fa-solid fa-angle-right'></i>"],
    autoplay: true,
    smartSpeed: 1500,
    autoplayTimeout: 3000,
    responsiveClass: true,
    responsive: {
      0: {
        items: 2,
      },
      600: {
        items: 3,
      },
      1000: {
        items: 5,
      }
    }
  });

  //========== SLIDER ============= //
  $('.slider-section-boxarea').owlCarousel({
    loop: true,
    margin: 30,
    nav: false,
    dots: false,
    items: 10,
    autoplay: true,
    smartSpeed: 1500,
    autoplayTimeout: 3000,
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 2,
      },
      1000: {
        items: 4,
      }
    }
  });

  //========== SLIDER ============= //
  $('.images-area-lightbox').owlCarousel({
    loop: true,
    margin: 30,
    nav: true,
    dots: true,
    items: 10,
    navText: ["<i class='fa-solid fa-angle-left'></i>", "<i class='fa-solid fa-angle-right'></i>"],
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 1,
      },
      1000: {
        items: 1,
      }
    }
  });


  //========== SLIDER ============= //
  $('.property-details-slider').owlCarousel({
    loop: true,
    margin: 30,
    nav: false,
    dots: true,
    items: 10,
    autoplay: true,
    smartSpeed: 1500,
    autoplayTimeout: 3000,
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 2,
      },
      1000: {
        items: 3,
      }
    }
  });

  //========== SLIDER ============= //
  $('.img2-carousel').owlCarousel({
    loop: true,
    margin: 30,
    nav: true,
    dots: true,
    items: 10,
    autoplay: true,
    smartSpeed: 1500,
    autoplayTimeout: 3000,
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 1,
      },
      1000: {
        items: 1,
      }
    }
  });


  //========== SLIDER ============= //
  $('.single-slider-area').owlCarousel({
    loop: true,
    margin: 30,
    nav: false,
    dots: true,
    items: 10,
    autoplay: true,
    smartSpeed: 1500,
    autoplayTimeout: 3000,
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 2,
      },
      1000: {
        items: 3,
      }
    }
  });
  $(".img3-carousel").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    infinite: false,
    infinite: true,
    asNavFor: '.img4',
    prevArrow: $('.next-arrow'),
    nextArrow: $('.prev-arrow'),
  });
  $(".img4").slick({
    slidesToShow: 4,
    slidesToScroll: 4,
    items: 15,
    arrows: true,
    asNavFor: ".img3-carousel",
    infinite: true,
    focusOnSelect: true,
    infinite: true,
    prevArrow: $('.next-arrow'),
    nextArrow: $('.-prev-arrow'),
  });
  //========== SLIDER ============= //

  //========== PRELOADER ============= //
  $(window).on("load", function (event) {
    setTimeout(function () {
      $(".preloader").fadeToggle();
    }, 200);

  });
  //========== PRELOADER BAR AREA ============= //

})(jQuery);

//========== TEXT_ANIMATION AREA ============= //
if ($('.text-anime-style-1').length) {
  let staggerAmount = 0.05,
    translateXValue = 0,
    delayValue = 0.5,
    animatedTextElements = document.querySelectorAll('.text-anime-style-1');

  animatedTextElements.forEach((element) => {
    let animationSplitText = new SplitText(element, { type: "chars, words" });
    gsap.from(animationSplitText.words, {
      duration: 1,
      delay: delayValue,
      x: 20,
      autoAlpha: 0,
      stagger: staggerAmount,
      scrollTrigger: { trigger: element, start: "top 85%" },
    });
  });
}

if ($('.text-anime-style-2').length) {
  let staggerAmount = 0.05,
    translateXValue = 20,
    delayValue = 0.5,
    easeType = "power2.out",
    animatedTextElements = document.querySelectorAll('.text-anime-style-2');

  animatedTextElements.forEach((element) => {
    let animationSplitText = new SplitText(element, { type: "chars, words" });
    gsap.from(animationSplitText.chars, {
      duration: 1,
      delay: delayValue,
      x: translateXValue,
      autoAlpha: 0,
      stagger: staggerAmount,
      ease: easeType,
      scrollTrigger: { trigger: element, start: "top 85%" },
    });
  });
}

if ($('.text-anime-style-3').length) {
  let animatedTextElements = document.querySelectorAll('.text-anime-style-3');

  animatedTextElements.forEach((element) => {
    if (element.animation) {
      element.animation.progress(1).kill();
      element.split.revert();
    }

    element.split = new SplitText(element, {
      type: "lines,words,chars",
      linesClass: "split-line",
    });
    gsap.set(element, { perspective: 400 });

    gsap.set(element.split.chars, {
      opacity: 0,
      x: "50",
    });

    element.animation = gsap.to(element.split.chars, {
      scrollTrigger: { trigger: element, start: "top 90%" },
      x: "0",
      y: "0",
      rotateX: "0",
      opacity: 1,
      duration: 1,
      ease: Back.easeOut,
      stagger: 0.02,
    });
  });
}
//========== TEXT_ANIMATION AREA ============= //

//========== IMAGE ANIMATION ============= //
if ($('.reveal').length) { gsap.registerPlugin(ScrollTrigger); let revealContainers = document.querySelectorAll(".reveal"); revealContainers.forEach((container) => { let image = container.querySelector("img"); let tl = gsap.timeline({ scrollTrigger: { trigger: container, toggleActions: "play none none none" } }); tl.set(container, { autoAlpha: 1 }); tl.from(container, 1.5, { xPercent: -100, ease: Power2.out }); tl.from(image, 1.5, { xPercent: 100, scale: 1.3, delay: -1.5, ease: Power2.out }); }); }
//========== IMAGE ANIMATION ============= //

//========== RENT_SALE TAB ============= //
const tabButtons = document.querySelectorAll(".tab-btn");
const tabContents = document.querySelectorAll(".tab-content1");

tabButtons.forEach((button) => {
  button.addEventListener("click", () => {
    tabButtons.forEach((btn) => btn.classList.remove("active"));
    button.classList.add("active");
    tabContents.forEach((content) => (content.style.display = "none"));

    const tabId = button.getAttribute("data-tab");
    document.getElementById(tabId).style.display = "block";
  });
});
//========== RENT_SALE TAB ============= //

//========== SWIPER SLIDER ============= //
document.addEventListener("DOMContentLoaded", function () {
  var swiper3 = new Swiper(".swiper-thumb2", {
    spaceBetween: 10,
    slidesPerView: 6,
    freeMode: true,
    watchSlidesProgress: true,
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
  });
  var swiper4 = new Swiper(".swiper-testimonial-2", {
    spaceBetween: 10,
    loop: true,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
    thumbs: {
      swiper: swiper3,
    },
  });

  var swiperFades = document.querySelectorAll(".swiper-fade");

  swiperFades.forEach(function (swiperFade, index) {
    var swiper = new Swiper(swiperFade, {
      spaceBetween: 0,
      slidesPerView: 1,
      freeMode: true,
      watchSlidesProgress: true,
      autoplay: {
        delay: 2500,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
  });

});
//========== SHOW FORM ============= //

//========== SHOW FORM ============= //
var showform = function () {
  if ($(".show-form").length > 0) {
    $(".show-form").on("click", function (e) {
      e.preventDefault();
      $(this).toggleClass("click");
      $(".wd-search-form").toggleClass("show-box");
    });
  }
};
//========== SHOW FORM ============= //
function initializeSlider(minId, maxId, fillClass, outputId, formatCallback) {
  const minRange = document.getElementById(minId);
  const maxRange = document.getElementById(maxId);
  const sliderFill = document.querySelector(`.${fillClass}`);
  const output = document.getElementById(outputId);

  // Exit silently if any required element is missing
  if (!minRange || !maxRange || !sliderFill || !output) {
    return;
  }

  function updateSlider() {
    const minValue = parseInt(minRange.value, 10);
    const maxValue = parseInt(maxRange.value, 10);
    const totalRange = maxRange.max - minRange.min;

    if (minValue > maxValue) return;

    const minPercent = ((minValue - minRange.min) / totalRange) * 100;
    const maxPercent = ((maxValue - minRange.min) / totalRange) * 100;

    sliderFill.style.left = `${minPercent}%`;
    sliderFill.style.width = `${maxPercent - minPercent}%`;

    output.textContent = formatCallback(minValue, maxValue);
  }

  minRange.addEventListener("input", updateSlider);
  maxRange.addEventListener("input", updateSlider);
  updateSlider();
}

// Initialize sliders
initializeSlider(
  "price-range-min",
  "price-range-max",
  "price-slider .slider-fill",
  "price-output",
  (min, max) => `$${min.toLocaleString()} - $${max.toLocaleString()}`
);

initializeSlider(
  "size-range-min",
  "size-range-max",
  "size-slider .slider-fill",
  "size-output",
  (min, max) => `${min} SqFt - ${max} SqFt`
);



//========== RANGE SLIDER ============= //

//========== HEADING TEXT ANIMATION ============= //
var words = ['Fast And Smarter', 'Fits Dream Home', 'Fits Perfectly',],
  part,
  i = 0,
  offset = 0,
  len = words.length,
  forwards = true,
  skip_count = 0,
  skip_delay = 15,
  speed = 70;
var wordflick = function () {
  setInterval(function () {
    if (forwards) {
      if (offset >= words[i].length) {
        ++skip_count;
        if (skip_count == skip_delay) {
          forwards = false;
          skip_count = 0;
        }
      }
    }
    else {
      if (offset == 0) {
        forwards = true;
        i++;
        offset = 0;
        if (i >= len) {
          i = 0;
        }
      }
    }
    part = words[i].substr(0, offset);
    if (skip_count == 0) {
      if (forwards) {
        offset++;
      }
      else {
        offset--;
      }
    }
    $('.word').text(part);
  }, speed);
};

$(document).ready(function () {
  wordflick();
});
//========== HEADING TEXT ANIMATION ============= //



//TOGGLE SEARCH filter FORM

// Select the toggle button and the search filter form
const openSearchButton = document.querySelector('.open-search-filter-form');
const searchFilterForm = document.querySelector('.search-filter-form');

// Check if both elements exist before adding event listeners
if (openSearchButton && searchFilterForm) {
  // Add click event listener to the button
  openSearchButton.addEventListener('click', () => {
    // Toggle 'active' class on both the button and the search form
    openSearchButton.classList.toggle('active');
    searchFilterForm.classList.toggle('active');
  });
}


// Select the toggle button and the filter options form
const toggleFilterButton = document.querySelector('.open-filter-form');
const filterOptionsForm = document.querySelector('.filter-options-form');

// Check if both elements exist before adding event listeners
if (toggleFilterButton && filterOptionsForm) {
  // Add click event listener to the button
  toggleFilterButton.addEventListener('click', () => {
    // Toggle 'active' class on the button
    toggleFilterButton.classList.toggle('active');
    // Toggle 'active' class on the filter form
    filterOptionsForm.classList.toggle('active');
  });
}


