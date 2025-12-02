const can = document.getElementById("canvas");
const ctx = can.getContext("2d");

// Apenas se o canvas existir na página
if (can) {
    can.width = window.innerWidth;
    can.height = window.innerHeight;

    let particles = [];
    
    // Altura da zona do degradê (deve bater com o CSS: 250px)
    const DEGRADE_ZONE_HEIGHT = 250; 

    function Clear() {
        // Limpa o canvas completamente para deixar o degradê do CSS aparecer
        ctx.clearRect(0, 0, can.width, can.height);
    }

    function Particle(x, y, speed, color) {
        this.x = x;
        this.y = y;
        this.speed = speed;
        this.baseColor = color; // Guarda a cor original (Teal/Verde)

        // Armazena a posição anterior para desenhar o rastro
        this.oldX = x;
        this.oldY = y;

        this.update = function () {
            // LÓGICA DE MUDANÇA DE COR
            // Se estiver na parte superior (azul escuro), fica branco.
            // Se estiver na parte inferior (claro), usa a cor original.
            if (this.y < DEGRADE_ZONE_HEIGHT) {
                ctx.strokeStyle = "#2c68ffff"; 
                ctx.shadowBlur = 5; // Adiciona um brilho extra no branco
                ctx.shadowColor = "#ffffff";
            } else {
                ctx.strokeStyle = this.baseColor;
                ctx.shadowBlur = 0; // Remove brilho na parte clara
                ctx.shadowColor = "transparent";
            }

            ctx.lineWidth = 2;
            ctx.lineCap = "round";

            ctx.beginPath();
            ctx.moveTo(this.oldX, this.oldY);
            
            // Atualiza posições
            this.oldX = this.x;
            this.oldY = this.y;
            
            this.x += this.speed.x;
            this.y += this.speed.y;

            ctx.lineTo(this.x, this.y);
            ctx.stroke();

            // Lógica de movimento aleatório
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

        const hue = Math.random() * (220 - 180) + 180; // Faixa de azul/ciano
        const saturation = 100; 
        const lightness = 50; // Um pouco mais escuro para contrastar com o fundo claro

        // --- AJUSTE DE ALTURA ---
        const liftAmount = 150; // Define quantos pixels subir o centro da explosão

        const origins = [
            // Subtraímos liftAmount do Y para subir o centro
            { x: can.width / 2, y: (can.height / 2) - liftAmount }, 
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
                        `hsl(${hue}, ${saturation}%, ${lightness}%)` 
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

            // Remove partículas que saem da tela
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

    window.addEventListener('resize', () => {
        can.width = window.innerWidth;
        can.height = window.innerHeight;
    });
}

// --- INTERSECTION OBSERVERS (JORNADA E REVEALS) ---

const reveals = document.querySelectorAll('.reveal');
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');
        }
    });
}, { threshold: 0.1 });

reveals.forEach(el => revealObserver.observe(el));

document.addEventListener("DOMContentLoaded", () => {
    const etapas = document.querySelectorAll(".etapa");

    if (etapas.length > 0) {
        const observerOptions = {
            root: null,
            rootMargin: "0px",
            threshold: 0.6
        };

        const etapaObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("is-active");
                } else {
                    entry.target.classList.remove("is-active");
                }
            });
        }, observerOptions);

        etapas.forEach(etapa => {
            etapaObserver.observe(etapa);
        });
    }
});

// --- LÓGICA DE COR DA NAVBAR (Baseado no Scroll) ---
document.addEventListener("DOMContentLoaded", () => {
    if (document.body.id === 'welcome-page') {
        
        const navbar = document.querySelector('.navbar');
        const heroSection = document.querySelector('.hero');

        if (navbar && heroSection) {
            const heroHeight = heroSection.offsetHeight;

            window.addEventListener('scroll', () => {
                // Quando passar do Hero, adiciona classe para mudar a cor da navbar se necessário
                if (window.scrollY > heroHeight - 80) {
                    navbar.classList.add('navbar-scrolled-past-hero');
                } else {
                    navbar.classList.remove('navbar-scrolled-past-hero');
                }
            });
        }
    }
});