document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('[data-menu-toggle]');
  const menu = document.querySelector('[data-menu]');
  if (toggle && menu) {
    toggle.addEventListener('click', () => menu.classList.toggle('open'));
  }

  document.querySelectorAll('a[href^="#"], a[href*="/#"]').forEach((link) => {
    link.addEventListener('click', () => {
      if (menu) menu.classList.remove('open');
    });
  });

  const processSteps = document.querySelectorAll('[data-process-steps] article');
  const processPanel = document.querySelector('[data-process-panel]');
  const processLabel = document.querySelector('[data-process-label]');
  const processContent = document.querySelector('[data-process-content]');

  /* ---- Desktop: side panel (unchanged from original) ---- */
  const activateDesktop = (step) => {
    if (!processPanel || !processLabel || !processContent) return;
    processSteps.forEach((item) => item.classList.toggle('active', item === step));
    processLabel.textContent = step.dataset.label || '';
    processContent.innerHTML = step.dataset.panel || '';
    processPanel.classList.remove('is-changing');
    window.requestAnimationFrame(() => processPanel.classList.add('is-changing'));
  };

  /* ---- Mobile: accordion under each step ---- */
  let accordionsCreated = false;

  function createAccordions() {
    if (accordionsCreated) return;
    processSteps.forEach((step) => {
      const panelHtml = step.dataset.panel || '';
      const label = step.dataset.label || '';
      const el = document.createElement('div');
      el.className = 'step-accordion';
      el.innerHTML = '<span>' + label + '</span>' + panelHtml;
      step.after(el);
      step._acc = el;
    });
    accordionsCreated = true;
    // open first
    if (processSteps[0] && processSteps[0]._acc) {
      processSteps[0]._acc.classList.add('open');
    }
  }

  function removeAccordions() {
    if (!accordionsCreated) return;
    document.querySelectorAll('.step-accordion').forEach(el => el.remove());
    processSteps.forEach(s => delete s._acc);
    accordionsCreated = false;
  }

  const activateMobile = (step) => {
    const wasActive = step.classList.contains('active');
    processSteps.forEach((item) => {
      item.classList.remove('active');
      if (item._acc) item._acc.classList.remove('open');
    });
    if (!wasActive) {
      step.classList.add('active');
      if (step._acc) step._acc.classList.add('open');
    }
  };

  /* ---- Event listeners ---- */
  processSteps.forEach((step) => {
    step.addEventListener('click', () => {
      if (window.innerWidth <= 640 && step._acc) {
        activateMobile(step);
      } else {
        activateDesktop(step);
      }
    });
    step.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        if (window.innerWidth <= 640 && step._acc) {
          activateMobile(step);
        } else {
          activateDesktop(step);
        }
      }
    });
  });

  /* ---- Init & resize ---- */
  if (window.innerWidth <= 640) createAccordions();

  let wasMobile = window.innerWidth <= 640;
  window.addEventListener('resize', () => {
    const nowMobile = window.innerWidth <= 640;
    if (nowMobile && !wasMobile) createAccordions();
    if (!nowMobile && wasMobile) removeAccordions();
    wasMobile = nowMobile;
  });

  /* ---- Mobile: auto-scroll pricing to featured card ---- */
  if (window.innerWidth <= 640) {
    const pricingGrid = document.querySelector('.pricing-grid');
    const featured = pricingGrid ? pricingGrid.querySelector('.featured') : null;
    if (pricingGrid && featured) {
      setTimeout(() => {
        const offset = featured.offsetLeft - (pricingGrid.offsetWidth - featured.offsetWidth) / 2;
        pricingGrid.scrollLeft = Math.max(0, offset);
      }, 100);
    }
  }
});
