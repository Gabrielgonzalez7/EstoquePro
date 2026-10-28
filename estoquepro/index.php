<?php
session_start();

require_once 'config/database.php';
require_once 'models/Produto.php';
require_once 'models/Usuario.php';
require_once 'controllers/ProdutoController.php';
require_once 'controllers/UsuarioController.php';

if (!function_exists('e')) {
    function e(string $str): string {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}

$produtoModel = new Produto($pdo);
$produtoController = new ProdutoController($produtoModel);
$usuarioController = new UsuarioController($pdo);

$acao = $_GET['acao'] ?? 'listar';

switch ($acao) {
    // --- ROTAS DE AUTENTICAÇÃO ---
    case 'login':
        $usuarioController->login();
        break;
    case 'autenticar':
        $usuarioController->autenticar();
        break;
    case 'logout':
        $usuarioController->logout();
        break;

    // --- ROTAS DE GESTÃO DE USUÁRIOS (RESTRITO AO ADMIN) ---
    case 'listar_usuarios':
        $usuarioController->listar();
        break;
    case 'cadastrar_usuario':
        $usuarioController->cadastrar();
        break;
    case 'salvar_usuario':
        $usuarioController->salvar();
        break;
    case 'editar_usuario':
        $usuarioController->editar();
        break;
    case 'atualizar_usuario':
        $usuarioController->atualizar();
        break;
    case 'excluir_usuario':
        $usuarioController->excluir();
        break;

    // --- ROTAS DE GESTÃO DE PRODUTOS (CLIENTE / ADMIN) ---
    case 'criar':
        $produtoController->criar();
        break;
    case 'editar':
        $produtoController->editar();
        break;
    case 'atualizar':
        $produtoController->atualizar();
        break;
    case 'excluir':
        $produtoController->excluir();
        break;
        case 'listar_categorias':
        require_once 'controllers/CategoriaController.php';
        $controller = new CategoriaController();
        $controller->index();
        break;
        case 'cadastrar_categoria':
        require_once 'controllers/CategoriaController.php';
        $controller = new CategoriaController();
        $controller->cadastrar();
        break;
        case 'excluir_categoria':
        require_once 'controllers/CategoriaController.php';
        $controller = new CategoriaController();
        $controller->excluir();
        break;
    default:
        $produtoController->index();
        break;
}