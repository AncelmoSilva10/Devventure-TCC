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