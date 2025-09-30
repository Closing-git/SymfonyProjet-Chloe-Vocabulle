function displayUserUI() {
    const profilLogo = document.querySelector('#profil-circle');
    const profilPannel = document.querySelector('.profil-pannel')

    // Évite double attachement (DOMContentLoaded + turbo:load)
    if (profilPannel.dataset.uiInit === '1') return;
    profilPannel.dataset.uiInit = '1';


    profilLogo.addEventListener('click', (e) => {
        if (profilPannel.classList.contains('show')) {
            profilPannel.classList.add("hidden");
            profilPannel.classList.remove("show");
        }
        else {
            profilPannel.classList.add("show");
            profilPannel.classList.remove("hidden");
        }
    })

    //Si on clique à côté
    document.addEventListener('click', (e) => {
        //Récupère les zones où "on peut cliquer"
        const clickedInside = profilPannel.contains(e.target) || profilLogo.contains(e.target);
        if (!clickedInside && profilPannel.classList.contains('show')) {
            profilPannel.classList.add("hidden");
            profilPannel.classList.remove("show");
        }})

    // Fermer sur Échap
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && profilPannel.classList.contains('show')) {
            profilPannel.classList.add("hidden");
            profilPannel.classList.remove("show");
        }
    });
}

document.addEventListener('turbo:load', displayUserUI);
document.addEventListener('DOMContentLoaded', displayUserUI);