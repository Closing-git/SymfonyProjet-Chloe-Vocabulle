function input_facile() {
        const form = document.getElementById('quizzFacileForm');
        const boutonsReponse = document.querySelectorAll('button[name="reponse_bouton"]');
        const reponseUtilisateur = document.getElementById('reponse');
        if (!form || !boutonsReponse || !reponseUtilisateur) {
            return
        }
        else {
            // Évite double attachement (DOMContentLoaded + turbo:load)
            if (reponseUtilisateur.dataset.uiInit === '1') return;
            reponseUtilisateur.dataset.uiInit = '1';
            
        }

        boutonsReponse.forEach(bouton => {
            bouton.addEventListener('click', function () {
                // Retire la classe 'focus' de tous les boutons
                boutonsReponse.forEach(btn => btn.classList.remove('focus'));
                // Ajoute la classe 'focus' au bouton cliqué
                this.classList.add('focus');
                // Met à jour la valeur du champ caché avec la valeur du bouton
                reponseUtilisateur.value = this.value;
            });
        });
    };


document.addEventListener('DOMContentLoaded', () => {
    input_facile();
});
document.addEventListener('turbo:load', input_facile);
document.addEventListener('turbo:render', input_facile);

