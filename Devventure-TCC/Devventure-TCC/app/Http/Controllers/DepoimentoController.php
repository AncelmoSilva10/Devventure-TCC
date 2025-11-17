<?php

namespace App\Http\Controllers;

use App\Models\Depoimento;
use Illuminate\Http\Request;

class DepoimentoController extends Controller
{
    
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'texto' => 'required|string|max:300',
            'autor' => 'required|string|max:255',
        ]);

        
        $depoimento = Depoimento::create([
            'texto' => $validated['texto'],
            'autor' => $validated['autor'],
            'aprovado' => false, // <-- MUDANÇA PRINCIPAL AQUI
        ]);

        // Retorna uma resposta de sucesso para o JavaScript
        // Removi o objeto 'depoimento' para que o JS não o adicione na tela.
        return response()->json([
            'success' => true,
            'message' => 'Depoimento enviado para aprovação!', 
        ]);
    }
}