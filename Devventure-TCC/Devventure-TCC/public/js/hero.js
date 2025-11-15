const can = document.getElementById("canvas");
const ctx = can.getContext("2d");

// Apenas se o canvas existir na página
if (can) {
    can.width = window.innerWidth;
    can.height = window.innerHeight;
    // Removi can.style.background = "black"; pois o CSS já define o background do .hero
    // e o canvas será transparente ou com opacidade

    let particles = [];

    function Clear() {
        // Usamos rgba com uma opacidade muito baixa para um efeito de rastro sutil
        ctx.fillStyle = "rgba(255, 255, 255, 0.08)"; // Cor clara com baixa opacidade
        ctx.fillRect(0, 0, can.width, can.height);
    }

    function Particle(x, y, speed, color) {
        this.x = x;
        this.y = y;
        this.speed = speed;
        this.color = color;

        this.update = function () {
            ctx.strokeStyle = this.color;
            ctx.lineWidth = 1;
            ctx.lineCap = "round";

            ctx.beginPath();
            ctx.moveTo(this.x, this.y);

            this.x += this.speed.x;
            this.y += this.speed.y;

            ctx.lineTo(this.x, this.y);
            ctx.stroke();

            const angle = Math.atan2(this.speed.y, this.speed.x);
            const magnitude = Math.sqrt(this.speed.x * this.speed.x + this.speed.y * this.speed.y);
            
            const options = [angle + Math.PI / 4, angle - Math.PI / 4];
            const choice = Math.floor(Math.random() * options.length);

            if (Math.random() < 0.05) {
                this.speed.x = Math.cos(options[choice]) * magnitude;
                this.speed.y = Math.sin(options[choice]) * magnitude;
            }
        };
    }

    const speed = 2.5;
    const period = 4500;

    function pulse() {
        setTimeout(pulse, period);

        const hue = Math.random() * (220 - 180) + 180; // Faixa de azul/ciano (180 a 220)
        const saturation = 100; // Sempre saturado
        const lightness = 60; // Brilho constante para ser visível

        const origins = [
            { x: can.width / 2, y: can.height / 2 },
            { x: 0, y: 0 },
            { x: can.width, y: 0 },
            { x: 0, y: can.height },
            { x: can.width, y: can.height }
        ];

        for (let origin of origins) {
            for (let i = 0; i < 56; i++) {
                const angle = (i / 8) * 2 * Math.PI;
                particles.push(
                    new Particle(
                        origin.x,
                        origin.y,
                        {
                            x: Math.cos(angle) * speed,
                            y: Math.sin(angle) * speed,
                        },
                        `hsl(${hue}, ${saturation}%, ${lightness}%)` // Cor verde/teal
                    )
                );
            }
        }
    }

    function animate() {
        requestAnimationFrame(animate);
        Clear();

        for (let i = 0; i < particles.length; i++) {
            particles[i].update();

            if (
                particles[i].x < 0 ||
                particles[i].x > can.width ||
                particles[i].y < 0 ||
                particles[i].y > can.height
            ) {
                particles.splice(i, 1);
                i--;
            }
        }
    }

    pulse();
    animate();

    // Adiciona listener para redimensionar o canvas junto com a janela
    window.addEventListener('resize', () => {
        can.width = window.innerWidth;
        can.height = window.innerHeight;
    });
}


// A parte do IntersectionObserver está correta e não precisa de alterações.
const reveals = document.querySelectorAll('.reveal');
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');
        }
    });
}, { threshold: 0.1 });

reveals.forEach(el => observer.observe(el));

document.addEventListener("DOMContentLoaded", () => {
    const etapas = document.querySelectorAll(".etapa");

    if (etapas.length > 0) {
        const observerOptions = {
            root: null, // Observa em relação ao viewport
            rootMargin: "0px",
            threshold: 0.6 // Ativa quando 60% do elemento está visível
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Adiciona a classe quando entra na tela
                    entry.target.classList.add("is-active");
                } else {
                    // Remove a classe quando sai da tela
                    entry.target.classList.remove("is-active");
                }
            });
        }, observerOptions);

        // Inicia a observação para cada etapa
        etapas.forEach(etapa => {
            observer.observe(etapa);
        });
    }
});

// CÓDIGO DE DIAGNÓSTICO - Cole isso no final do seu hero.js

document.addEventListener("DOMContentLoaded", () => {
    // Só executa este script se estivermos na página inicial
    if (document.body.id === 'welcome-page') {
        
        console.log("Script da Navbar Ativo na Home!"); // Mensagem 1: O script começou?

        const navbar = document.querySelector('.navbar'); // Tenta encontrar a navbar
        const heroSection = document.querySelector('.hero'); // Tenta encontrar a hero section

        console.log("Elemento Navbar encontrado:", navbar); // Mensagem 2: Encontrou a navbar? (Se aparecer 'null', o nome da classe está errado)
        console.log("Elemento Hero encontrado:", heroSection); // Mensagem 3: Encontrou a hero?

        if (!navbar || !heroSection) {
            console.error("ERRO: Não foi possível encontrar a navbar ou a hero section. Verifique os nomes das classes no HTML.");
            return;
        }

        const heroHeight = heroSection.offsetHeight;

        window.addEventListener('scroll', () => {
            if (window.scrollY > heroHeight - 80) {
                navbar.classList.add('navbar-scrolled');
                console.log("Adicionando classe 'navbar-scrolled'"); // Mensagem 4: Deve aparecer quando você rola para baixo
            } else {
                navbar.classList.remove('navbar-scrolled');
                console.log("Removendo classe 'navbar-scrolled'"); // Mensagem 5: Deve aparecer quando você rola para cima
            }
        });
    }
});