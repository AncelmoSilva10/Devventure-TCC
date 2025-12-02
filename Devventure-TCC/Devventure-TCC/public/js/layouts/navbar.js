document.addEventListener('DOMContentLoaded', () => {

    const navbar = document.querySelector('.navbar'); 
    const body = document.body;

    const dropdownButtons = document.querySelectorAll('.profile-button');
    dropdownButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.stopPropagation();
            const dropdown = button.nextElementSibling;
            document.querySelectorAll('.profile-dropdown-content.active').forEach(d => {
                if (d !== dropdown) d.classList.remove('active');
            });
            dropdown.classList.toggle('active');
        });
    });

    window.addEventListener('click', () => {
        document.querySelectorAll('.profile-dropdown-content.active').forEach(dropdown => {
            dropdown.classList.remove('active');
        });
    });

    const modalTriggers = document.querySelectorAll('.modal-trigger');
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', () => {
            const modalId = trigger.dataset.modalTarget;
            const modal = document.querySelector(modalId);
            if (modal) {
                modal.style.display = 'flex';
            }
        });
    });

    const modalCloseButtons = document.querySelectorAll('.modal-close');
    modalCloseButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.dataset.modalClose;
            const modal = document.querySelector(modalId);
            if (modal) {
                modal.style.display = 'none';
            }
        });
    });

    const modalOverlays = document.querySelectorAll('.modal-overlay');
    modalOverlays.forEach(modal => {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });

    const menuToggle = document.getElementById('menu-toggle');
    const navbarLinks = document.getElementById('navbar-links');

    if (menuToggle && navbarLinks) {
        menuToggle.addEventListener('click', () => {
            menuToggle.classList.toggle('active');
            navbarLinks.classList.toggle('active');
        });
    }

    if (navbar) {
        
        // 1. ANIMAÇÃO DE RECLUSÃO GERAL (APLICA ESTADO EXPANDIDO INICIAL)
        navbar.classList.add('expanded', 'initial-shape');
        
        // 2. GARANTE A COR DE FUNDO GERAL
        navbar.classList.add('scrolled');

        requestAnimationFrame(() => {
             requestAnimationFrame(() => {
                navbar.classList.remove('initial-shape');
                navbar.classList.remove('expanded');
             });
        });
        
        // 3. LÓGICA DE SCROLL (APLICADA GERALMENTE)
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }
});