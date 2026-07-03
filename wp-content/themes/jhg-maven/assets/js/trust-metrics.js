/**
 * Trust metrics — count-up animation when section enters viewport.
 */
(function () {
  "use strict";

  var DURATION_MS = 1800;

  function formatNumber(value, useCommas) {
    if (useCommas) {
      return value.toLocaleString("en-US");
    }

    return String(value);
  }

  function easeOutCubic(progress) {
    return 1 - Math.pow(1 - progress, 3);
  }

  function runCounter(el) {
    var target = parseInt(el.getAttribute("data-count-to"), 10);
    var suffix = el.getAttribute("data-count-suffix") || "";
    var useCommas = el.hasAttribute("data-count-commas");

    if (Number.isNaN(target)) {
      return;
    }

    el.classList.add("is-counting");

    var start = null;

    function tick(timestamp) {
      if (!start) {
        start = timestamp;
      }

      var progress = Math.min((timestamp - start) / DURATION_MS, 1);
      var current = Math.round(target * easeOutCubic(progress));

      el.textContent = formatNumber(current, useCommas) + suffix;

      if (progress < 1) {
        window.requestAnimationFrame(tick);
      } else {
        el.textContent = formatNumber(target, useCommas) + suffix;
        el.classList.remove("is-counting");
        el.classList.add("is-counted");
      }
    }

    window.requestAnimationFrame(tick);
  }

  function initCounter(el) {
    if (el.dataset.countStarted === "1") {
      return;
    }

    el.dataset.countStarted = "1";
    runCounter(el);
  }

  document.addEventListener("DOMContentLoaded", function () {
    var counters = document.querySelectorAll(".jhg-trust-metrics-value-count");

    if (!counters.length) {
      return;
    }

    var reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    if (reducedMotion) {
      counters.forEach(function (el) {
        var target = parseInt(el.getAttribute("data-count-to"), 10);
        var suffix = el.getAttribute("data-count-suffix") || "";
        var useCommas = el.hasAttribute("data-count-commas");

        if (!Number.isNaN(target)) {
          el.textContent = formatNumber(target, useCommas) + suffix;
        }
      });
      return;
    }

    var section = document.querySelector(".jhg-trust-metrics");

    if (!section || !("IntersectionObserver" in window)) {
      counters.forEach(initCounter);
      return;
    }

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) {
            return;
          }

          entry.target.querySelectorAll(".jhg-trust-metrics-value-count").forEach(initCounter);
          observer.unobserve(entry.target);
        });
      },
      { threshold: 0.35, rootMargin: "0px 0px -5% 0px" }
    );

    observer.observe(section);
  });
})();
