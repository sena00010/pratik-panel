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

  const activateProcessStep = (step) => {
    if (!step || !processPanel || !processLabel || !processContent) return;
    processSteps.forEach((item) => item.classList.toggle('active', item === step));
    processLabel.textContent = step.dataset.label || '';
    processContent.innerHTML = step.dataset.panel || '';
    processPanel.classList.remove('is-changing');
    window.requestAnimationFrame(() => processPanel.classList.add('is-changing'));
  };

  processSteps.forEach((step) => {
    step.addEventListener('click', () => activateProcessStep(step));
    step.addEventListener('keydown', (event) => {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        activateProcessStep(step);
      }
    });
  });
});
