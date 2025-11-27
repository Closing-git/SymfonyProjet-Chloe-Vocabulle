function caracSpeciauxType() {
    const container = document.querySelector('.questions-contener');

    if (!container) {
        return;
    }

    if (container.dataset.uiInitCarac === '1') {
        container.dataset.uiInitCarac = '0';
        return;
    }
    container.dataset.uiInitCarac = '1';

    document.addEventListener('click', (e) => {
        const target = e.target;
        const triggerBtn = target.closest('.carac-speciaux');

        if (!triggerBtn) return;

        // Cibler l'input dans le formulaire
        const form = container.querySelector('form');
        if (!form) {
            return;
        }

        const input = form.querySelector('input[type="text"], input:not([type])');
        if (!input) {

            return;
        }
        

        input.value += triggerBtn.textContent.trim();
        input.focus();
    });
}

// Gestion du chargement initial et des navigations Turbo

document.addEventListener('turbo:load', caracSpeciauxType);
document.addEventListener('DOMContentLoaded', caracSpeciauxType);