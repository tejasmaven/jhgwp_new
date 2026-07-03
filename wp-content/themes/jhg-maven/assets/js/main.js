(function () {
  "use strict";

  var DESKTOP_NAV_MQ = window.matchMedia("(min-width: 992px)");

  function initMobileNav() {
    var offcanvasEl = document.getElementById("jhg-mobile-nav");
    var openToggle = document.querySelector(
      '.jhg-nav-toggle[data-bs-target="#jhg-mobile-nav"]'
    );

    if (!offcanvasEl || !openToggle || typeof bootstrap === "undefined") {
      return;
    }

    function setOpenState(isOpen) {
      openToggle.classList.toggle("is-active", isOpen);
      openToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
      openToggle.setAttribute(
        "aria-label",
        isOpen ? "Close menu" : "Open menu"
      );
      document.body.classList.toggle("jhg-nav-open", isOpen);
    }

    offcanvasEl.addEventListener("show.bs.offcanvas", function () {
      setOpenState(true);
    });

    offcanvasEl.addEventListener("hidden.bs.offcanvas", function () {
      setOpenState(false);

      offcanvasEl.querySelectorAll(".jhg-nav-mobile .nav-item.is-expanded").forEach(
        function (item) {
          item.classList.remove("is-expanded");
        }
      );

      offcanvasEl.querySelectorAll(".jhg-nav-mobile .jhg-mobile-expand").forEach(
        function (btn) {
          btn.setAttribute("aria-expanded", "false");
        }
      );
    });

    offcanvasEl.querySelectorAll(".jhg-nav-mobile .jhg-nav-dropdown-link").forEach(
      function (link) {
        link.addEventListener("click", function () {
          var instance = bootstrap.Offcanvas.getInstance(offcanvasEl);
          if (instance) {
            instance.hide();
          }
        });
      }
    );

    offcanvasEl.querySelectorAll(".jhg-nav-mobile .jhg-mobile-expand").forEach(
      function (btn) {
        btn.addEventListener("click", function (event) {
          event.preventDefault();
          event.stopPropagation();

          var item = btn.closest(".nav-item");

          if (!item) {
            return;
          }

          var isOpen = item.classList.toggle("is-expanded");
          btn.setAttribute("aria-expanded", isOpen ? "true" : "false");
        });
      }
    );

  }

  function alignCardDropdownPanels() {
    if (!DESKTOP_NAV_MQ.matches) {
      return;
    }

    document
      .querySelectorAll(".jhg-header-nav .jhg-nav-dropdown-card-parent")
      .forEach(function (item) {
        var trigger = item.querySelector(
          ":scope > .nav-link, :scope > .jhg-nav-parent-trigger"
        );
        var cardInner = item.querySelector(
          ":scope > .jhg-nav-dropdown-card .jhg-nav-dropdown-card-inner"
        );

        if (!trigger || !cardInner) {
          return;
        }

        var triggerRect = trigger.getBoundingClientRect();
        var innerRect = cardInner.getBoundingClientRect();
        var inset = Math.round(triggerRect.left - innerRect.left);

        cardInner.style.setProperty("--jhg-card-content-inset", inset + "px");
      });
  }

  function initDesktopNavDropdowns() {
    var items = document.querySelectorAll(
      ".jhg-header-nav .jhg-dropdown-hover"
    );

    items.forEach(function (item) {
      var trigger = item.querySelector(
        ":scope > .nav-link, :scope > .jhg-nav-parent-trigger"
      );
      var closeTimer;

      if (!trigger) {
        return;
      }

      function openMenu() {
        if (!DESKTOP_NAV_MQ.matches) {
          return;
        }
        window.clearTimeout(closeTimer);
        item.classList.add("is-open");
        trigger.setAttribute("aria-expanded", "true");
        alignCardDropdownPanels();
        window.requestAnimationFrame(alignCardDropdownPanels);
      }

      function closeMenu() {
        closeTimer = window.setTimeout(function () {
          item.classList.remove("is-open");
          trigger.setAttribute("aria-expanded", "false");
        }, 140);
      }

      var panel = item.querySelector(":scope > .jhg-nav-dropdown");

      item.addEventListener("mouseenter", openMenu);
      item.addEventListener("mouseleave", closeMenu);
      item.addEventListener("focusin", openMenu);
      item.addEventListener("focusout", function (event) {
        if (!item.contains(event.relatedTarget)) {
          closeMenu();
        }
      });

      if (panel) {
        panel.addEventListener("mouseenter", openMenu);
        panel.addEventListener("mouseleave", closeMenu);
      }
    });

    alignCardDropdownPanels();
    window.addEventListener("resize", alignCardDropdownPanels);
    window.addEventListener("load", alignCardDropdownPanels);
  }

  document.addEventListener("DOMContentLoaded", function () {
    initMobileNav();
    initDesktopNavDropdowns();
  });
})();
