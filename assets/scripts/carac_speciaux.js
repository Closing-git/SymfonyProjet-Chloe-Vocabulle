function caracSpeciauxType() {
    const container = document.querySelector('.questions-contener');

    if (!container) {
        return;
    }

    if (container.dataset.uiInit === '1') {
        container.dataset.uiInit = '0';
        return;
    }
    container.dataset.uiInit = '1';

    document.addEventListener('click', (e) => {
        const target = e.target;
        const triggerBtn = target.closest('.carac-speciaux');

        if (!triggerBtn) return;

        const input = container.querySelector('input[type="text"], input:not([type])');
        if (!input) {
            return;
        }

        input.value += triggerBtn.textContent.trim();
        input.focus();
    });
}

// Gestion du chargement initial et des navigations Turbo
document.addEventListener('turbo:render', caracSpeciauxType);
document.addEventListener('turbo:load', caracSpeciauxType);
document.addEventListener('DOMContentLoaded', caracSpeciauxType);