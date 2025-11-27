function ReturnConfirm() {

    //Choisis l'une ou l'autre div en fonction de la page (page questions ou questions_corriges ou page modifier)
    let container = document.querySelector('.questions-contener') || document.querySelector('.modifier-liste-contener');
    //Si y'a pas de container correspondant, ne fait rien
    if (!container) {
        return;
    }
    else {
        // Évite double attachement (DOMContentLoaded + turbo:load)
        if (container.dataset.uiInit === '1') return;
        container.dataset.uiInit = '1';
    }

    let logo = document.querySelector('#logo');

    // Gestion du clic sur le logo
    logo.addEventListener('click', (e) => {
        e.preventDefault(); // Empêche la navigation si le logo vu que logo est dans un <a>

        const confirmBlock = container.querySelector('.confirm_delete');
        if (!confirmBlock) return;

        // Passe de hidden à show et vice versa
        if (confirmBlock.classList.contains('hidden')) {
            confirmBlock.classList.remove('hidden');
            confirmBlock.classList.add('show-delete');
        } else {
            confirmBlock.classList.remove('show-delete');
            confirmBlock.classList.add('hidden');
        }
    });


    // Gestion du clic n'importe où sur le document pour quitter la modale
    document.addEventListener('click', (e) => {
        const target = e.target;
        //Récupère la modale ouverte
        const anyOpen = document.querySelector('.confirm_delete.show-delete');

        // Si une modale est ouverte et que le clic est en dehors
        if (anyOpen) {
            const clickedInsideModal = anyOpen.contains(target);
            const clickedOnTrigger = target.closest('.supprimer-button') || target.closest('#logo');

            //Si on ne clique pas dans le modal ni sur le bouton (logo ou close), alors ferme
            if (!clickedInsideModal && !clickedOnTrigger) {
                anyOpen.classList.remove('show-delete');
                anyOpen.classList.add('hidden');
                return;
            }
        }

        // Si le clic est sur le conteneur, on continue 
        if (!container.contains(target)) return;

        // 1) Clic sur un bouton .supprimer-button 
        const triggerBtn = target.closest('.supprimer-button');
        if (triggerBtn && !triggerBtn.closest('.confirm_delete')) {
            e.preventDefault(); // Empêche le comportement par défaut

            const confirmBlock = container.querySelector('.confirm_delete');
            console.log(confirmBlock)

            if (!confirmBlock) return;
            confirmBlock.classList.remove('hidden');
            confirmBlock.classList.add('show-delete');
            return; // ne pas poursuivre
        }

        // 2) Clic sur "Annuler" dans le bloc de confirmation
        const cancelBtn = target.closest('.cancel_delete');
        if (cancelBtn) {
            e.preventDefault();
            const confirmBlock = cancelBtn.closest('.confirm_delete');
            if (!confirmBlock) return;
            confirmBlock.classList.remove('show-delete');
            confirmBlock.classList.add('hidden');
            return;
        }

        // 3) Clic en dehors du bloc ouvert -> fermer
        const openModal = container.querySelector('.confirm_delete.show-delete');
        if (openModal) {
            const clickedInside = openModal.contains(target);
            const clickedOnTrigger = target.closest('.supprimer-button') || target.closest('#logo');

            if (!clickedInside && !clickedOnTrigger) {
                openModal.classList.remove('show-delete');
                openModal.classList.add('hidden');
            }
        }
    });

    // 4) Echap -> fermer le bloc ouvert
    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        const open = container.querySelector('.confirm_delete.show-delete');
        if (!open) return;
        open.classList.remove('show-delete');
        open.classList.add('hidden');
    });
}

//Turbo:load => Pour quand on vient de login (parce que symfony utilise turbo qui ne charge que le body et pas toute la page)
document.addEventListener('turbo:load', ReturnConfirm);
document.addEventListener('DOMContentLoaded', () => {
    ReturnConfirm();
});


