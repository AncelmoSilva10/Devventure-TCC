<?php

use App\Http\Controllers\Professor\TurmaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// --- Importe seus Controllers ---
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Auth\AlunoLoginController;
use App\Http\Controllers\Auth\ProfessorLoginController;
use App\Http\Controllers\Auth\AdmLoginController;
use App\Http\Controllers\Aluno\PerfilController as AlunoPerfilController;
use App\Http\Controllers\Aluno\AlunoProvaController;
use App\Http\Controllers\Aluno\RespostaController;
use App\Http\Controllers\Professor\PerfilController as ProfessorPerfilController;
use App\Http\Controllers\Professor\ProvasController ;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Aluno\ExercicioAlunoController;
use App\Http\Controllers\Professor\AvisoController;
use App\Http\Controllers\DepoimentoController;
use App\Models\Depoimento;
use App\Http\Controllers\Professor\RelatorioController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- PÁGINAS PÚBLICAS E FORMULÁRIOS DE LOGIN ---
//Home
Route::get('/', function () {
    
    $depoimentos = Depoimento::where('aprovado', true)->latest()->get();

    return view('welcome', [
        'depoimentos' => $depoimentos
    ]);

});

// Rota para SALVAR o depoimento
Route::post('/depoimentos', [DepoimentoController::class, 'store'])->name('depoimentos.store');

Route::get('loginAluno', function () { return view('Aluno.login'); })->name('login.aluno');
Route::get('loginProfessor', function () { return view('Professor.login'); })->name('login.professor');
Route::get('/loginAdm', function () { return view('Adm.login'); })->name('login.admin');


// --- FLUXO DE AUTENTICAÇÃO, CADASTRO, 2FA E REDEFINIÇÃO DE SENHA ---

// CADASTRO
Route::post('/cadastro-aluno', [AlunoPerfilController::class, 'store'])->name('aluno.cadastrar');
Route::post('/cadastrar-prof', [ProfessorPerfilController::class, 'store'])->name('professor.cadastro.action');

// LOGIN
Route::post('/login-aluno', [AlunoLoginController::class, 'verifyUser'])->name('aluno.login');
Route::post('/login-professor', [ProfessorLoginController::class, 'verifyUser'])->name('professor.login.action');
Route::post('/login-adm', [AdmLoginController::class, 'verifyUser']);

// VERIFICAÇÃO DE DUAS ETAPAS (2FA) APÓS O LOGIN
// --- URL ALTERADA para evitar conflito ---
Route::get('/cadastro/verificar-2fa', [TwoFactorController::class, 'showVerifyForm'])->name('2fa.verify.form');
Route::post('/cadastro/verificar-2fa', [TwoFactorController::class, 'verifyCode'])->name('2fa.verify.code');
Route::post('/cadastro/reenviar-verificacao', [TwoFactorController::class, 'resend'])->name('verification.resend');

// ESQUECEU A SENHA
// Rota para exibir o formulário de "Esqueceu a Senha"
Route::get('/esqueceu-senha', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Rota para enviar o e-mail com o código
Route::post('/esqueceu-senha', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// Rota para o CÓDIGO de redefinição de senha
Route::get('/redefinir-senha/verificar-codigo', [ForgotPasswordController::class, 'showVerifyForm'])->name('password.verify.form');
Route::post('/redefinir-senha/verificar-codigo', [ForgotPasswordController::class, 'verifyCode'])->name('password.verify.code');

// Rota para MOSTRAR a tela final de redefinição de senha
Route::get('/redefinir-senha/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');

// Rota para PROCESSAR e salvar a nova senha
Route::post('/redefinir-senha', [ResetPasswordController::class, 'reset'])->name('password.update');



// VERIFICAÇÃO DE E-MAIL APÓS CADASTRO
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    // Redireciona para o dashboard correto após a verificação
    if (Auth::user() instanceof \App\Models\Professor) {
        return redirect()->route('login-professor');
    }
    return redirect()->route('aluno-login');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Um novo link de verificação foi enviado!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// --- ROTAS PROTEGIDAS ---

// ROTAS DO ALUNO
Route::middleware(['auth:aluno'])->group(function () {
    Route::get('/alunoDashboard', \App\Http\Controllers\Aluno\DashboardController::class)->name('aluno.dashboard');
    Route::post('/logout-aluno', [AlunoLoginController::class, 'logoutUser'])->name('aluno.logout');
    Route::get('/aluno/perfil', [AlunoPerfilController::class, 'edit'])->name('aluno.perfil.edit');
    Route::patch('/aluno/perfil', [AlunoPerfilController::class, 'update'])->name('aluno.perfil.update');

    Route::get('/minhas-turmas', [App\Http\Controllers\Aluno\TurmaController::class, 'minhasTurmas'])->name('aluno.turma');
    Route::get('/turmaAluno/{turma}', [App\Http\Controllers\Aluno\TurmaController::class, 'mostrarTurmaEspecifica'])->name('turmas.especifica');
    Route::get('/turma/{turma}/ranking', [App\Http\Controllers\Aluno\TurmaController::class, 'mostrarRanking'])->name('aluno.turma.ranking');

    Route::get('/aula/{aula}', [App\Http\Controllers\Aluno\AulaController::class, 'aula'])->name('aulas.view');
    Route::post('/aula/progresso', [App\Http\Controllers\Aluno\AulaController::class, 'salvarProgresso'])->name('aulas.progresso');
    Route::post('/aulas/{aula}/formulario/responder', [RespostaController::class, 'store'])->name('aluno.formulario.responder');

    Route::post('/convite/{convite}/aceitar', [App\Http\Controllers\Aluno\ConviteController::class, 'aceitar'])->name('convites.aceitar');
    Route::post('/convite/{convite}/recusar', [App\Http\Controllers\Aluno\ConviteController::class, 'recusar'])->name('convites.recusar');

    Route::get('/aluno/exercicios/{exercicio}', [ExercicioAlunoController::class, 'mostrar'])->name('aluno.exercicios.mostrar');
    Route::post('/aluno/exercicios/{exercicio}/responder', [ExercicioAlunoController::class, 'responder'])->name('aluno.exercicios.responder');

    Route::get('/aluno/provas/{prova}', [AlunoProvaController::class, 'show'])->name('aluno.provas.show');
    Route::post('/aluno/provas/{prova}/iniciar', [AlunoProvaController::class, 'iniciar'])->name('aluno.provas.iniciar');
    Route::get('/aluno/provas/tentativa/{tentativa}', [AlunoProvaController::class, 'fazer'])->name('aluno.provas.fazer');
    Route::post('/aluno/provas/tentativa/{tentativa}/submeter', [AlunoProvaController::class, 'submeter'])->name('aluno.provas.submeter');
    Route::get('/aluno/provas/tentativa/{tentativa}/resultado', [AlunoProvaController::class, 'resultado'])->name('aluno.provas.resultado');

});


// ROTAS DO PROFESSOR
Route::middleware(['auth:professor'])->group(function () {
    Route::get('/professorDashboard', [App\Http\Controllers\Professor\DashboardController::class, 'dashboard'])->name('professorDashboard');
    Route::post('/logout-professor', [ProfessorLoginController::class, 'logoutUser'])->name('professor.logout');
    Route::get('/perfilProfessor', [ProfessorPerfilController::class, 'edit'])->name('professor.perfil.edit');
    Route::patch('/perfilProfessorUpdate', [ProfessorPerfilController::class, 'update'])->name('professor.perfil.update');

    Route::get('/professorGerenciar', [App\Http\Controllers\Professor\TurmaController::class, 'GerenciarTurma'])->name('professor.turmas');
    Route::get('/professorGerenciarEspecifica', [App\Http\Controllers\Professor\TurmaController::class, 'turmaEspecifica'])->name('professor.turma.especifica');

    Route::post('/cadastrar-turma', [App\Http\Controllers\Professor\TurmaController::class, 'turma'])->name('professor.turmas.store');
    Route::get('/turmas/{turma}', [App\Http\Controllers\Professor\TurmaController::class, 'turmaEspecificaID'])->name('turmas.especificaID');
    Route::post('/turmas/{turma}/convidar', [App\Http\Controllers\Professor\TurmaController::class, 'convidarAluno'])->name('turmas.convidar');
    Route::post('/turmas/{turma}/aulas', [App\Http\Controllers\Professor\TurmaController::class, 'formsAula'])->name('turmas.aulas.formsAula');
    Route::get('/professor/turmas/{turma}/ranking', [App\Http\Controllers\Professor\TurmaController::class, 'mostrarRanking'])->name('professor.turma.ranking');
    Route::get('/professor/turmas/{turma}/relatorios', [App\Http\Controllers\Professor\RelatorioController::class, 'index'])->name('professor.relatorios.index');
    Route::get('/professor/turmas/{turma}/relatorios/aluno/{aluno}', [App\Http\Controllers\Professor\RelatorioController::class, 'relatorioAluno'])->name('professor.relatorios.aluno');

    Route::get('/turmas/{turma}/provas/{prova}/resultados', [ProvasController::class, 'resultados'])->name('Professor.relatorios.provaResultado');
    Route::get('/professorProvas', [ProvasController::class, 'create'])->name('professor.provas.create');
    Route::post('/professorCriarProvas', [ProvasController::class, 'store'])->name('professor.provas.store');
    Route::delete('/professor/provas/{prova}', [ProvasController::class, 'destroy'])->name('professor.provas.destroy');
    
    Route::get('/professorExercicios', [App\Http\Controllers\Professor\ExercicioController::class, 'exercicios'])->name('professor.exercicios.index');
    Route::post('/professorCriarExercicios', [App\Http\Controllers\Professor\ExercicioController::class, 'CriarExercicios'])->name('professor.exercicios.store');
    Route::get('/professor/exercicios/{exercicio}/respostas', [App\Http\Controllers\Professor\ExercicioController::class, 'mostrarRespostas'])->name('professor.exercicios.respostas');
    Route::post('/professor/respostas/{resposta}/avaliar', [App\Http\Controllers\Professor\ExercicioController::class, 'avaliarResposta'])->name('professor.respostas.avaliar');
    
    Route::get('/professor/aulas/{aula}/formulario/create', [App\Http\Controllers\Professor\FormularioController::class, 'create'])->name('formularios.create');
    Route::post('/professor/aulas/{aula}/formulario', [App\Http\Controllers\Professor\FormularioController::class, 'store'])->name('formularios.store');

    Route::get('/professor/avisos/criar', [AvisoController::class, 'create'])->name('professor.avisos.create');
    Route::post('/professor/avisos', [AvisoController::class, 'store'])->name('professor.avisos.store');

    Route::get('/professor/turma/{turma}/relatorios/exportar', [RelatorioController::class, 'exportar'])
    ->name('professor.relatorios.exportar');

    Route::get('/professor/turma/{turma}/aluno/{aluno}/relatorio/exportar', [RelatorioController::class, 'exportarIndividual'])
    ->name('professor.relatorios.exportarIndividual');
});


// ROTAS DO ADMIN
Route::middleware('auth:admin')->group(function () {
    Route::get('/admDashboard', [App\Http\Controllers\Adm\DashboardController::class, 'admDashboard'])->name('admin.dashboard');

    // Rotas para gerenciar Alunos
    Route::post('/admin/alunos/{aluno}/block', [App\Http\Controllers\Adm\DashboardController::class, 'blockAluno'])->name('admin.alunos.block');
    Route::post('/admin/alunos/{aluno}/unblock', [App\Http\Controllers\Adm\DashboardController::class, 'unblockAluno'])->name('admin.alunos.unblock');

    // Rotas para gerenciar Professores
    Route::post('/admin/professores/{professor}/block', [App\Http\Controllers\Adm\DashboardController::class, 'blockProfessor'])->name('admin.professores.block');
    Route::post('/admin/professores/{professor}/unblock', [App\Http\Controllers\Adm\DashboardController::class, 'unblockProfessor'])->name('admin.professores.unblock');

    // Rota para buscar Alunos
Route::get('/admin/alunos/search', [App\Http\Controllers\Adm\DashboardController::class, 'searchAlunos'])
     ->name('admin.alunos.search');

// Rota para buscar Professores
Route::get('/admin/professores/search', [App\Http\Controllers\Adm\DashboardController::class, 'searchProfessores'])
     ->name('admin.professores.search');

    Route::post('/logout-adm', [AdmLoginController::class, 'logoutUser'])->name('admin.logout');

    Route::get('/download-csvAuno', [App\Http\Controllers\Adm\DashboardController::class, 'downloadCsvAlunos'])->name('download.csvAluno');

     Route::get('/download-csvProf', [App\Http\Controllers\Adm\DashboardController::class, 'downloadCsvProfessores'])->name('download.csvProf');

     //Rotas do Depoimento
    Route::post('/admin/depoimentos/{depoimento}/block', [App\Http\Controllers\Adm\DashboardController::class, 'blockDepoimento'])->name('admin.depoimentos.block');
    Route::post('/admin/depoimentos/{depoimento}/unblock', [App\Http\Controllers\Adm\DashboardController::class, 'unblockDepoimento'])->name('admin.depoimentos.unblock');
    Route::get('/admin/depoimentos/search', [App\Http\Controllers\Adm\DashboardController::class, 'searchDepoimentos'])->name('admin.depoimentos.search');


});