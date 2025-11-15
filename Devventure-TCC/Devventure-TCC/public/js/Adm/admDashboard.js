document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    const sections = document.querySelectorAll('.dashboard-section');
    const navLinks = document.querySelectorAll('.sidebar-nav ul li a');
    const menuToggle = document.getElementById('menuToggle');
    let activeCharts = [];

    //-----------------------------------------
    //  FUNÇÃO PRINCIPAL PARA TROCAR DE ABA
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
                initSectionCharts(targetId);
            } else {
                section.classList.remove('active');
            }
        });
    }
    
    //-----------------------------------------
    //  LÓGICA QUE RODA AO CARREGAR A PÁGINA
    //-----------------------------------------
    function handlePageLoad() {
        const hash = window.location.hash.substring(1);
        const initialSectionId = hash || 'overview';
        showSection(initialSectionId);
    }
    handlePageLoad();

    //-----------------------------------------------------
    //  EVENTOS DE CLIQUE E REDIMENSIONAMENTO
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

    window.addEventListener('resize', function() {
        activeCharts.forEach(chart => {
            if (chart && chart.resize) {
                chart.resize();
            }
        });
    });

    //---------------------------------------------------
    //  INICIALIZAÇÃO DE GRÁFICOS (Seu código)
    //---------------------------------------------------
    function initSectionCharts(sectionId) {
        activeCharts.forEach(chart => {
            if (chart && chart.dispose) {
                chart.dispose();
            }
        });
        activeCharts = [];

        const dashboardData = window.dashboardData;
        const alunosCount = dashboardData.alunosCount;
        const professoresCount = dashboardData.professoresCount;
        const alunosProfessoresChartData = [
            { value: alunosCount, name: 'Alunos' },
            { value: professoresCount, name: 'Professores' }
        ];

        if (sectionId === 'overview') {
            if (document.getElementById('alunosProfessoresChart')) {
                const alunosProfessoresChart = echarts.init(document.getElementById('alunosProfessoresChart'));
                alunosProfessoresChart.setOption({
                    tooltip: { trigger: 'item', formatter: '{a} <br/>{b} : {c} ({d}%)' },
                    legend: { orient: 'vertical', left: 'left', data: ['Alunos', 'Professores'] },
                    series: [{ name: 'Contagem', type: 'pie', radius: '50%', data: alunosProfessoresChartData, emphasis: { itemStyle: { shadowBlur: 10, shadowOffsetX: 0, shadowColor: 'rgba(0, 0, 0, 0.5)' } } }],
                    color: ['#4299e1', '#a0aec0']
                });
                activeCharts.push(alunosProfessoresChart);
            }
            if (document.getElementById('overviewBarChart')) {
                const overviewBarChart = echarts.init(document.getElementById('overviewBarChart'));
                overviewBarChart.setOption({
                    title: { text: 'Alunos vs Professores', subtext: 'Contagem Absoluta', left: 'center', textStyle: { fontSize: 14 }, subtextStyle: { fontSize: 10 } },
                    tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
                    xAxis: { type: 'category', data: ['Alunos', 'Professores'], axisLabel: { fontSize: 10, fontWeight: 'bold' } },
                    yAxis: { type: 'value', name: 'Número de Usuários', nameLocation: 'middle', nameGap: 25, axisLabel: { formatter: '{value}' } },
                    series: [{ name: 'Quantidade', type: 'bar', data: [{ value: alunosCount, name: 'Alunos', itemStyle: { color: '#4299e1' } }, { value: professoresCount, name: 'Professores', itemStyle: { color: '#a0aec0' } }], barWidth: '50%', emphasis: { itemStyle: { shadowBlur: 10, shadowOffsetX: 0, shadowColor: 'rgba(0, 0, 0, 0.5)' } }, label: { show: true, position: 'top', formatter: '{c}', fontSize: 10 } }]
                });
                activeCharts.push(overviewBarChart);
            }
        }
        if (sectionId === 'charts-section') {
            if (document.getElementById('userDistributionPieChart')) {
                const userDistributionPieChart = echarts.init(document.getElementById('userDistributionPieChart'));
                userDistributionPieChart.setOption({
                    title: { text: 'Alunos vs Professores', subtext: 'Proporção Geral', left: 'center' },
                    tooltip: { trigger: 'item', formatter: '{a} <br/>{b} : {c} ({d}%)' },
                    legend: { orient: 'vertical', left: 'left', top: 'bottom', data: ['Alunos', 'Professores'] },
                    series: [{ name: 'Distribuição', type: 'pie', radius: '55%', center: ['50%', '60%'], data: alunosProfessoresChartData, emphasis: { itemStyle: { shadowBlur: 10, shadowOffsetX: 0, shadowColor: 'rgba(0, 0, 0, 0.5)' } }, label: { formatter: '{b}: {c} ({d}%)' } }],
                    color: ['#4299e1', '#a0aec0']
                });
                activeCharts.push(userDistributionPieChart);
            }
            if (document.getElementById('userDistributionBarChart')) {
                const userDistributionBarChart = echarts.init(document.getElementById('userDistributionBarChart'));
                userDistributionBarChart.setOption({
                    title: { text: 'Alunos vs Professores', subtext: 'Contagem Absoluta', left: 'center' },
                    tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
                    xAxis: { type: 'category', data: ['Alunos', 'Professores'], axisLabel: { fontSize: 12, fontWeight: 'bold' } },
                    yAxis: { type: 'value', name: 'Número de Usuários', axisLabel: { formatter: '{value}' } },
                    series: [{ name: 'Quantidade', type: 'bar', data: [{ value: alunosCount, name: 'Alunos', itemStyle: { color: '#4299e1' } }, { value: professoresCount, name: 'Professores', itemStyle: { color: '#a0aec0' } }], barWidth: '40%', emphasis: { itemStyle: { shadowBlur: 10, shadowOffsetX: 0, shadowColor: 'rgba(0, 0, 0, 0.5)' } }, label: { show: true, position: 'top', formatter: '{c}' } }]
                });
                activeCharts.push(userDistributionBarChart);
            }
        }
    }

    // ===================================================
    //             SEÇÃO DE BUSCA ALUNOS
    // ===================================================
    const searchAlunosForm = document.getElementById('searchAlunosForm');
    const searchAlunosInput = document.getElementById('searchAlunosInput');
    const alunosTableBody = document.getElementById('alunosTableBody');
    const alunosPagination = document.getElementById('alunosPagination');

    searchAlunosForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const query = searchAlunosInput.value;
        const searchUrl = searchAlunosForm.dataset.searchUrl; 
        
        if (query.length === 0) {
            window.location.href = '/admDashboard#alunos'; 
            return;
        }
        
        fetch(`${searchUrl}?query=${query}`)
            .then(response => response.json())
            .then(data => {
                alunosPagination.style.display = 'none';
                alunosTableBody.innerHTML = ''; 

                const blockUrlBase = alunosTableBody.dataset.blockUrl; // Será "/admin/alunos"
                const unblockUrlBase = alunosTableBody.dataset.unblockUrl; // Será "/admin/alunos"
                const csrfToken = alunosTableBody.dataset.csrfToken;

                if (data.length > 0) {
                    data.forEach(aluno => {
                        const statusBadge = aluno.status === 'ativo' ? '<span class="badge badge-success">Ativo</span>' : '<span class="badge badge-danger">Bloqueado</span>';
                        let actionButton = '';
                        const nomeEscapado = aluno.nome.replace(/"/g, '&quot;');
                        
                        if (aluno.status === 'ativo') {
                            // **** CORREÇÃO DA URL: Agora é ${blockUrlBase}/${aluno.id}/block ****
                            actionButton = `
                                <form action="${blockUrlBase}/${aluno.id}/block" method="POST" style="display: inline;" 
                                      class="form-confirm" data-action-text="bloquear" data-user-name="${nomeEscapado}">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <button type="submit" class="btn-icon" title="Bloquear Aluno"><i class="fas fa-ban" style="color: #e53e3e;"></i></button>
                                </form>
                            `;
                        } else {
                            // **** CORREÇÃO DA URL: Agora é ${unblockUrlBase}/${aluno.id}/unblock ****
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

    // ===================================================
    //          SEÇÃO DE BUSCA PROFESSORES
    // ===================================================
    const searchProfessoresForm = document.getElementById('searchProfessoresForm');
    const searchProfessoresInput = document.getElementById('searchProfessoresInput');
    const professoresTableBody = document.getElementById('professoresTableBody');
    const professoresPagination = document.getElementById('professoresPagination');

    searchProfessoresForm.addEventListener('submit', function(event) {
        event.preventDefault(); 
        const query = searchProfessoresInput.value;
        const searchUrl = searchProfessoresForm.dataset.searchUrl;

        if (query.length === 0) {
            window.location.href = '/admDashboard#professores'; 
            return;
        }

        fetch(`${searchUrl}?query=${query}`)
            .then(response => response.json())
            .then(data => {
                professoresPagination.style.display = 'none';
                professoresTableBody.innerHTML = '';

                const blockUrlBase = professoresTableBody.dataset.blockUrl; // Será "/admin/professores"
                const unblockUrlBase = professoresTableBody.dataset.unblockUrl; // Será "/admin/professores"
                const csrfToken = professoresTableBody.dataset.csrfToken;

                if (data.length > 0) {
                    data.forEach(professor => {
                        const statusBadge = professor.status === 'ativo' ? '<span class="badge badge-success">Ativo</span>' : '<span class="badge badge-danger">Bloqueado</span>';
                        let actionButton = '';
                        const nomeEscapado = professor.nome.replace(/"/g, '&quot;');
                        
                        if (professor.status === 'ativo') {
                            // **** CORREÇÃO DA URL: Agora é ${blockUrlBase}/${professor.id}/block ****
                            actionButton = `
                                <form action="${blockUrlBase}/${professor.id}/block" method="POST" style="display: inline;" 
                                      class="form-confirm" data-action-text="bloquear" data-user-name="${nomeEscapado}">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <button type="submit" class="btn-icon" title="Bloquear Professor"><i class="fas fa-ban" style="color: #e53e3e;"></i></button>
                                </form>
                            `;
                        } else {
                            // **** CORREÇÃO DA URL: Agora é ${unblockUrlBase}/${professor.id}/unblock ****
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

    // ===================================================
    //          SEÇÃO DE BUSCA DEPOIMENTOS
    // ===================================================
    const searchDepoForm = document.getElementById('searchDepoimentosForm');
    const searchDepoInput = document.getElementById('searchDepoimentosInput');
    const depoTableBody = document.getElementById('depoimentosTableBody');
    const depoPagination = document.getElementById('depoimentosPagination');
    
    const originalDepoHTML = depoTableBody.innerHTML; 
    const originalDepoPagination = depoPagination.innerHTML;

    function popularTabelaDepoimentos(data) {
        const blockUrlBase = depoTableBody.dataset.blockUrl; // Será "/admin/depoimentos"
        const unblockUrlBase = depoTableBody.dataset.unblockUrl; // Será "/admin/depoimentos"
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
                // **** CORREÇÃO DA URL: Agora é ${blockUrlBase}/${depoimento.id}/block ****
                actionButton = `
                    <form action="${blockUrlBase}/${depoimento.id}/block" method="POST" style="display: inline;" 
                          class="form-confirm" data-action-text="bloquear" data-user-name="o depoimento de ${autorEscapado}">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <button type="submit" class="btn-icon" title="Bloquear Depoimento"><i class="fas fa-ban" style="color: #e53e3e;"></i></button>
                    </form>
                `;
            } else {
                // **** CORREÇÃO DA URL: Agora é ${unblockUrlBase}/${depoimento.id}/unblock ****
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

    searchDepoForm.addEventListener('submit', function(e) {
        e.preventDefault(); 
        const query = searchDepoInput.value.trim();
        const searchUrl = searchDepoForm.dataset.searchUrl;

        if (query.length === 0) {
            depoTableBody.innerHTML = originalDepoHTML;
            depoPagination.innerHTML = originalDepoPagination;
            depoPagination.style.display = 'block';
            attachConfirmListeners();
            return;
        }

        depoPagination.style.display = 'none';

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
            depoTableBody.innerHTML = '<tr><td colspan="4" class="text-center">Erro ao carregar resultados.</td></tr>';
        });
    });


    // ===================================================
    //          LISTENERS DE LIMPAR CAMPO
    // ===================================================
    searchAlunosInput.addEventListener('input', function() {
        if (this.value.length === 0) {
            window.location.href = '/admDashboard#alunos';
        }
    });

    searchProfessoresInput.addEventListener('input', function() {
        if (this.value.length === 0) {
            window.location.href = '/admin/dashboard#professores';
        }
    });

    searchDepoInput.addEventListener('input', function() {
        if (this.value.length === 0) {
            depoTableBody.innerHTML = originalDepoHTML;
            depoPagination.innerHTML = originalDepoPagination;
            depoPagination.style.display = 'block';
            attachConfirmListeners();
        }
    });


    // ===================================================
    //          CÓDIGO DE CONFIRMAÇÃO (REATORADO)
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
    
    attachConfirmListeners();

});