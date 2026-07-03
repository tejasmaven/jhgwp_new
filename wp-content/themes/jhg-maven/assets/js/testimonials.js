/**
 * Testimonials slider — rewind mode (no loop clones).
 */
(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    if (typeof Swiper === "undefined") {
      return;
    }

    var reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    document.querySelectorAll(".jhg-testimonials-slider").forEach(function (slider) {
      var swiperEl = slider.querySelector(".jhg-testimonials-swiper");
      if (!swiperEl || swiperEl.swiper) {
        return;
      }

      var desktop = parseInt(slider.getAttribute("data-desktop-cards"), 10) === 3 ? 3 : 2;
      var total = swiperEl.querySelectorAll(".swiper-slide").length;
      if (total < 1) {
        return;
      }

      new Swiper(swiperEl, {
        rewind: true,
        slidesPerView: 1,
        slidesPerGroup: 1,
        spaceBetween: 16,
        speed: 500,
        autoplay: reducedMotion
          ? false
          : {
              delay: 5000,
              disableOnInteraction: false,
              pauseOnMouseEnter: true,
            },
        navigation: {
          prevEl: slider.querySelector(".jhg-testimonials-prev"),
          nextEl: slider.querySelector(".jhg-testimonials-next"),
        },
        pagination: {
          el: slider.querySelector(".jhg-testimonials-pagination"),
          clickable: true,
        },
        breakpoints: {
          768: {
            slidesPerView: Math.min(2, total),
            spaceBetween: 24,
          },
          1200: {
            slidesPerView: Math.min(desktop, total),
            spaceBetween: 36,
          },
        },
      });
    });
  });
})();
