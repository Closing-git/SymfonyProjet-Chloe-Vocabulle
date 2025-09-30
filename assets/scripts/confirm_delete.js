function DeleteConfirm() {
    const container = document.querySelector('.cards-contener');
    if (!container) return;

    // Délégation d'événements pour gérer aussi les éléments injectés dynamiquement
    container.addEventListener('click', (e) => {
        const target = e.target;

        // 1) Clic sur un bouton .supprimer-button déclencheur
        const triggerBtn = target.closest('.supprimer-button');
        if (triggerBtn && !triggerBtn.closest('.confirm_delete')) {
            e.preventDefault(); // Empêche la navigation si le bouton est dans un <a>
            const card = triggerBtn.closest('.card');
            if (!card) return;
            const confirmBlock = card.querySelector('.confirm_delete');
            if (!confirmBlock) return;

            confirmBlock.classList.remove('hidden');
            confirmBlock.classList.add('show');
            return; // ne pas poursuivre
        }

        // 2) Clic sur "Annuler" dans le bloc de confirmation
        const cancelBtn = target.closest('.cancel_delete');
        if (cancelBtn) {
            e.preventDefault();
            const confirmBlock = cancelBtn.closest('.confirm_delete');
            if (!confirmBlock) return;
            confirmBlock.classList.remove('show');
            confirmBlock.classList.add('hidden');
            return;
        }

        // 3) Clic en dehors du bloc ouvert -> fermer
        const anyOpen = container.querySelector('.confirm_delete.show');
        if (anyOpen) {
            const clickedInside = anyOpen.contains(target);
            const clickedTrigger = !!target.closest('.supprimer-button');
            if (!clickedInside && !clickedTrigger) {
                anyOpen.classList.remove('show');
                anyOpen.classList.add('hidden');
            }
        }
    });

    // 4) Echap -> fermer le bloc ouvert
    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        const open = container.querySelector('.confirm_delete.show');
        if (!open) return;
        open.classList.remove('show');
        open.classList.add('hidden');
    });
}

//Turbo:load => Pour quand on vient de login (parce que symfony utilise turbo qui ne charge que le body et pas toute la page)
document.addEventListener('turbo:load', DeleteConfirm);
document.addEventListener('DOMContentLoaded', () => {
    DeleteConfirm();
});


