function caracSpeciauxType() {
    const container = document.querySelector('.questions-contener');
    if (!container) return;


    // Évite double attachement (DOMContentLoaded + turbo:load)
    if (container.dataset.uiInit === '1') return;
    container.dataset.uiInit = '1';

    // Délégation d'événements pour gérer aussi les éléments injectés dynamiquement
    container.addEventListener('click', (e) => {
        const target = e.target;
        const triggerBtn = target.closest('.carac-speciaux');
        if (triggerBtn) {
            const input = triggerBtn.closest('.questions-contener').querySelector('input');
            input.value += triggerBtn.textContent.trim();
            input.focus();

        }
    });
}

//Turbo:load => Pour quand on vient de login (parce que symfony utilise turbo qui ne charge que le body et pas toute la page)
document.addEventListener('turbo:load', caracSpeciauxType);
document.addEventListener('DOMContentLoaded', () => {
    caracSpeciauxType();
});


