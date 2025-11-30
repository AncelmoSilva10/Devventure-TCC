/* --- TOGGLE DE ABAS --- */
window.toggleRecipient = function(type) {
    const tabAll = document.getElementById('tab-all');
    const tabSelect = document.getElementById('tab-select');
    const listContainer = document.getElementById('student-list-container');

    if (type === 'all') {
        tabAll.classList.add('active');
        tabSelect.classList.remove('active');
        listContainer.classList.remove('show');
    } else {
        tabSelect.classList.add('active');
        tabAll.classList.remove('active');
        listContainer.classList.add('show');
    }
}

document.addEventListener("DOMContentLoaded", function () {
    
    // --- FUNÇÃO PARA ABRIR/FECHAR MODAL ---
    function setupModal(btnId, modalId) {
        const btn = document.getElementById(btnId);
        const modal = document.getElementById(modalId);
        
        if (!btn) {
            console.warn(`Botão ${btnId} não encontrado.`);
            return;
        }
        if (!modal) {
            console.warn(`Modal ${modalId} não encontrado.`);
            return;
        }

        const closeBtn = modal.querySelector(".modal-close");
        const cancelBtn = modal.querySelector(".btn-cancelar");

        function open(e) {
            if(e) e.preventDefault();
            modal.classList.add("active");
        }
        function close() {
            modal.classList.remove("active");
        }

        btn.addEventListener("click", open);
        if (closeBtn) closeBtn.addEventListener("click", close);
        if (cancelBtn) cancelBtn.addEventListener("click", close);

        modal.addEventListener("click", (e) => {
            if (e.target === modal) close();
        });
    }

    // Inicializa os 3 modais
    setupModal("btnAbrirModalAula", "modalAdicionarAula");
    setupModal("btnAbrirModalAluno", "modalConvidarAluno");
    setupModal("btnAbrirModalAviso", "modalEnviarAviso"); // Verifica se este ID está no HTML

    // --- SELECIONAR TODOS (Checkbox) ---
    const selectAll = document.getElementById('selectAllStudents');
    if(selectAll){
        selectAll.addEventListener('change', function(){
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }

    // --- SWEET ALERTS (Se houver mensagens) ---
    if (window.flashMessages) {
        if (window.flashMessages.sweetSuccessConvite) {
            Swal.fire({ icon: 'success', title: 'Sucesso!', text: window.flashMessages.sweetSuccessConvite, confirmButtonColor: '#1a62ff' });
        }
        if (window.flashMessages.sweetErrorConvite) {
            Swal.fire({ icon: 'error', title: 'Atenção', text: window.flashMessages.sweetErrorConvite, confirmButtonColor: '#d33' });
        }
        if (window.flashMessages.sweetErrorAula) {
            Swal.fire({ icon: 'error', title: 'Erro', text: window.flashMessages.sweetErrorAula, confirmButtonColor: '#d33' });
        }
    }
});