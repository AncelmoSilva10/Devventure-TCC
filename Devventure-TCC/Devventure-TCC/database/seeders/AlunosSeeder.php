<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AlunosSeeder extends Seeder
{
    public function run()
    {
        // Loop do ID 20 até 29 (10 alunos)
        for ($i = 20; $i < 30; $i++) {
            
            // Define os pontos: ID 20 = 10 pts, ID 21 = 11 pts... ID 29 = 19 pts
            $pontos = $i - 10; 

            // 1. Tabela 'aluno'
            // Usamos updateOrInsert para atualizar os dados se o aluno já existir
            DB::table('aluno')->updateOrInsert(
                ['id' => $i], // Busca por este ID
                [
                    'nome' => "Aluno Teste $i",
                    'ra' => (string) $i,
                    'semestre' => '1º Semestre',
                    'email' => "aluno{$i}@teste.com",
                    'total_pontos' => $pontos, // <--- PONTOS AQUI (10 a 19)
                    'avatar' => null,
                    'status' => 'ativo',
                    // Senha padrão '12345678' (Hash)
                    'password' => Hash::make('12345678'), 
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // 2. Tabela pivô 'aluno_turma' (Vínculo com Turma 1)
            // Garante que o vínculo exista
            $exists = DB::table('aluno_turma')
                ->where('aluno_id', $i)
                ->where('turma_id', 1)
                ->exists();

            if (!$exists) {
                DB::table('aluno_turma')->insert([
                    'aluno_id' => $i,
                    'turma_id' => 1,
                ]);
            }
        }
    }
}