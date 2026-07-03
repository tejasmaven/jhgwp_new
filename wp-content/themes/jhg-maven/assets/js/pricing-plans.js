(function () {
  var sections = document.querySelectorAll('.jhg-pricing-plans[data-default-period]');

  if (!sections.length) {
    return;
  }

  sections.forEach(function (section) {
    var defaultPeriod = section.getAttribute('data-default-period') || 'annual';
    var tabs = section.querySelectorAll('.jhg-pricing-plans-tab');
    var cards = section.querySelectorAll('.jhg-pricing-card[data-period]');

    function showPeriod(period) {
      cards.forEach(function (card) {
        var match = card.getAttribute('data-period') === period;
        card.hidden = !match;
      });

      tabs.forEach(function (tab) {
        var active = tab.getAttribute('data-period') === period;
        tab.classList.toggle('is-active', active);
        tab.setAttribute('aria-selected', active ? 'true' : 'false');
      });
    }

    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        showPeriod(tab.getAttribute('data-period'));
      });
    });

    showPeriod(defaultPeriod);
  });
})();
