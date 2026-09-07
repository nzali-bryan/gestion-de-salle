document.addEventListener('DOMContentLoaded', () => {

    // 1. ANIMATION DE REBOND AU CLIC SUR TOUS LES BOUTONS (Effet Tactile Mobile)
    const boutons = document.querySelectorAll('button, .mg-bottom-nav a, .mg-see-all');
    
    boutons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            // Effet d'enfoncement au toucher
            btn.style.transform = 'scale(0.92)';
            btn.style.transition = 'transform 0.1s ease';
            
            setTimeout(() => {
                btn.style.transform = 'scale(1)';
            }, 120);
        });
    });

    // 2. PETITE INTERACTION SIMPLE : DÉMARRER UNE SESSION (Exemple visuel)
    const btnDemarrer = document.querySelector('.mg-start-btn');
    if (btnDemarrer) {
        btnDemarrer.addEventListener('click', () => {
            btnDemarrer.innerHTML = '<i class="bi bi-hourglass-split"></i> Chargement...';
            btnDemarrer.style.background = '#2ddc84';
            
            setTimeout(() => {
                alert("Session initialisée visuellement ! Prochaine étape : la liaison avec la base de données.");
                btnDemarrer.innerHTML = '<i class="bi bi-play-fill"></i> Démarrer une session';
                btnDemarrer.style.background = '#1769ff';
            }, 600);
        });
    });

    // 3. CLIC SUR LES NOTIFICATIONS
    const notifBtn = document.querySelector('.mg-icon-btn');
    if (notifBtn) {
        notifBtn.addEventListener('click', () => {
            const dot = notifBtn.querySelector('.notification-dot');
            if (dot) {
                dot.style.transform = 'scale(0)';
                dot.style.transition = 'transform 0.3s ease';
                setTimeout(() => dot.remove(), 300);
            }
        });
    }
});
