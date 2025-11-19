//CREER LES CARTES LISTE DE VOCABULAIRE
function buildCards(donnees, divResultat) {
    divResultat.textContent = "";

    const userIdInput = document.querySelector('#userId');
    const userId = userIdInput ? String(userIdInput.value) : null;
    // Utilise un chemin absolu basé sur l'origine du site pour éviter les chemins relatifs erronés
    const imgBase = new URL('/img/', window.location.origin).toString();
    const asset = (name) => imgBase + name;

    donnees.forEach(function (donnee) {
        const card = document.createElement('div');
        card.className = 'card shadow-box';

        // En-tête langues + créateur si public
        const entete = document.createElement('div');
        entete.className = 'en-tete-card';

        const langueG = document.createElement('span');
        langueG.className = 'langue';
        langueG.textContent = (donnee.langues?.[0]?.nom ?? '').toUpperCase();
        entete.appendChild(langueG);

        if (donnee.publicStatut) {
            const createur = document.createElement('div');
            createur.className = 'createur-liste';
            const pubImg = document.createElement('img');
            pubImg.src = asset('public-statut.png');
            const pNom = document.createElement('p');
            pNom.textContent = donnee.createur?.nom ?? '';
            createur.appendChild(pubImg);
            createur.appendChild(pNom);
            entete.appendChild(createur);
        }

        const langueD = document.createElement('span');
        langueD.className = 'langue';
        langueD.textContent = (donnee.langues?.[1]?.nom ?? '').toUpperCase();
        entete.appendChild(langueD);

        card.appendChild(entete);

        // Titre + note
        const titreWrap = document.createElement('div');
        titreWrap.className = 'titre-liste-contener';

        const pTitre = document.createElement('p');
        pTitre.className = 'titre-liste shadow';
        pTitre.textContent = donnee.titre ?? '';
        titreWrap.appendChild(pTitre);

        const noteDiv = document.createElement('div');
        noteDiv.className = 'note-liste';

        const note = Number(donnee.noteTotale ?? 0);
        if (Number.isFinite(note)) {
            const full = Math.min(Math.max(note, 0), 5);
            const empty = 5 - full;
            for (let i = 0; i < full; i++) {
                const img = document.createElement('img');
                img.src = asset('star-full-icon.png');
                noteDiv.appendChild(img);
            }
            for (let i = 0; i < empty; i++) {
                const img = document.createElement('img');
                img.src = asset('star-empty-icon.png');
                noteDiv.appendChild(img);
            }
        } else {
            const spanNo = document.createElement('span');
            spanNo.textContent = 'Pas encore de note';
            noteDiv.appendChild(spanNo);
        }

        titreWrap.appendChild(noteDiv);
        card.appendChild(titreWrap);

        // Favori (icône)
        const favUsers = (donnee.utilisateursQuiFav || []).map(u => String(u.id));
        const estFavori = userId ? favUsers.includes(userId) : false;
        const favForm = document.createElement('form');
        favForm.method = 'post';
        favForm.action = `/liste/${donnee.id}/fav-toggle`;
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '';
        favForm.appendChild(csrf);
        const favImg = document.createElement('img');
        favImg.className = 'heart-icon';
        favImg.title = estFavori ? 'Cliquez pour enlever des favoris' : 'Cliquez pour mettre en favori';
        favImg.src = estFavori ? asset('heart-icon.png') : asset('heart-empty-icon.png');
        favForm.appendChild(favImg);
        card.appendChild(favForm);

        // Meilleur score
        const infoJeuUser = (donnee.infosJeux || []).find(
            (info) => String(info.utilisateur?.id) === String(userId)
        );
        let bestScoreMostDifficult = 'Pas encore de meilleur score';
        if (infoJeuUser?.bestScores?.[2] != null) {
            bestScoreMostDifficult = infoJeuUser.bestScores[2];
        }
        const pScore = document.createElement('p');
        pScore.className = 'meilleur-score';
        if (bestScoreMostDifficult === 'Pas encore de meilleur score') {
            pScore.textContent = 'Pas encore de score';
        } else {
            pScore.textContent = `Best : ${bestScoreMostDifficult} %`;
        }
        card.appendChild(pScore);

        // Footer: play + edit/delete + confirm_delete (hidden)
        const footer = document.createElement('div');
        footer.className = 'footer-card';

        const playA = document.createElement('a');
        playA.href = `/quizzOptions/${donnee.id}`;
        const playIcon = document.createElement('div');
        playIcon.className = 'play-icon';
        const playImg = document.createElement('img');
        playImg.className = 'play-img';
        playImg.src = asset('play-icon.png');
        playIcon.appendChild(playImg);
        playA.appendChild(playIcon);
        footer.appendChild(playA);

        const editDel = document.createElement('div');
        editDel.className = 'edit-and-delete';

        const editA = document.createElement('a');
        editA.href = `/modifier/liste/${donnee.id}`;
        const editIcon = document.createElement('div');
        editIcon.className = 'edit-delete-icon';
        const editImg = document.createElement('img');
        editImg.src = asset('edit-pen-icon.png');
        editIcon.appendChild(editImg);
        editA.appendChild(editIcon);
        editDel.appendChild(editA);

        const delA = document.createElement('a');
        delA.href = `/supprimer/liste/${donnee.id}`;
        const delIcon = document.createElement('div');
        delIcon.className = 'edit-delete-icon';
        const delImg = document.createElement('img');
        delImg.src = asset('recycle-bin-icon.png');
        delIcon.appendChild(delImg);
        delA.appendChild(delIcon);
        editDel.appendChild(delA);

        footer.appendChild(editDel);

        const confirmDelete = document.createElement('div');
        confirmDelete.className = 'confirm_delete hidden';
        const pDel = document.createElement('p');
        pDel.textContent = `Êtes-vous sûr-e de vouloir supprimer la liste : ${donnee.titre} ?`;
        confirmDelete.appendChild(pDel);
        const cancel = document.createElement('button');
        cancel.className = 'cancel_delete';
        cancel.textContent = 'Annuler';
        confirmDelete.appendChild(cancel);
        const linkDelete = document.createElement('a');
        linkDelete.href = `/supprimer/liste/${donnee.id}`;
        const btnDel = document.createElement('button');
        btnDel.className = 'supprimer-button';
        btnDel.textContent = 'Supprimer';
        linkDelete.appendChild(btnDel);
        confirmDelete.appendChild(linkDelete);
        footer.appendChild(confirmDelete);

        card.appendChild(footer);

        divResultat.appendChild(card);
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