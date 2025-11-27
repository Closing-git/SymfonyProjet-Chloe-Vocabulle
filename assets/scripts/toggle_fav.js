// Script pour toggle favori(relié à FavoriController) 
function toggleFav() {


    const heartContainer = document.querySelector('.heart-contener');


    if (!heartContainer) {
        return
    }
    else {
        // Évite double attachement (DOMContentLoaded + turbo:load)
        if (heartContainer.dataset.uiInit === '1') return;
        heartContainer.dataset.uiInit = '1';

//Récupère l'id de la liste via le html
        let listeId = heartContainer.dataset.id;
        heartContainer.addEventListener('click', function () {
            try {
                console.log("Tentative d'ajout aux favoris pour la liste ID:", listeId);

                const response = fetch(`/favori/${listeId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }

                const result = response.json();
                console.log("Réponse du serveur:", result);
            }
            catch (error) {
                console.error("Erreur lors de la mise à jour des favoris:", error);
            };
        })
    }
}

document.addEventListener('DOMContentLoaded', () => {
    toggleFav();
});
document.addEventListener('turbo:load', toggleFav);
document.addEventListener('turbo:render', toggleFav);

