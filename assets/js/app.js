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
      const dropdown = button.parentElement;
      const setOpen = (open) => {
        dropdown.classList.toggle('is-open', open);
        button.setAttribute('aria-expanded', String(open));
      };
      button.addEventListener('click', () => setOpen(!dropdown.classList.contains('is-open')));
      dropdown.addEventListener('mouseenter', () => { if (window.matchMedia('(min-width: 761px)').matches) setOpen(true); });
      dropdown.addEventListener('mouseleave', () => { if (window.matchMedia('(min-width: 761px)').matches && !dropdown.contains(document.activeElement)) setOpen(false); });
      dropdown.addEventListener('focusout', (event) => {
        if (!dropdown.contains(event.relatedTarget)) setOpen(false);
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

  const header = document.querySelector('.site-header');
  if (header) {
    const toggleHeader = () => header.classList.toggle('is-scrolled', window.scrollY > 90);
    window.addEventListener('scroll', toggleHeader, { passive: true });
    toggleHeader();
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

    const setPausedIcon = (isPaused) => {
      const use = pauseButton?.querySelector('use');
      if (!use) return;
      const icon = isPaused ? '#i-play' : '#i-pause';
      use.setAttribute('href', icon);
      use.setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', icon);
    };

    const setPaused = (value) => {
      paused = value;
      setPausedIcon(paused);
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


  /* Gelpaz media is hotlinked: if a larger variant is missing on the source
     server, fall back to the smaller one instead of showing a broken image. */
  const applyFallback = (image) => {
    const fallback = image.dataset.fallback;
    if (!fallback || image.dataset.fallbackUsed) return;
    image.dataset.fallbackUsed = 'true';
    image.removeAttribute('srcset');
    image.src = fallback;
  };
  document.querySelectorAll('img[data-fallback]').forEach((image) => {
    image.addEventListener('error', () => applyFallback(image));
    // the error may already have fired while the page was parsing
    if (image.complete && image.naturalWidth === 0) applyFallback(image);
  });

  /* Property gallery lightbox */
  const lightbox = document.querySelector('.lightbox');
  const gallery = document.querySelector('[data-gallery]');
  if (lightbox && gallery) {
    const allTriggers = [...gallery.querySelectorAll('[data-lightbox-open]')];
    const thumbs = allTriggers.filter((trigger) => trigger.dataset.photo);
    const triggers = thumbs.length ? thumbs : allTriggers;
    const images = triggers.map((trigger) => trigger.dataset.photo
      || trigger.querySelector('img')?.getAttribute('src'));
    const alts = triggers.map((trigger) => trigger.querySelector('img')?.alt || '');
    // the main photo mirrors the first thumbnail: point its trigger at that slide
    const mainTrigger = allTriggers.find((trigger) => !trigger.dataset.photo);
    const mainIndex = mainTrigger
      ? Math.max(0, images.indexOf(mainTrigger.querySelector('img')?.getAttribute('src')))
      : -1;
    const stage = lightbox.querySelector('[data-lightbox-image]');
    const caption = lightbox.querySelector('[data-lightbox-caption]');
    const previous = lightbox.querySelector('[data-lightbox-previous]');
    const next = lightbox.querySelector('[data-lightbox-next]');
    const close = () => {
      lightbox.classList.remove('is-open');
      lightbox.hidden = true;
      document.body.classList.remove('is-menu-open');
      lastTrigger?.focus();
    };
    let current = 0;
    let lastTrigger = null;

    const show = (index) => {
      current = (index + images.length) % images.length;
      stage.src = images[current];
      stage.alt = alts[current] || '';
      caption.textContent = `${current + 1} / ${images.length}`;
      gallery.querySelectorAll('.property-gallery__thumbs button').forEach((button, buttonIndex) => {
        button.setAttribute('aria-current', String(buttonIndex === current));
      });
    };

    allTriggers.forEach((trigger) => {
      const index = trigger === mainTrigger ? mainIndex : triggers.indexOf(trigger);
      trigger.addEventListener('click', () => {
        lastTrigger = trigger;
        show(index);
        lightbox.hidden = false;
        requestAnimationFrame(() => lightbox.classList.add('is-open'));
        document.body.classList.add('is-menu-open');
        lightbox.querySelector('[data-lightbox-close]')?.focus();
      });
    });

    previous?.addEventListener('click', () => show(current - 1));
    next?.addEventListener('click', () => show(current + 1));
    lightbox.querySelector('[data-lightbox-close]')?.addEventListener('click', close);
    lightbox.addEventListener('click', (event) => {
      if (event.target === lightbox) close();
    });
    document.addEventListener('keydown', (event) => {
      if (lightbox.hidden) return;
      if (event.key === 'Escape') close();
      if (event.key === 'ArrowLeft') show(current - 1);
      if (event.key === 'ArrowRight') show(current + 1);
    });
  }
})();
