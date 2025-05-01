document.addEventListener("DOMContentLoaded", function () {
    let wishCounter = document.querySelector(".header-ctn .qty"); // Sélectionne l'élément du compteur

    // Récupérer la valeur actuelle depuis localStorage (ou initialiser à 0)
    let count = localStorage.getItem("wishlistCount") ? parseInt(localStorage.getItem("wishlistCount")) : 0;
    wishCounter.textContent = count; // Afficher la valeur actuelle

    // Ajouter un événement sur le bouton "Add to Wishlist"
    document.querySelectorAll(".add-to-wishlist").forEach(button => {
        button.addEventListener("click", function () {
            count++; // Incrémenter le compteur
            localStorage.setItem("wishlistCount", count); // Enregistrer la nouvelle valeur
            wishCounter.textContent = count; // Mettre à jour l'affichage
            
            // Recharger la page après une courte pause (optionnel)
            setTimeout(() => {
                location.reload();
            }, 500);
        });
    });
});

	function initWishCounter() {
    let wishCounter = document.querySelector(".qty"); // Sélectionne le compteur

    // Récupérer la valeur du compteur depuis localStorage (ou initialiser à 0)
    let count = localStorage.getItem("wishlistCount") ? parseInt(localStorage.getItem("wishlistCount")) : 0;
    
    // Mettre à jour l'affichage
    wishCounter.textContent = count;
}

function addToWishlist() {
    let count = localStorage.getItem("wishlistCount") ? parseInt(localStorage.getItem("wishlistCount")) : 0;
    count++; // Incrémenter

    // Sauvegarder la nouvelle valeur
    localStorage.setItem("wishlistCount", count);

    // Recharger la page pour voir la mise à jour
    location.reload();
}

// Exécuter initWishCounter après le chargement de la page
document.addEventListener("DOMContentLoaded", initWishCounter);


