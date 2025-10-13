
function initSwitch() {
    const switch_button = document.getElementById('switch_button');
    if (!switch_button) return;

    const langue1 = document.getElementById('langue1');
    const langue2 = document.getElementById('langue2');
    const reponse_en = document.getElementById('reponse_en');
    const langue_cible = document.getElementById('langue_cible');

    //Vérifier que tout est initialisé, sinon attendre quelques secondes pour relancer la fonction
    if (!langue1 || !langue2 || !reponse_en || !langue_cible) {
        console.log('Éléments non trouvés, réessaie dans 100ms');
        setTimeout(initSwitch, 100); // Réessayer après un court délai
        return;
    }

    function switch_function() {
        const langue_cible_initiale = window.langue2;

        const temp = langue1.textContent;
        langue1.textContent = langue2.textContent;
        langue2.textContent = temp;


        if (reponse_en.textContent == "Tu devras entrer tes réponses en {{liste.getLangues()[1].nom}}.") {
            reponse_en.textContent = "Tu devras entrer tes réponses en " + langue1.textContent + ".";
        }
        else {
            reponse_en.textContent = "Tu devras entrer tes réponses en " + langue2.textContent + ".";
        }

        if (langue_cible.value === langue_cible_initiale) {
            langue_cible.value = window.langue1;
        } else {
            langue_cible.value = window.langue2;
        }

    };
    //On crée une copie du bouton et on remplace le bouton original par la copie
    // cloneNode fait une copie profonde = copie complète de l'élément switch_button
    // Mais cela supprime les écouteurs d'événements (donc on remet un bouton, sans écouteur d'évenement)
    switch_button.replaceWith(switch_button.cloneNode(true));
    //Là on ajoute un écouteur d'évenement et grâce à la ligne précédente, on s'assure qu'il n'y a qu'un seul écouteur d'événement
    document.getElementById('switch_button').addEventListener('click', switch_function);

}

document.addEventListener('DOMContentLoaded', () => {
    initSwitch();
});
document.addEventListener('turbo:load', initSwitch);
document.addEventListener('turbo:render', initSwitch);

