 const abrirBtn = document.querySelector('.add-turma button');
    const modal = document.getElementById('modal');
    const cancelarBtn = document.getElementById('cancelar');

    abrirBtn.addEventListener('click', () => {
      modal.style.display = 'flex';
    });

    cancelarBtn.addEventListener('click', () => {
      modal.style.display = 'none';
    });

    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.style.display = 'none';
      }
    });


    const btnVerTudo = document.getElementById('btnVerTudo');
const exerciciosScroll = document.querySelector('.turmas-scroll');


btnVerTudo.addEventListener('click', () => {
  exerciciosScroll.classList.toggle('expandido');

  // Troca o texto do botão
  if (exerciciosScroll.classList.contains('expandido')) {
    btnVerTudo.textContent = 'Mostrar em grade';
  } else {
    btnVerTudo.textContent = 'Ver tudo';
  }
});

document.addEventListener('DOMContentLoaded', function() {
    
    // Elementos do DOM
    const modal = document.getElementById('modal');
    const btnHeader = document.getElementById('btnAdicionarHeader');
    const btnEmpty = document.getElementById('btnAdicionarEmpty'); 
    const btnCancel = document.getElementById('cancelar');

    // Função para abrir o modal
    function openModal() {
        if(modal) modal.classList.add('active');
    }

    // Função para fechar o modal
    function closeModal() {
        if(modal) modal.classList.remove('active');
    }

    // Event Listeners
    if(btnHeader) {
        btnHeader.addEventListener('click', openModal);
    }

    if(btnEmpty) {
        btnEmpty.addEventListener('click', openModal);
    }

    if(btnCancel) {
        btnCancel.addEventListener('click', closeModal);
    }

    // Fechar ao clicar fora do modal (na parte escura)
    if(modal) {
        modal.addEventListener('click', (e) => {
            if(e.target === modal) {
                closeModal();
            }
        });
    }

});