/* Anova Router — mobile menu toggle (no dependencies). */
(function () {
  var toggle = document.querySelector('.ar-nav-toggle');
  var nav = document.getElementById('ar-primary-nav');
  if (!toggle || !nav) return;
  function set(open) {
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    nav.classList.toggle('is-open', open);
    document.body.classList.toggle('ar-nav-open', open);
  }
  toggle.addEventListener('click', function () { set(toggle.getAttribute('aria-expanded') !== 'true'); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && nav.classList.contains('is-open')) { set(false); toggle.focus(); } });
  window.addEventListener('resize', function () { if (window.innerWidth > 900) set(false); });
})();
