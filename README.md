# 🚀 Devventure

![Badge Concluído](http://img.shields.io/static/v1?label=STATUS&message=CONCLUÍDO&color=GREEN&style=for-the-badge)
![Badge TCC](http://img.shields.io/static/v1?label=TIPO&message=TCC%20-%20ETEC&color=BLUE&style=for-the-badge)

> **Trabalho de Conclusão de Curso (TCC)** desenvolvido para o curso técnico de **Desenvolvimento de Sistemas** da **Etec de Guaianazes**.

---

## 💻 Sobre o Projeto

O **Devventure** é um sistema web educacional desenvolvido especificamente para atender as demandas da **Etec de Guaianazes**.

O objetivo da plataforma é facilitar a gestão do ensino, permitindo que **professores** cadastrem aulas, exercícios, vídeos explicativos e materiais de apoio, além de oferecer ferramentas para acompanhar de perto o desenvolvimento de cada estudante. Para os **alunos**, o sistema entrega diversos recursos interativos focados em promover um aprendizado mais dinâmico e eficiente.

---

## 🛠 Tecnologias Utilizadas

O projeto foi desenvolvido utilizando as seguintes tecnologias:

- **Back-end:** [PHP](https://www.php.net/) e [Laravel](https://laravel.com/)
- **Front-end:** [JavaScript](https://developer.mozilla.org/pt-BR/docs/Web/JavaScript), HTML5 e CSS3
- **Banco de Dados:** [MySQL](https://www.mysql.com/)
- **Versionamento:** Git e GitHub

---

## ✨ Funcionalidades Principais

- [x] **Gestão de Conteúdo:** Cadastro de aulas, exercícios e materiais de apoio pelos professores.
- [x] **Acervo de Vídeos:** Suporte para inclusão de vídeos explicativos.
- [x] **Dashboard do Professor:** Ferramentas para acompanhamento do desenvolvimento e desempenho do aluno.
- [x] **Área do Aluno:** Interface interativa para acesso aos materiais e resolução de exercícios.
- [x] **Autenticação:** Sistema de login seguro para diferenciação de níveis de acesso (Professor/Aluno).

---

## ⚙️ Como Executar o Projeto

### Pré-requisitos

Antes de começar, você vai precisar ter instalado em sua máquina as seguintes ferramentas:
[Git](https://git-scm.com), [Composer](https://getcomposer.org/) e um servidor local como [XAMPP](https://www.apachefriends.org/pt_br/index.html) ou Docker.

### 🎲 Rodando a Aplicação

```bash
# Clone este repositório
$ git clone [https://github.com/AncelmoSilva10/Devventure-TCC](https://github.com/AncelmoSilva10/Devventure-TCC.git)

# Acesse a pasta do projeto no terminal/cmd
$ cd NOME-DO-REPOSITORIO

# Instale as dependências do PHP
$ composer install

# Instale as dependências do Front-end
$ npm install && npm run dev

# Crie o arquivo .env
$ cp .env.example .env

# Gere a chave da aplicação
$ php artisan key:generate

# Rode as migrações do banco de dados (Certifique-se de ter configurado o .env)
$ php artisan migrate

# Inicie o servidor local
$ php artisan serve
