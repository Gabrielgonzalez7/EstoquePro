<?php

require_once 'models/Usuario.php';

class UsuarioController {
    private Usuario $usuarioModel;

    public function __construct(PDO $pdo) {
        $this->usuarioModel = new Usuario($pdo);
    }

    private function renderizar(string $view, array $dados = []): void {
        extract($dados);

        ob_start();
        require __DIR__ . "/../views/" . $view . ".php";
        $conteudo = ob_get_clean();

        require __DIR__ . "/../views/layout.php";
    }

    // Trava de segurança: Permite acesso apenas para usuários com papel 'admin'
    private function verificarPermissaoAdmin(): void {
        if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
            header('Location: index.php?acao=login&mensagem=' . urlencode('Efetue o login para continuar.'));
            exit;
        }

        if (($_SESSION['usuario_papel'] ?? '') !== 'admin') {
            header('Location: index.php?acao=listar&mensagem=' . urlencode('Acesso negado: Apenas administradores podem gerenciar usuários.'));
            exit;
        }
    }

    // --- AUTENTICAÇÃO E SESSÃO ---
    public function login(): void {
        if (isset($_SESSION['usuario_id'])) {
            header('Location: index.php?acao=listar');
            exit;
        }

        $this->renderizar("login", [
            "mensagem" => $_GET['mensagem'] ?? ''
        ]);
    }

    public function autenticar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?acao=login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        $usuario = $this->usuarioModel->buscarPorEmail($email);

        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            $_SESSION['usuario_id']    = $usuario['id'];
            $_SESSION['usuario_nome']  = $usuario['nome'];
            $_SESSION['usuario_papel'] = $usuario['papel'];

            header('Location: index.php?acao=listar&mensagem=' . urlencode('Bem-vindo(a), ' . $usuario['nome'] . '!'));
            exit;
        }

        header('Location: index.php?acao=login&mensagem=' . urlencode('E-mail ou senha inválidos.'));
        exit;
    }

    public function logout(): void {
        session_destroy();
        header('Location: index.php?acao=login&mensagem=' . urlencode('Sessão encerrada com sucesso.'));
        exit;
    }

    // --- MÉTODOS EXCLUSIVOS DO ADMINISTRADOR ---
    public function listar(): void {
        $this->verificarPermissaoAdmin();
        $usuarios = $this->usuarioModel->listarTodos();
        $this->renderizar("usuarios/index", [
            "usuarios" => $usuarios,
            "mensagem" => $_GET['mensagem'] ?? ''
        ]);
    }

    public function cadastrar(): void {
        $this->verificarPermissaoAdmin();
        $this->renderizar("usuarios/criar", [
            "mensagem" => $_GET['mensagem'] ?? ''
        ]);
    }

    public function salvar(): void {
        $this->verificarPermissaoAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?acao=cadastrar_usuario');
            exit;
        }

        $nome  = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $papel = $_POST['papel'] ?? 'cliente';

        if (empty($nome) || empty($email) || empty($senha)) {
            header('Location: index.php?acao=cadastrar_usuario&mensagem=' . urlencode('Preencha todos os campos obrigatórios.'));
            exit;
        }

        if ($this->usuarioModel->buscarPorEmail($email)) {
            header('Location: index.php?acao=cadastrar_usuario&mensagem=' . urlencode('E-mail já cadastrado.'));
            exit;
        }

        $this->usuarioModel->criar($nome, $email, $senha, $papel);
        header('Location: index.php?acao=listar_usuarios&mensagem=' . urlencode('Usuário cadastrado com sucesso!'));
        exit;
    }

    public function editar(): void {
        $this->verificarPermissaoAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $usuario = $this->usuarioModel->buscarPorId($id);

        if (!$usuario) {
            header('Location: index.php?acao=listar_usuarios&mensagem=' . urlencode('Usuário não encontrado.'));
            exit;
        }

        $this->renderizar("usuarios/editar", [
            "usuario" => $usuario,
            "mensagem" => $_GET['mensagem'] ?? ''
        ]);
    }

    public function atualizar(): void {
        $this->verificarPermissaoAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?acao=listar_usuarios');
            exit;
        }

        $id    = (int)($_POST['id'] ?? 0);
        $nome  = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $papel = $_POST['papel'] ?? 'cliente';
        $senha = !empty($_POST['senha']) ? $_POST['senha'] : null;

        $this->usuarioModel->atualizar($id, $nome, $email, $papel, $senha);
        header('Location: index.php?acao=listar_usuarios&mensagem=' . urlencode('Usuário atualizado com sucesso!'));
        exit;
    }

    public function excluir(): void {
        $this->verificarPermissaoAdmin();

        // Aceita exclusão via POST (formulário) ou GET (link com ID)
        $id = 0;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
        } else {
            $id = (int)($_GET['id'] ?? 0);
        }

        if ($id <= 0) {
            header('Location: index.php?acao=listar_usuarios&mensagem=' . urlencode('ID de usuário inválido.'));
            exit;
        }

        // Impede que o admin exclua a própria conta logada
        if ($id === (int)$_SESSION['usuario_id']) {
            header('Location: index.php?acao=listar_usuarios&mensagem=' . urlencode('Você não pode excluir a sua própria conta!'));
            exit;
        }

        $this->usuarioModel->excluir($id);
        header('Location: index.php?acao=listar_usuarios&mensagem=' . urlencode('Usuário excluído com sucesso!'));
        exit;
    }
}