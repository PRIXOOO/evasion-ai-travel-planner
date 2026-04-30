
document.querySelectorAll('.search-tabs button').forEach(btn => {
    btn.addEventListener('click', function() {
// 1. On retire la classe 'active' de l'ancien bouton et on la met sur le bouton cliqué
        document.querySelector('.search-tabs button.active').classList.remove('active');
        this.classList.add('active');

        // 2. On récupère le "data-target" du bouton cliqué (vol, hotel, ou decouvrir)
        const targetId = this.getAttribute('data-target');
        
        // 3. On cache tous les contenus
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active-content');
        });
        
        // 4. On affiche uniquement le contenu qui correspond au bouton cliqué
        document.getElementById(targetId).classList.add('active-content');
    });
});

//Un bouton vite fait temporaire 
// --- REDIRECTION DU BOUTON "TESTER NOTRE IA" VERS L'ONGLET DÉCOUVERTE ---
const btnTesterIA = document.querySelector('.btn-wide-blue');

if (btnTesterIA) {
    btnTesterIA.addEventListener('click', function(e) {
        e.preventDefault();

        // 1. On fait défiler la page en douceur vers le haut (la section hero)
        const heroSection = document.querySelector('.hero');
        if (heroSection) {
            heroSection.scrollIntoView({ behavior: 'smooth' });
        } else {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // 2. On cherche le bouton de l'onglet "Faites-moi découvrir" et on simule un clic dessus
        // Assure-toi que tu as bien mis data-target="decouvrir" sur cet onglet dans le HTML (comme on l'a fait juste avant)
        const ongletDecouvrir = document.querySelector('.search-tabs button[data-target="decouvrir"]');
        
        if (ongletDecouvrir) {
            // Un léger délai pour laisser le temps au scroll de commencer avant de changer l'onglet
            setTimeout(() => {
                ongletDecouvrir.click();
                
                // Optionnel : on met automatiquement le curseur dans la barre de recherche textuelle
                const inputRecherche = document.querySelector('.discover-input');
                if (inputRecherche) {
                    inputRecherche.focus();
                }
            }, 300);
        }
    });
}

//effet 3D robot
const robot = document.querySelector('.ai-illustration');

if (robot) {
    robot.addEventListener('mousemove', function(e) {
        const rect = robot.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;
        
        const mouseX = e.clientX - centerX;
        const mouseY = e.clientY - centerY;
        
        const rotateX = -(mouseY / (rect.height / 2)) * 15; 
        const rotateY = (mouseX / (rect.width / 2)) * 15;
        
        robot.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
    });

    robot.addEventListener('mouseleave', function() {
        robot.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg)`;
    });
}


// On nettoie la mémoire au cas où
if (window.moteurCarousel) {
    clearInterval(window.moteurCarousel);
}

const testimonials = document.querySelectorAll('.testi-card');
const dots = document.querySelectorAll('.dots span');

if (testimonials.length === 3 && dots.length === 3) {
    
    // Séquence d'affichage parfaite : Milieu -> Droite -> Milieu -> Gauche
    const sequence = [1, 2, 1, 0]; 
    let etapeActuelle = 0; 

    function animerCarousel() {
        // 1. On éteint la carte et le point actuels
        let posActuelle = sequence[etapeActuelle];
        testimonials[posActuelle].classList.remove('main');
        testimonials[posActuelle].classList.add('fade');
        dots[posActuelle].classList.remove('active');

        // 2. On passe à l'étape suivante
        etapeActuelle++;
        if (etapeActuelle >= sequence.length) {
            etapeActuelle = 0; // On recommence la boucle
        }

        // 3. On allume la nouvelle carte et le nouveau point
        let posNouvelle = sequence[etapeActuelle];
        testimonials[posNouvelle].classList.remove('fade');
        testimonials[posNouvelle].classList.add('main');
        dots[posNouvelle].classList.add('active');
    }

    // On lance l'animation toutes les 3 secondes
    window.moteurCarousel = setInterval(animerCarousel, 3000);
}

// --- AUTO-COMPLÉTION DES VILLES VIA API ---

const cityInputs = document.querySelectorAll('.city-autocomplete');
const dataList = document.getElementById('cities-list');
let timeoutId; // Pour éviter de spammer l'API à chaque lettre tapée

cityInputs.forEach(input => {
    input.addEventListener('input', function(e) {
        const query = e.target.value;
        
        // On annule la recherche précédente si on tape très vite
        clearTimeout(timeoutId);

        // On attend que l'utilisateur ait tapé au moins 3 lettres
        if (query.length < 3) {
            return; 
        }

        // On attend 300 millisecondes après la dernière frappe avant d'appeler l'API
        timeoutId = setTimeout(() => {
            
            // Appel à l'API gratuite Open-Meteo
            fetch(`https://geocoding-api.open-meteo.com/v1/search?name=${query}&count=5&language=fr&format=json`)
                .then(response => response.json())
                .then(data => {
                    // On vide l'ancienne liste
                    dataList.innerHTML = ''; 
                    
                    // Si on a trouvé des villes, on les ajoute à la datalist
                    if (data.results) {
                        data.results.forEach(city => {
                            const option = document.createElement('option');
                            // On affiche la ville et le pays (ex: "Paris, France")
                            option.value = `${city.name}, ${city.country}`;
                            dataList.appendChild(option);
                        });
                    }
                })
                .catch(err => console.error("Erreur de récupération des villes:", err));
                
        }, 300);
    });
});

// --- MENU HAMBURGER (MOBILE) ---
const mobileMenu = document.getElementById('mobile-menu');
const navLinks = document.querySelector('.nav-links');

if (mobileMenu && navLinks) {
    mobileMenu.addEventListener('click', function() {
        navLinks.classList.toggle('active');
        mobileMenu.classList.toggle('fixed-close'); // Fixe la croix en haut à droite
        document.body.classList.toggle('no-scroll'); // Empêche le fond de scroller
        
        const icon = mobileMenu.querySelector('i');
        if (navLinks.classList.contains('active')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        } else {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
            
            // On referme le sous-menu profil s'il était ouvert en quittant
            const userMenu = document.querySelector('.user-menu-container');
            if(userMenu) userMenu.classList.remove('open');
        }
    });
}

// --- SOUS-MENU PROFIL (MOBILE) ---
const userMenuBtn = document.querySelector('.user-menu-container');
if (userMenuBtn) {
    userMenuBtn.addEventListener('click', function(e) {
        // Ajoute ou enlève la classe 'open' pour afficher le menu déroulant
        this.classList.toggle('open');
    });
}
// --- INTERCEPTION DE LA RECHERCHE IA (SIMPLIFIÉE) ---
document.addEventListener('DOMContentLoaded', function() {
    // On cible le formulaire
    const searchForm = document.getElementById('decouvrir');
    
    if (searchForm) {
        searchForm.addEventListener('submit', function(event) {
            
            const inputRecherche = searchForm.querySelector('input[name="prompt_utilisateur"]');
            
            // Si c'est vide, on bloque l'envoi
            if (!inputRecherche || inputRecherche.value.trim() === "") {
                event.preventDefault(); 
                return; 
            }

            // --- ANIMATION DU BOUTON ---
            // On ne bloque PAS l'envoi du formulaire (pas de event.preventDefault() si tout est OK).
            // On laisse le navigateur faire un vrai POST vers recherche_ia.php !
            
            const btnSubmit = searchForm.querySelector('button[type="submit"]');
            
            // On change le visuel du bouton pour faire patienter l'utilisateur
            btnSubmit.innerHTML = 'Création en cours... ⏳';
            btnSubmit.style.opacity = '0.7';
            
            // Et c'est tout ! Le navigateur va charger la page recherche_ia.php tout seul
            // avec les données POST, et ton beau design PHP va s'afficher.
        });
    }
});
