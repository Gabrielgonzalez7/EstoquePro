<?php
require_once __DIR__ . '/../models/Categoria.php';

class CategoriaController {
    public $pdo;
    public $categoriaModel;

    public function __construct() {
        // Carrega o arquivo de configuração da raiz do projeto usando __DIR__
        if (file_exists(__DIR__ . '/../config.php')) {
            require __DIR__ . '/../config.php';
        }

        // Tenta usar a conexão global $pdo do config.php ou abre via PDO nativo
        global $pdo;
        if (isset($pdo)) {
            $this->pdo = $pdo;
        } else {
            // Ajuste o nome do banco e credenciais caso não use o $pdo global
            $this->pdo = new PDO("mysql:host=localhost;dbname=estoquepro;charset=utf8", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        $this->categoriaModel = new Categoria($this->pdo);
    }

    public function renderizar($view, $dados = []) {
        extract($dados);
        ob_start();
        require __DIR__ . "/../views/{$view}.php";
        $conteudo = ob_get_clean();
        require __DIR__ . '/../views/layout.php';
    }

    // Listagem das categorias do usuário logado
    public function index() {
        $usuarioId = $_SESSION['usuario_id'] ?? 0;
        $categorias = $this->categoriaModel->listarPorUsuario($usuarioId);
        $mensagem = $_SESSION['mensagem'] ?? '';
        unset($_SESSION['mensagem']);

        $this->renderizar('categorias/index', [
            'categorias' => $categorias,
            'mensagem' => $mensagem
        ]);
    }

    // Cadastra categoria
    public function cadastrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? '');
            $usuarioId = $_SESSION['usuario_id'] ?? 0;

            if (!empty($nome)) {
                $this->categoriaModel->criar($nome, $usuarioId);
                $_SESSION['mensagem'] = "Categoria criada com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Informe o nome da categoria.";
            }
        }
        header("Location: index.php?acao=listar_categorias");
        exit;
    }

    // Tela e Ação de Editar Categoria
    public function editar() {
        $usuarioId = $_SESSION['usuario_id'] ?? 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $nome = trim($_POST['nome'] ?? '');

            if ($id > 0 && !empty($nome)) {
                $this->categoriaModel->atualizar($id, $nome, $usuarioId);
                $_SESSION['mensagem'] = "Categoria atualizada com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Informe o nome da categoria.";
            }
            header("Location: index.php?acao=listar_categorias");
            exit;
        }

        $id = $_GET['id'] ?? 0;
        $categoria = $this->categoriaModel->buscarPorId($id, $usuarioId);

        if (!$categoria) {
            $_SESSION['mensagem'] = "Categoria não encontrada.";
            header("Location: index.php?acao=listar_categorias");
            exit;
        }

        $this->renderizar('categorias/editar', [
            'categoria' => $categoria
        ]);
    }

    // Exclui categoria
    public function excluir() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $usuarioId = $_SESSION['usuario_id'] ?? 0;

            if ($id > 0) {
                $this->categoriaModel->excluir($id, $usuarioId);
                $_SESSION['mensagem'] = "Categoria excluída com sucesso!";
            }
        }
        header("Location: index.php?acao=listar_categorias");
        exit;
    }
}