//CREER LES CARTES LISTE DE VOCABULAIRE
function buildCards(donnees, divResultat) {
    divResultat.textContent = "";

    donnees.forEach(function (donnee) {
        const divCard = document.createElement("div");
        divCard.classList.add("card");

        // Titre de la liste
        const pTitre = document.createElement("p");
        pTitre.classList.add("titre-liste");
        pTitre.textContent = "Titre de la liste : " + donnee.titre;
        divCard.appendChild(pTitre);

        // Langues
        const spanLangues = document.createElement("span");
        spanLangues.classList.add("langues-liste");
        spanLangues.textContent = "Langues : ";
        divCard.appendChild(spanLangues);

        (donnee.langues || []).forEach(function (langue, i) {
            const spanLangue = document.createElement("span");
            spanLangue.classList.add("langue" + i);
            spanLangue.textContent = langue.nom;
            divCard.appendChild(spanLangue);
        });

        // Créateur
        const pCreateur = document.createElement("p");
        pCreateur.classList.add("createur-liste");
        pCreateur.textContent = "Créateur : " + (donnee.createur?.nom ?? "");
        divCard.appendChild(pCreateur);

        // Statut
        const pStatut = document.createElement("p");
        pStatut.classList.add("statut-liste");
        pStatut.textContent = "Statut : " + (donnee.publicStatut ? "Public" : "Privé");
        divCard.appendChild(pStatut);

        // Note Totale
        const spanNote = document.createElement("span");
        spanNote.classList.add("note-liste");
        spanNote.textContent = "Note : ";
        divCard.appendChild(spanNote);

        if (donnee.noteTotale) {
            divCard.appendChild(document.createTextNode(donnee.noteTotale));
        } else {
            const divPasDeNote = document.createElement("div");
            divPasDeNote.classList.add("pas-de-note");
            const spanPasDeNote = document.createElement("span");
            spanPasDeNote.textContent = "Pas encore de note";
            divPasDeNote.appendChild(spanPasDeNote);
            divCard.appendChild(divPasDeNote);
        }

        // Favori
        const userIdInput = document.querySelector('#userId');
        const userId = userIdInput ? String(userIdInput.value) : null;
        const favIds = (donnee.utilisateursQuiFav || []).map(u => String(u.id));
        const estFavori = userId ? favIds.includes(userId) : false;

        const pFavori = document.createElement("p");
        pFavori.classList.add("favori-liste");
        pFavori.textContent = "Favori : " + (estFavori ? "OUI" : "NON");
        divCard.appendChild(pFavori);

        //Bouton pour FAV 
        const favBtn = document.createElement('button');
        favBtn.className = 'fav-toggle';
        favBtn.textContent = estFavori ? 'Retirer des favoris' : 'Ajouter aux favoris';
        favBtn.dataset.id = String(donnee.id);
        divCard.appendChild(favBtn);

        
        // Meilleur score
        const infoJeuUser = (donnee.infosJeux || []).find(
            (info) => String(info.utilisateur?.id) === String(userId)
        );
        let bestScoreMostDifficult = "Pas encore de meilleur score";
        if (infoJeuUser?.bestScores?.[2] != null) {
            bestScoreMostDifficult = infoJeuUser.bestScores[2];
        }

        const pScore = document.createElement("p");
        pScore.classList.add("meilleur-score");
        pScore.textContent = "Meilleur Score : " + bestScoreMostDifficult;
        divCard.appendChild(pScore);

        // Boutons
        const playLink = document.createElement("a");
        playLink.href = `/quizzOptions/${donnee.id}`;
        playLink.innerHTML = `<button class="play-button">Play</button>`;
        divCard.appendChild(playLink);

        const modLink = document.createElement("a");
        modLink.href = `/modifier/liste/${donnee.id}`;
        modLink.innerHTML = `<button class="modifier-button">Modifier</button>`;
        divCard.appendChild(modLink);

        const supprBtnWrap = document.createElement("a");
        supprBtnWrap.innerHTML = `<button class="supprimer-button">Supprimer</button>`;
        divCard.appendChild(supprBtnWrap);

        // Saut de ligne !! [A RETIRER ET FAIRE EN CSS PLUS TARD] !!
        divCard.appendChild(document.createElement("br"));
        divCard.appendChild(document.createElement("br"));

        // Bloc de confirmation de suppression
        const containerDeleteDiv = document.createElement('div');
        containerDeleteDiv.className = 'confirm_delete hidden';

        const pDeleteConfirm = document.createElement('p');
        pDeleteConfirm.textContent = `Êtes-vous sûr-e de vouloir supprimer la liste : ${donnee.titre} ?`;
        containerDeleteDiv.appendChild(pDeleteConfirm);

        const cancelBtn = document.createElement('button');
        cancelBtn.className = 'cancel_delete';
        cancelBtn.type = 'button';
        cancelBtn.textContent = 'Annuler';
        containerDeleteDiv.appendChild(cancelBtn);

        const linkDelete = document.createElement('a');
        linkDelete.href = `/supprimer/liste/${donnee.id}`;

        const deleteConfirmBtn = document.createElement('button');
        deleteConfirmBtn.className = 'supprimer-button';
        deleteConfirmBtn.type = 'button';
        deleteConfirmBtn.textContent = 'Supprimer';

        linkDelete.appendChild(deleteConfirmBtn);
        containerDeleteDiv.appendChild(linkDelete);
        divCard.appendChild(containerDeleteDiv);


        // Ajouter la carte à la div Résultat
        divResultat.appendChild(divCard);
    });
}

function initAccueil() {
    // Évite la double initialisation (vu qu'on lance un eventlistener sur turbo:load et DOMContentLoaded)
    const root = document.querySelector('#recherche-form');
    if (!root) return;
    //On donne un dataset initialized de 1 à root pour dire qu'il a déjà été initialisé
    //Si il est égal à 1, on ne fait rien
    if (root.dataset.initialized === '1') return;
    // Sinon on lui donne la valeur 1 (du coup il est initialisé)
    root.dataset.initialized = '1';

    //Puis on récupère root pour le mettre dans formRecherche
    const formRecherche = root;
    const divResultat = document.querySelector(".cards-contener");
    if (!divResultat) return;

    // Gére les erreurs si axios n'est pas lancé
    if (typeof axios === 'undefined') {
        console.warn('axios manquant: le filtrage ne fonctionnera pas.');
        return;
    }

    formRecherche.addEventListener("submit", function (event) {
        event.preventDefault();
    });

    // Boucle pour appliquer les changement quand on tape dans la barre ET quand on change les options (via Select ou checkbox)
    "keyup change".split(" ").forEach(function (ev) {
        formRecherche.addEventListener(ev, function () {
            const formData = new FormData(formRecherche);

            //Pour récupérer la route depuis le dataset de notre formulaire
            const route = formRecherche.dataset.route;
            if (!route) return;

            //AJAX
            axios.post(route, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            }).then(function (response) {
                //CREER LES CARTES paramètres soit les données récupérées par ajax soit [] si vide
                buildCards(response.data || [], divResultat);
            }).catch((err) => {
                //Gestion des erreurs
                console.error('Erreur requête filtre:', err);
            });
        });
    });
}

// Lancer l’init sur reload et navigation Turbo (c'est le cas depuis login)
document.addEventListener('turbo:load', initAccueil);
document.addEventListener('DOMContentLoaded', initAccueil);