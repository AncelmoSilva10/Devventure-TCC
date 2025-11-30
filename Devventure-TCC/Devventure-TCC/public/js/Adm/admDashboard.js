document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    const sections = document.querySelectorAll('.dashboard-section');
    const navLinks = document.querySelectorAll('.sidebar-nav ul li a');
    const menuToggle = document.getElementById('menuToggle');
    let activeCharts = [];

    //-----------------------------------------
    //  FUNÇÃO PRINCIPAL PARA TROCAR DE ABA
    //-----------------------------------------
    function showSection(targetId) {
        navLinks.forEach(item => {
            if (item.getAttribute('href') === '#' + targetId) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
        sections.forEach(section => {
            if (section.id === targetId) {
                section.classList.add('active');
                // Chama a função para desenhar os gráficos dessa seção
                initSectionCharts(targetId);
            } else {
                section.classList.remove('active');
            }
        });
    }
    
    //-----------------------------------------
    //  LÓGICA QUE RODA AO CARREGAR A PÁGINA
    //-----------------------------------------
    function handlePageLoad() {
        const hash = window.location.hash.substring(1);
        const initialSectionId = hash || 'overview';
        showSection(initialSectionId);
    }
    handlePageLoad();

    //-----------------------------------------------------
    //  EVENTOS DE CLIQUE E REDIMENSIONAMENTO
    //-----------------------------------------------------
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            document.querySelector('.main-content').classList.toggle('expanded');
        });
    }
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            history.pushState(null, '', '#' + targetId);
            showSection(targetId);
        });
    });

    // Redimensiona os gráficos se a tela mudar de tamanho
    window.addEventListener('resize', function() {
        activeCharts.forEach(chart => {
            if (chart && chart.resize) {
                chart.resize();
            }
        });
    });

    //---------------------------------------------------
    //  INICIALIZAÇÃO DE GRÁFICOS (ATUALIZADO)
    //---------------------------------------------------
    function initSectionCharts(sectionId) {
        // 1. Limpa gráficos antigos para não dar erro ou sobrepor
        activeCharts.forEach(chart => {
            if (chart && chart.dispose) {
                chart.dispose();
            }
        });
        activeCharts = [];

        // 2. Pega os dados que vieram do PHP (window.dashboardData)
        // Se alguma variável não existir, usa 0 como padrão
        const dashboardData = window.dashboardData || {};
        const alunosCount = dashboardData.alunosCount || 0;
        const professoresCount = dashboardData.professoresCount || 0;
        const turmasCount = dashboardData.turmasCount || 0;         // NOVO
        const exerciciosCount = dashboardData.exerciciosCount || 0; // NOVO

        const alunosProfessoresChartData = [
            { value: alunosCount, name: 'Alunos' },
            { value: professoresCount, name: 'Professores' }
        ];

        // --- ABA: VISÃO GERAL (OVERVIEW) ---
        if (sectionId === 'overview') {
            // Gráfico Pizza Pequeno (Visão Geral)
            if (document.getElementById('alunosProfessoresChart')) {
                const chart1 = echarts.init(document.getElementById('alunosProfessoresChart'));
                chart1.setOption({
                    tooltip: { trigger: 'item', formatter: '{a} <br/>{b} : {c} ({d}%)' },
                    legend: { orient: 'vertical', left: 'left', data: ['Alunos', 'Professores'] },
                    series: [{ 
                        name: 'Contagem', 
                        type: 'pie', 
                        radius: '50%', 
                        data: alunosProfessoresChartData, 
                        emphasis: { itemStyle: { shadowBlur: 10, shadowOffsetX: 0, shadowColor: 'rgba(0, 0, 0, 0.5)' } } 
                    }],
                    color: ['#4299e1', '#a0aec0']
                });
                activeCharts.push(chart1);
            }
            // Gráfico Barras Pequeno (Visão Geral)
            if (document.getElementById('overviewBarChart')) {
                const chart2 = echarts.init(document.getElementById('overviewBarChart'));
                chart2.setOption({
                    title: { text: 'Alunos vs Professores', subtext: 'Contagem Absoluta', left: 'center', textStyle: { fontSize: 14 }, subtextStyle: { fontSize: 10 } },
                    tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
                    xAxis: { type: 'category', data: ['Alunos', 'Professores'], axisLabel: { fontSize: 10, fontWeight: 'bold' } },
                    yAxis: { type: 'value' },
                    series: [{ 
                        name: 'Quantidade', 
                        type: 'bar', 
                        data: [
                            { value: alunosCount, itemStyle: { color: '#4299e1' } }, 
                            { value: professoresCount, itemStyle: { color: '#a0aec0' } }
                        ], 
                        barWidth: '50%' 
                    }]
                });
                activeCharts.push(chart2);
            }
        }

        // --- ABA: ANÁLISES (CHARTS-SECTION) ---
        if (sectionId === 'charts-section') {
            
            // ============================================================
            // GRÁFICO 1: USUÁRIOS (PIZZA) - Alunos vs Professores
            // ============================================================
            if (document.getElementById('userDistributionPieChart')) {
                const chart1 = echarts.init(document.getElementById('userDistributionPieChart'));
                chart1.setOption({
                    tooltip: { trigger: 'item', formatter: '{b}: {c} ({d}%)' },
                    legend: { bottom: 0 },
                    color: ['#3498db', '#95a5a6'], // Azul e Cinza
                    series: [{
                        name: 'Usuários',
                        type: 'pie',
                        radius: '55%',
                        data: alunosProfessoresChartData,
                        emphasis: { itemStyle: { shadowBlur: 10, shadowOffsetX: 0, shadowColor: 'rgba(0, 0, 0, 0.5)' } }
                    }]
                });
                activeCharts.push(chart1);
            }

            // ============================================================
            // GRÁFICO 2: USUÁRIOS (BARRAS) - Alunos vs Professores
            // ============================================================
            if (document.getElementById('userDistributionBarChart')) {
                const chart2 = echarts.init(document.getElementById('userDistributionBarChart'));
                chart2.setOption({
                    tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
                    grid: { left: '3%', right: '4%', bottom: '10%', containLabel: true },
                    xAxis: { type: 'category', data: ['Alunos', 'Professores'] },
                    yAxis: { type: 'value' },
                    series: [{
                        name: 'Total',
                        type: 'bar',
                        barWidth: '50%',
                        label: { show: true, position: 'top' },
                        data: [
                            { value: alunosCount, itemStyle: { color: '#3498db' } },
                            { value: professoresCount, itemStyle: { color: '#95a5a6' } }
                        ]
                    }]
                });
                activeCharts.push(chart2);
            }

            // ============================================================
            // GRÁFICO 3 (NOVO): CONTEÚDO (PIZZA) - Turmas vs Exercícios
            // ============================================================
            if (document.getElementById('contentDistributionPieChart')) {
                const chart3 = echarts.init(document.getElementById('contentDistributionPieChart'));
                chart3.setOption({
                    tooltip: { trigger: 'item', formatter: '{b}: {c} ({d}%)' },
                    legend: { bottom: 0 },
                    color: ['#2ecc71', '#e74c3c'], // Verde e Vermelho
                    series: [{
                        name: 'Conteúdo',
                        type: 'pie',
                        radius: ['40%', '70%'], // Estilo Donut para diferenciar
                        itemStyle: { borderRadius: 5, borderColor: '#fff', borderWidth: 2 },
                        data: [
                            { value: turmasCount, name: 'Turmas' },
                            { value: exerciciosCount, name: 'Exercícios' }
                        ]
                    }]
                });
                activeCharts.push(chart3);
            }

            // ============================================================
            // GRÁFICO 4 (NOVO): GERAL (BARRAS) - Tudo junto
            // ============================================================
            if (document.getElementById('contentDistributionBarChart')) {
                const chart4 = echarts.init(document.getElementById('contentDistributionBarChart'));
                chart4.setOption({
                    tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
                    grid: { left: '3%', right: '4%', bottom: '10%', containLabel: true },
                    xAxis: { 
                        type: 'category', 
                        data: ['Alunos', 'Professores', 'Turmas', 'Exercícios'],
                        axisLabel: { interval: 0, rotate: 0 } // Garante que todos os nomes apareçam
                    },
                    yAxis: { type: 'value' },
                    series: [{
                        name: 'Quantidade',
                        type: 'bar',
                        barWidth: '45%',
                        label: { show: true, position: 'top' },
                        data: [
                            { value: alunosCount, itemStyle: { color: '#3498db' } },      // Azul
                            { value: professoresCount, itemStyle: { color: '#95a5a6' } }, // Cinza
                            { value: turmasCount, itemStyle: { color: '#2ecc71' } },      // Verde
                            { value: exerciciosCount, itemStyle: { color: '#e74c3c' } }   // Vermelho
                        ]
                    }]
                });
                activeCharts.push(chart4);
            }
        }
    }
    // ===================================================
    //             SEÇÃO DE BUSCA ALUNOS
    // ===================================================
    const searchAlunosForm = document.getElementById('searchAlunosForm');
    const searchAlunosInput = document.getElementById('searchAlunosInput');
    const alunosTableBody = document.getElementById('alunosTableBody');
    const alunosPagination = document.getElementById('alunosPagination');

    if(searchAlunosForm) {
        searchAlunosForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const query = searchAlunosInput.value;
            const searchUrl = searchAlunosForm.dataset.searchUrl; 
            
            if (query.length === 0) {
                window.location.href = '/admDashboard#alunos'; 
                window.location.reload(); // Força recarregar para limpar filtro
                return;
            }
            
            fetch(`${searchUrl}?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    if(alunosPagination) alunosPagination.style.display = 'none';
                    alunosTableBody.innerHTML = ''; 

                    const blockUrlBase = alunosTableBody.dataset.blockUrl;
                    const unblockUrlBase = alunosTableBody.dataset.unblockUrl;
                    const csrfToken = alunosTableBody.dataset.csrfToken;

                    if (data.length > 0) {
                        data.forEach(aluno => {
                            const statusBadge = aluno.status === 'ativo' ? '<span class="badge badge-success">Ativo</span>' : '<span class="badge badge-danger">Bloqueado</span>';
                            let actionButton = '';
                            const nomeEscapado = aluno.nome.replace(/"/g, '&quot;');
                            
                            if (aluno.status === 'ativo') {
                                actionButton = `
                                    <form action="${blockUrlBase}/${aluno.id}/block" method="POST" style="display: inline;" 
                                          class="form-confirm" data-action-text="bloquear" data-user-name="${nomeEscapado}">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <button type="submit" class="btn-icon" title="Bloquear Aluno"><i class="fas fa-ban" style="color: #e53e3e;"></i></button>
                                    </form>
                                `;
                            } else {
                                actionButton = `
                                    <form action="${unblockUrlBase}/${aluno.id}/unblock" method="POST" style="display: inline;"
                                          class="form-confirm" data-action-text="desbloquear" data-user-name="${nomeEscapado}">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <button type="submit" class="btn-icon" title="Desbloquear Aluno"><i class="fas fa-check-circle" style="color: #48bb78;"></i></button>
                                    </form>
                                `;
                            }
                            actionButton = `<a href="#" class="btn-icon" title="Ver Detalhes do Aluno"><i class="fas fa-eye"></i></a> ` + actionButton;

                            const row = `
                                <tr>
                                    <td>${aluno.nome}</td>
                                    <td>${aluno.email}</td>
                                    <td>${aluno.ra}</td>
                                    <td>${statusBadge}</td>
                                    <td>${actionButton}</td>
                                </tr>
                            `;
                            alunosTableBody.innerHTML += row;
                        });
                    } else {
                        alunosTableBody.innerHTML = '<tr><td colspan="5" class="text-center">Nenhum resultado encontrado.</td></tr>';
                    }
                    
                    attachConfirmListeners();
                });
        });
    }

    // ===================================================
    //             SEÇÃO DE BUSCA PROFESSORES
    // ===================================================
    const searchProfessoresForm = document.getElementById('searchProfessoresForm');
    const searchProfessoresInput = document.getElementById('searchProfessoresInput');
    const professoresTableBody = document.getElementById('professoresTableBody');
    const professoresPagination = document.getElementById('professoresPagination');

    if(searchProfessoresForm) {
        searchProfessoresForm.addEventListener('submit', function(event) {
            event.preventDefault(); 
            const query = searchProfessoresInput.value;
            const searchUrl = searchProfessoresForm.dataset.searchUrl;

            if (query.length === 0) {
                window.location.href = '/admDashboard#professores'; 
                window.location.reload();
                return;
            }

            fetch(`${searchUrl}?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    if(professoresPagination) professoresPagination.style.display = 'none';
                    professoresTableBody.innerHTML = '';

                    const blockUrlBase = professoresTableBody.dataset.blockUrl;
                    const unblockUrlBase = professoresTableBody.dataset.unblockUrl;
                    const csrfToken = professoresTableBody.dataset.csrfToken;

                    if (data.length > 0) {
                        data.forEach(professor => {
                            const statusBadge = professor.status === 'ativo' ? '<span class="badge badge-success">Ativo</span>' : '<span class="badge badge-danger">Bloqueado</span>';
                            let actionButton = '';
                            const nomeEscapado = professor.nome.replace(/"/g, '&quot;');
                            
                            if (professor.status === 'ativo') {
                                actionButton = `
                                    <form action="${blockUrlBase}/${professor.id}/block" method="POST" style="display: inline;" 
                                          class="form-confirm" data-action-text="bloquear" data-user-name="${nomeEscapado}">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <button type="submit" class="btn-icon" title="Bloquear Professor"><i class="fas fa-ban" style="color: #e53e3e;"></i></button>
                                    </form>
                                `;
                            } else {
                                actionButton = `
                                    <form action="${unblockUrlBase}/${professor.id}/unblock" method="POST" style="display: inline;"
                                          class="form-confirm" data-action-text="desbloquear" data-user-name="${nomeEscapado}">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <button type="submit" class="btn-icon" title="Desbloquear Professor"><i class="fas fa-check-circle" style="color: #48bb78;"></i></button>
                                    </form>
                                `;
                            }
                            actionButton = `<a href="#" class="btn-icon" title="Ver Detalhes do Professor"><i class="fas fa-eye"></i></a> ` + actionButton;

                            const row = `
                                <tr>
                                    <td>${professor.nome}</td>
                                    <td>${professor.email}</td>
                                    <td>${professor.cpf}</td>
                                    <td>${statusBadge}</td>
                                    <td>${actionButton}</td>
                                </tr>
                            `;
                            professoresTableBody.innerHTML += row;
                        });
                    } else {
                        professoresTableBody.innerHTML = '<tr><td colspan="5" class="text-center">Nenhum resultado encontrado.</td></tr>';
                    }
                    
                    attachConfirmListeners();
                });
        });
    }

    // ===================================================
    //             SEÇÃO DE BUSCA DEPOIMENTOS
    // ===================================================
    const searchDepoForm = document.getElementById('searchDepoimentosForm');
    const searchDepoInput = document.getElementById('searchDepoimentosInput');
    const depoTableBody = document.getElementById('depoimentosTableBody');
    const depoPagination = document.getElementById('depoimentosPagination');
    
    // Guarda o HTML original caso o JS seja carregado antes do DOM estar 100% pronto
    let originalDepoHTML = ''; 
    let originalDepoPagination = '';

    if (depoTableBody) originalDepoHTML = depoTableBody.innerHTML;
    if (depoPagination) originalDepoPagination = depoPagination.innerHTML;

    function popularTabelaDepoimentos(data) {
        const blockUrlBase = depoTableBody.dataset.blockUrl;
        const unblockUrlBase = depoTableBody.dataset.unblockUrl;
        const csrfToken = depoTableBody.dataset.csrfToken;

        depoTableBody.innerHTML = ''; 

        if (data.length === 0) {
            depoTableBody.innerHTML = '<tr><td colspan="4" class="text-center">Nenhum depoimento encontrado.</td></tr>';
            return;
        }

        data.forEach(depoimento => {
            const statusBadge = depoimento.aprovado ? '<span class="badge badge-success">Aprovado</span>' : '<span class="badge badge-danger">Bloqueado</span>';
            let actionButton = '';
            const autorEscapado = depoimento.autor.replace(/"/g, '&quot;');
            
            if (depoimento.aprovado) {
                actionButton = `
                    <form action="${blockUrlBase}/${depoimento.id}/block" method="POST" style="display: inline;" 
                          class="form-confirm" data-action-text="bloquear" data-user-name="o depoimento de ${autorEscapado}">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <button type="submit" class="btn-icon" title="Bloquear Depoimento"><i class="fas fa-ban" style="color: #e53e3e;"></i></button>
                    </form>
                `;
            } else {
                actionButton = `
                    <form action="${unblockUrlBase}/${depoimento.id}/unblock" method="POST" style="display: inline;"
                          class="form-confirm" data-action-text="aprovar" data-user-name="o depoimento de ${autorEscapado}">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <button type="submit" class="btn-icon" title="Aprovar Depoimento"><i class="fas fa-check-circle" style="color: #48bb78;"></i></button>
                    </form>
                `;
            }
            
            const textoLimitado = depoimento.texto.length > 80 ? depoimento.texto.substring(0, 80) + '...' : depoimento.texto;
            const textoEscapado = depoimento.texto.replace(/"/g, '&quot;');

            const newRow = `
                <tr>
                    <td>${depoimento.autor}</td>
                    <td><span title="${textoEscapado}">${textoLimitado}</span></td>
                    <td>${statusBadge}</td>
                    <td>${actionButton}</td>
                </tr>
            `;
            depoTableBody.innerHTML += newRow;
        });
        
        attachConfirmListeners();
    }

    if(searchDepoForm) {
        searchDepoForm.addEventListener('submit', function(e) {
            e.preventDefault(); 
            const query = searchDepoInput.value.trim();
            const searchUrl = searchDepoForm.dataset.searchUrl;

            if (query.length === 0) {
                if(depoTableBody) depoTableBody.innerHTML = originalDepoHTML;
                if(depoPagination) {
                    depoPagination.innerHTML = originalDepoPagination;
                    depoPagination.style.display = 'block';
                }
                attachConfirmListeners();
                return;
            }

            if(depoPagination) depoPagination.style.display = 'none';

            fetch(`${searchUrl}?query=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                popularTabelaDepoimentos(data);
            })
            .catch(error => {
                console.error('Erro ao buscar depoimentos:', error);
                if(depoTableBody) depoTableBody.innerHTML = '<tr><td colspan="4" class="text-center">Erro ao carregar resultados.</td></tr>';
            });
        });
    }

    // ===================================================
    //             LISTENERS DE LIMPAR CAMPO
    // ===================================================
    if(searchAlunosInput) {
        searchAlunosInput.addEventListener('input', function() {
            if (this.value.length === 0) {
                window.location.href = '/admDashboard#alunos';
                window.location.reload();
            }
        });
    }

    if(searchProfessoresInput) {
        searchProfessoresInput.addEventListener('input', function() {
            if (this.value.length === 0) {
                window.location.href = '/admDashboard#professores';
                window.location.reload();
            }
        });
    }

    if(searchDepoInput) {
        searchDepoInput.addEventListener('input', function() {
            if (this.value.length === 0) {
                if(depoTableBody) depoTableBody.innerHTML = originalDepoHTML;
                if(depoPagination) {
                    depoPagination.innerHTML = originalDepoPagination;
                    depoPagination.style.display = 'block';
                }
                attachConfirmListeners();
            }
        });
    }

    // ===================================================
    //             CÓDIGO DE CONFIRMAÇÃO (REATORADO)
    // ===================================================
    function handleConfirmSubmit(event) {
        event.preventDefault(); 
        const actionText = this.dataset.actionText;
        const userName = this.dataset.userName;
        
        Swal.fire({
            title: 'Tem certeza?',
            html: `Você realmente deseja <b>${actionText}</b> ${userName}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6', 
            cancelButtonColor: '#d33', 
            confirmButtonText: `Sim, ${actionText}!`,
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit(); 
            }
        });
    }

    function attachConfirmListeners() {
        const confirmationForms = document.querySelectorAll('.form-confirm');
        
        confirmationForms.forEach(form => {
            form.removeEventListener('submit', handleConfirmSubmit); 
            form.addEventListener('submit', handleConfirmSubmit);
        });
    }
    
    // Roda uma vez no início para pegar os botões que já existem
    attachConfirmListeners();

});