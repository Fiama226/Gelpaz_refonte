(() => {
  const body = document.body;
  const menuButton = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.main-nav');

  if (menuButton && nav) {
    menuButton.addEventListener('click', () => {
      const open = nav.classList.toggle('is-open');
      menuButton.setAttribute('aria-expanded', String(open));
      menuButton.querySelector('.sr-only').textContent = open ? 'Fermer le menu' : 'Ouvrir le menu';
      body.classList.toggle('is-menu-open', open);
    });

    nav.querySelectorAll('.nav-dropdown > button').forEach((button) => {
      button.addEventListener('click', () => {
        const open = button.parentElement.classList.toggle('is-open');
        button.setAttribute('aria-expanded', String(open));
      });
    });

    nav.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => {
        nav.classList.remove('is-open');
        body.classList.remove('is-menu-open');
        menuButton.setAttribute('aria-expanded', 'false');
        menuButton.querySelector('.sr-only').textContent = 'Ouvrir le menu';
      });
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        nav.classList.remove('is-open');
        nav.querySelectorAll('.nav-dropdown.is-open').forEach((dropdown) => {
          dropdown.classList.remove('is-open');
          dropdown.querySelector('button')?.setAttribute('aria-expanded', 'false');
        });
        body.classList.remove('is-menu-open');
        menuButton.setAttribute('aria-expanded', 'false');
        menuButton.querySelector('.sr-only').textContent = 'Ouvrir le menu';
      }
    });
  }

  const topButton = document.querySelector('.back-to-top');
  if (topButton) {
    window.addEventListener('scroll', () => {
      topButton.classList.toggle('is-visible', window.scrollY > 500);
    }, { passive: true });
    topButton.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }

  const slideshow = document.querySelector('.hero-slideshow');
  const hero = document.querySelector('.home-hero');
  if (slideshow && hero) {
    const slides = [...slideshow.querySelectorAll('.hero-slide')];
    const dots = [...hero.querySelectorAll('[data-slide-to]')];
    const previousButton = hero.querySelector('.hero-control--previous');
    const nextButton = hero.querySelector('.hero-control--next');
    const pauseButton = hero.querySelector('.hero-control--pause');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    let activeIndex = 0;
    let timer;
    let paused = reducedMotion.matches;

    const updateSlide = (index) => {
      activeIndex = (index + slides.length) % slides.length;
      slides.forEach((slide, slideIndex) => {
        slide.classList.toggle('is-active', slideIndex === activeIndex);
      });
      dots.forEach((dot, dotIndex) => {
        dot.setAttribute('aria-current', String(dotIndex === activeIndex));
      });
    };

    const stopTimer = () => {
      window.clearInterval(timer);
      timer = undefined;
    };

    const startTimer = () => {
      stopTimer();
      if (!paused && !reducedMotion.matches && slides.length > 1) {
        timer = window.setInterval(() => updateSlide(activeIndex + 1), 7000);
      }
    };

    const setPaused = (value) => {
      paused = value;
      pauseButton.textContent = paused ? '▶' : 'Ⅱ';
      pauseButton.setAttribute('aria-label', paused ? 'Lire le diaporama' : 'Mettre le diaporama en pause');
      startTimer();
    };

    previousButton?.addEventListener('click', () => updateSlide(activeIndex - 1));
    nextButton?.addEventListener('click', () => updateSlide(activeIndex + 1));
    pauseButton?.addEventListener('click', () => setPaused(!paused));
    dots.forEach((dot) => dot.addEventListener('click', () => updateSlide(Number(dot.dataset.slideTo))));
    hero.addEventListener('mouseenter', stopTimer);
    hero.addEventListener('mouseleave', startTimer);
    hero.addEventListener('focusin', stopTimer);
    hero.addEventListener('focusout', (event) => {
      if (!hero.contains(event.relatedTarget)) startTimer();
    });
    document.addEventListener('visibilitychange', () => document.hidden ? stopTimer() : startTimer());
    reducedMotion.addEventListener?.('change', (event) => {
      paused = event.matches;
      startTimer();
    });
    startTimer();
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
