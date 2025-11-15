document.addEventListener('DOMContentLoaded', function() {
    
    // --- ELEMENTOS DO DOM (Carrossel - do seu código) ---
    const containerDepoimentos = document.getElementById('containerDepoimentos');
    const indicadores = document.getElementById('indicadores');
    const btnAnterior = document.getElementById('btnAnterior');
    const btnProximo = document.getElementById('btnProximo');
    
    // --- ELEMENTOS DO DOM (Formulário - novos) ---
    const form = document.getElementById('formDepoimento');
    const textarea = document.getElementById('textoDepoimento');
    const charCounter = document.querySelector('.contador-caracteres');

    // --- ESTADO DO CARROSSEL (do seu código) ---
    let currentIndex = 0;
    let isAnimating = false;

    // --- 1. CONTADOR DE CARACTERES (novo) ---
    if(textarea && charCounter) {
        textarea.addEventListener('input', () => {
            const count = textarea.value.length;
            charCounter.textContent = `${count}/300`;
        });
    }

    // --- 2. FUNÇÕES DO CARROSSEL (Seu código, com 1 modificação) ---

    // (Seu código original - QUASE IDÊNTICO)
    function inicializarCarrossel() {
        const cards = containerDepoimentos.querySelectorAll('.card-wrapper');
        
        // Limpa classes de estado (para garantir que só 1 esteja ativo)
        cards.forEach(card => card.classList.remove('active', 'exiting', 'entering'));

        // Se não há cards, mostra mensagem
        if (cards.length === 0) {
            containerDepoimentos.innerHTML = '<div class="sem-depoimentos">Nenhum depoimento cadastrado.</div>';
            if(indicadores) indicadores.innerHTML = '';
            if(btnAnterior) btnAnterior.disabled = true;
            if(btnProximo) btnProximo.disabled = true;
            return;
        }
        
        // Habilita/desabilita botões
        if(btnAnterior) btnAnterior.disabled = cards.length <= 1;
        if(btnProximo) btnProximo.disabled = cards.length <= 1;
        
        // Mostra o primeiro card (o novo card recém-adicionado)
        currentIndex = 0;
        if (cards.length > 0) {
            cards[0].classList.add('active');
        }
        
        // *** NÃO PRECISAMOS MAIS DO `cards.forEach` PARA ADICIONAR EVENTOS ***
        // O event handler de delegação (abaixo) cuida disso.
    }

    // *** MODIFICAÇÃO: Event Delegation (Substitui seu `cards.forEach`) ***
    // Isso ouve cliques no container e descobre qual card foi clicado.
    // Funciona para cards que já existem E para os que serão adicionados!
    if(containerDepoimentos) {
        containerDepoimentos.addEventListener('click', (e) => {
            // Encontra o .card-wrapper mais próximo que foi clicado
            const clickedCard = e.target.closest('.card-wrapper');
            if (!clickedCard || isAnimating) return; // Se não clicou num card ou se está animando

            // Pega a lista de todos os cards ATUAIS
            const cards = Array.from(containerDepoimentos.querySelectorAll('.card-wrapper'));
            const index = cards.indexOf(clickedCard); // Descobre o índice do card clicado
            
            if (index > -1) {
                const nextIndex = (index + 1) % cards.length;
                changeTestimonial(nextIndex);
            }
        });
    }
    
    // (Seu código original - SEM MUDANÇAS)
    function changeTestimonial(newIndex) {
        const cards = containerDepoimentos.querySelectorAll('.card-wrapper');
        
        if (isAnimating || cards.length <= 1 || newIndex === currentIndex) return;
        
        isAnimating = true;
        
        const currentCard = cards[currentIndex];
        currentCard.classList.remove('active');
        currentCard.classList.add('exiting');
        
        currentIndex = newIndex;
        
        const newCard = cards[currentIndex];
        newCard.classList.add('entering');
        
        setTimeout(() => {
            currentCard.classList.remove('exiting');
            newCard.classList.remove('entering');
            newCard.classList.add('active');
            isAnimating = false;
        }, 500); // 500ms (ajuste se sua animação for mais longa)
    }
    
    // (Seu código original - SEM MUDANÇAS)
    setInterval(() => {
        const cards = containerDepoimentos.querySelectorAll('.card-wrapper');
        if (cards.length > 1 && !isAnimating) {
            const newIndex = (currentIndex + 1) % cards.length;
            changeTestimonial(newIndex);
        }
    }, 8000); // 8 segundos
    
    // --- 3. ENVIO DO FORMULÁRIO (novo) ---
   // --- 3. ENVIO DO FORMULÁRIO (novo) ---
if (form) {
    form.addEventListener('submit', function (e) {
        e.preventDefault(); // Impede o recarregamento da página

        // Pega a URL do atributo data-url (da correção anterior)
        const postUrl = form.dataset.url;

        const autorInput = document.getElementById('autorDepoimento');
        const textoInput = document.getElementById('textoDepoimento');
        const csrfToken = document.querySelector('input[name="_token"]').value;

        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.textContent = 'Enviando...';

        fetch(postUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                autor: autorInput.value,
                texto: textoInput.value
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // 1. Adiciona o novo depoimento ao DOM
                adicionarDepoimentoAoDOM(data.depoimento);

                // 2. Limpa o formulário e reseta o contador
                form.reset();
                if (charCounter) charCounter.textContent = '0/300';

                // 3. RE-INICIALIZA O CARROSSEL
                inicializarCarrossel();

                // **** ALERTA DE SUCESSO COM SWEETALERT ****
                Swal.fire({
                    title: 'Enviado!',
                    text: 'Seu depoimento foi enviado com sucesso.',
                    icon: 'success',
                    timer: 2000, // Fecha sozinho depois de 2 segundos
                    showConfirmButton: false
                });

            }
        })
        .catch(error => {
            console.error('Erro:', error);
            if (error.errors) {
                // Erros de validação (ex: campos vazios)
                let errorMsg = 'Por favor, verifique os campos:<br><br>';
                for (const field in error.errors) {
                    errorMsg += `- ${error.errors[field][0]}<br>`; // Usa <br> para SweetAlert
                }
                
                // **** ALERTA DE VALIDAÇÃO COM SWEETALERT ****
                Swal.fire({
                    title: 'Campos Inválidos',
                    html: errorMsg, // Usa 'html' para renderizar o <br>
                    icon: 'error',
                    confirmButtonText: 'Entendi'
                });

            } else {
                // Erro genérico (404, 500, etc.)
                
                // **** ALERTA DE ERRO GENÉRICO COM SWEETALERT ****
                Swal.fire({
                    title: 'Oops... Algo deu errado.',
                    text: 'Ocorreu um erro ao enviar seu depoimento. Tente novamente.',
                    icon: 'error',
                    confirmButtonText: 'Fechar'
                });
            }
        })
        .finally(() => {
            // Reabilita o botão
            submitButton.disabled = false;
            submitButton.textContent = 'Enviar Depoimento';
        });
    });
}

    // --- 4. FUNÇÃO AUXILIAR PARA ADICIONAR CARD (nova) ---
    function adicionarDepoimentoAoDOM(depoimento) {
        // Escapa HTML simples para segurança
        const textoEscapado = depoimento.texto.replace(/</g, "&lt;").replace(/>/g, "&gt;");
        const autorEscapado = depoimento.autor.replace(/</g, "&lt;").replace(/>/g, "&gt;");

        const newCardHTML = `
            <div class="card-wrapper"> <div class="camada-fundo">
                    <div class="card-fundo"></div>
                    <div class="card-fundo"></div>
                </div>
                <div class="card-depoimento">
                    <p>"${textoEscapado}"</p>
                    <span>- ${autorEscapado}</span>
                </div>
            </div>
        `;
        
        // Insere o novo card NO COMEÇO do container
        containerDepoimentos.insertAdjacentHTML('afterbegin', newCardHTML);
    }

    // --- 5. INICIALIZAÇÃO (do seu código) ---
    inicializarCarrossel();
});