
function traduction_add() {
    const traductions = document.getElementById('traductions');
    const addBtn = document.getElementById('btn-add-traduction');

    // Évite double attachement (DOMContentLoaded + turbo:load)
    if (addBtn.dataset.uiInit === '1') return;
    addBtn.dataset.uiInit = '1';


    // Ajouter une ligne
    addBtn.addEventListener('click', function () {
        const index = traductions.dataset.index;
        traductions.dataset.index = parseInt(index) + 1;
        //Génére du HTML avec le form Traduction (=prototype)
        const prototype = traductions.dataset.prototype;

        const div_traduction = document.createElement('div');
        // Remplace __name__ par l'index (name est généré par Symfony dans le prototype)
        div_traduction.innerHTML = prototype.replace(/__name__/g, index);
        //Prend le premier enfant du div_traduction pour y ajouter le formulaire traduction créé
        const item = div_traduction.firstElementChild || div_traduction;
        item.classList.add("traduction-item");
        traductions.appendChild(item);

        // Ajoutez le bouton de suppression
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn-remove-traduction';
        removeBtn.textContent = 'Supprimer';
        item.appendChild(removeBtn);
        removeBtn.onclick = function () {
            div_traduction.remove();
        };

        //Fonction pour supprimer les trads déjà présentes (voir ci-desous)
        attachRemove(item);

    });

    // Supprimer les traductions déjà présentes
    traductions.querySelectorAll('.traduction-item').forEach(attachRemove);

    function attachRemove(item) {
        const btn = item.querySelector('.btn-remove-traduction');
        if (!btn) return;
        btn.addEventListener('click', function () {
            item.remove();
        });
    }
};



//Turbo:load => Pour quand on vient de login (parce que symfony utilise turbo qui ne charge que le body et pas toute la page)
document.addEventListener('turbo:load', function () {
    if (document.getElementById('btn-add-traduction')) {
        traduction_add();
    }
});
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('btn-add-traduction')) {
        traduction_add();
    }
});
