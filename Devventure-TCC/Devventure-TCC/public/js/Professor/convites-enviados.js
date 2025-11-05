/* public/js/convites-enviados.js */

// Roda o script quando o HTML estiver pronto
document.addEventListener("DOMContentLoaded", function() {

    // --- FUNCIONALIDADE 1: Fechar Alertas Manualmente ---
    const closeButtons = document.querySelectorAll(".alert-close-btn");
    
    closeButtons.forEach(button => {
        button.addEventListener("click", function() {
            // 'closest' sobe no DOM e acha o 'alert' pai para remover
            this.closest(".alert").style.display = 'none';
        });
    });

    // --- FUNCIONALIDADE 2: Auto-fechar Alertas de SUCESSO ---
    const successAlerts = document.querySelectorAll(".alert-sucesso");
    
    successAlerts.forEach(alert => {
        // Depois de 4 segundos...
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            // Espera a transição acabar para remover da página
            setTimeout(() => alert.remove(), 500); 
        }, 4000); 
    });
    // (Alertas de ERRO não fecham sozinhos, o que é uma boa prática)

    // --- FUNCIONALIDADE 3: Confirmação Inteligente para Cancelar ---
    const cancelForms = document.querySelectorAll(".form-cancelar-convite");
    
    cancelForms.forEach(form => {
        form.addEventListener("submit", function(event) {
            // 1. Previne o envio automático do formulário
            event.preventDefault(); 
            
            // 2. Pega o nome do aluno de dentro do item (Isso é o "Top"!)
            const conviteItem = form.closest(".convite-item");
            const alunoNome = conviteItem.querySelector(".info-aluno strong").textContent;
            
            // 3. Cria uma mensagem personalizada
            const mensagem = `Tem certeza que deseja cancelar o convite para ${alunoNome}? \n\nEsta ação não pode ser desfeita.`;

            // 4. Mostra a confirmação
            if (confirm(mensagem)) {
                // 5. Se o usuário confirmar, envia o formulário
                form.submit();
            }
            
            // Se o usuário clicar "Cancelar", nada acontece.
        });
    });
});