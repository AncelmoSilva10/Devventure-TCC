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
            'aprovado' => true, 
        ]);

        // Retorna o depoimento criado como JSON
        // O JavaScript usará isso para adicionar o card na hora.
        return response()->json([
            'success' => true,
            'message' => 'Depoimento enviado com sucesso!',
            'depoimento' => $depoimento
        ]);
    }
}
