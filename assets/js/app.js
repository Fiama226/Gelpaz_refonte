(() => {
  const body = document.body;
  const menuButton = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.main-nav');

  if (menuButton && nav) {
    menuButton.addEventListener('click', () => {
      const open = nav.classList.toggle('is-open');
      menuButton.setAttribute('aria-expanded', String(open));
      body.classList.toggle('is-menu-open', open);
    });

    nav.querySelectorAll('.nav-dropdown > button').forEach((button) => {
      button.addEventListener('click', () => {
        button.parentElement.classList.toggle('is-open');
      });
    });

    nav.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => {
        nav.classList.remove('is-open');
        body.classList.remove('is-menu-open');
        menuButton.setAttribute('aria-expanded', 'false');
      });
    });
  }

  const topButton = document.querySelector('.back-to-top');
  if (topButton) {
    window.addEventListener('scroll', () => {
      topButton.classList.toggle('is-visible', window.scrollY > 500);
    }, { passive: true });
    topButton.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }

  document.querySelectorAll('form').forEach((form) => {
    form.addEventListener('submit', (event) => {
      if (form.classList.contains('newsletter-form')) {
        event.preventDefault();
        const button = form.querySelector('button');
        if (button) button.textContent = '✓';
      }
    });
  });
})();
