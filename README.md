# 🛠️ BackEnd-AppPonto

[![Made with PHP](https://img.shields.io/badge/Made%20with-PHP-8892BF?style=for-the-badge&logo=php)](https://www.php.net/)
[![Made with Laravel](https://img.shields.io/badge/Made%20with-Laravel-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com/)
[![Database: MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge&logo=mysql)](https://www.mysql.com/)

> Backend de um sistema de **registro de ponto**, desenvolvido com **Laravel**. Fornece APIs para gerenciar empregadores, funcionários, batidas de ponto e relatórios.

---

## 🚀 Funcionalidades

### 👤 Empregadores

- ➕ Adicionar empregador: `nome`, `e-mail`, `CPF`, `empresa`, `senha (hash)`
- 🔍 Exibir empregador: por `e-mail` e `senha`

### 👥 Funcionários

- ➕ Cadastrar funcionário: `nome`, `CPF`, `e-mail`, `senha`, `empregador`, `data contratação`, `função`
- 🔍 Ver funcionário: por `e-mail` e `senha`
- 📋 Listar todos os funcionários de um empregador
- 🔎 Buscar funcionário por `e-mail`
- ❌ Excluir funcionário por `e-mail` e `CPF`

### ⏱️ Registro de Ponto

- 🕒 Bater ponto com `e-mail`, `data`, `hora`, `CPF`

### 📈 Relatórios

- ⚠️ Placeholder - precisa ser implementado

---

## 🧰 Stack Tecnológica

| 🧩 Tecnologia | 🚀 Função |
|--------------|----------|
| ![Laravel](https://img.shields.io/badge/Laravel-Framework-red?logo=laravel&style=flat-square) | Backend principal |
| ![PHP](https://img.shields.io/badge/PHP-8+-8892BF?logo=php&style=flat-square) | Linguagem usada |
| ![MySQL](https://img.shields.io/badge/MySQL-BD-005C84?logo=mysql&style=flat-square) | Banco de dados relacional |
| ![Composer](https://img.shields.io/badge/Composer-PHP%20Deps-885630?logo=composer&style=flat-square) | Gerenciador de dependências |
| ![Postman](https://img.shields.io/badge/Postman-Testes-FF6C37?logo=postman&style=flat-square) | Teste das APIs |

---

## 🧱 Estrutura do Projeto

### 📁 Diretórios

| Pasta | Descrição |
|-------|-----------|
| `app/Http/Controllers` | Controladores da API |
| `app/Models` | Modelos Eloquent: `Empregador`, `Funcionario`, `TabelaPonto`, `User` |
| `routes/` | Arquivos de rotas: `api.php`, `web.php`, etc |
| `database/` | Migrações, seeds e factories |
| `public/` | Entrada do app (`index.php`) |
| `Mysql querys/` | Scripts SQL prontos pra uso |

---

## 📦 Modelos

| Modelo | Tabela | Campos |
|--------|--------|--------|
| `Empregador` | `empregadores` | `nome`, `email`, `cpf`, `empresa`, `senha` |
| `Funcionario` | `funcionarios` | `cpf`, `email`, `nome`, `senha`, `empregador`, `data_contratacao`, `funcao` |
| `TabelaPonto` | (não especificado) | `cpf`, `email`, `data`, `hora` |
| `User` | `users` | Padrão do Laravel |

---

## 🌐 Rotas da API

| Método | Endpoint | Ação |
|--------|----------|------|
| `POST` | `/api/addEmpregador` | Criar empregador |
| `GET` | `/api/exibirEmpregador` | Ver empregador |
| `POST` | `/api/addFuncionario` | Criar funcionário |
| `GET` | `/api/exibirFuncionario` | Ver funcionário |
| `POST` | `/api/enviarcolaborador` | Listar todos funcionários |
| `POST` | `/api/ColaboradorExpecifico` | Buscar funcionário específico |
| `POST` | `/api/ReceberPonto` | Registrar ponto |
| `POST` | `/api/GerarRelatorio` | Gerar relatório (placeholder) |
| `POST` | `/api/ExcluirColaborador` | Deletar funcionário |

---

## 🛠️ Como Rodar

```bash
# Clonar o repositório
git clone https://github.com/Kcarlos-dev/BackEnd-AppPonto.git
cd BackEnd-AppPonto

# Instalar dependências
composer install

# Configurar ambiente
cp .env.example .env
# (edite com seus dados do banco)

# Gerar chave
php artisan key:generate

# Rodar migrações
php artisan migrate

# Start no servidor
php artisan serve
