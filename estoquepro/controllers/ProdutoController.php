<?php

require_once __DIR__ . '/../models/Categoria.php';

class ProdutoController
{
    private Produto $produto;

    public function __construct(Produto $produto)
    {
        $this->produto = $produto;
    }

    // Pega o ID do usuário logado na sessão (redireciona para login se não estiver autenticado)
    private function getUsuarioId(): int
    {
        if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
            header('Location: index.php?acao=login&mensagem=' . urlencode('Efetue o login para acessar o sistema.'));
            exit;
        }
        
        return (int) $_SESSION['usuario_id'];
    }

    private function renderizar(string $view, array $dados = []): void 
    {
        extract($dados);

        ob_start();
        require __DIR__ . "/../views/" . $view . ".php";
        $conteudo = ob_get_clean();

        require __DIR__ . "/../views/layout.php";
    }

    private function redirecionar(string $mensagem): never
    {
        header(
            "Location: index.php?mensagem=" .
            urlencode($mensagem)
        );
        exit;
    }

    private function validarDados(array $entrada): array
    {
        $nome = trim($entrada["nome"] ?? "");
        $codigo = trim($entrada["codigo"] ?? "");

        $precoTexto = $entrada["preco"] ?? null;
        $quantidadeTexto = $entrada["quantidade"] ?? null;
        $minimoTexto = $entrada["estoque_minimo"] ?? null;
        
        // Pega a categoria (opcional ou inteira)
        $categoriaId = filter_var($entrada["categoria_id"] ?? null, FILTER_VALIDATE_INT);

        $preco = is_string($precoTexto) || is_int($precoTexto)
            || is_float($precoTexto)
            ? filter_var($precoTexto, FILTER_VALIDATE_FLOAT)
            : false;

        $quantidade = is_string($quantidadeTexto)
            || is_int($quantidadeTexto)
            ? filter_var($quantidadeTexto, FILTER_VALIDATE_INT)
            : false;

        $minimo = is_string($minimoTexto)
            || is_int($minimoTexto)
            ? filter_var($minimoTexto, FILTER_VALIDATE_INT)
            : false;

        if (
            $nome === "" ||
            strlen($nome) > 120 ||
            $preco === false ||
            $preco < 0 ||
            $preco > 99999999.99 ||
            $quantidade === false ||
            $quantidade < 0 ||
            $quantidade > 1000000 ||
            $minimo === false ||
            $minimo < 0 ||
            $minimo > 1000000
        ) {
            throw new InvalidArgumentException(
                "Preencha os campos com valores válidos."
            );
        }

        return [
            "nome" => $nome,
            "codigo" => $codigo,
            "preco" => $preco,
            "quantidade" => $quantidade,
            "estoque_minimo" => $minimo,
            "categoria_id" => $categoriaId ?: null
        ];
    }

   public function index(): void
{
    $usuarioId = $this->getUsuarioId();
    $busca = trim($_GET["busca"] ?? "");

    // Busca a lista de categorias criadas pelo usuário/empresa conectado
    global $pdo;
    $categoriaModel = new Categoria($pdo);
    $categorias = $categoriaModel->listarPorUsuario($usuarioId);

    $this->renderizar("produtos/index", [
        "produtos" => $this->produto->listar($usuarioId, $busca),
        "resumo" => $this->produto->resumo($usuarioId),
        "categorias" => $categorias, // <-- PASSANDO AS CATEGORIAS PARA A VIEW
        "busca" => $busca,
        "mensagem" => $_GET["mensagem"] ?? ""
    ]);
}
    public function criar(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            http_response_code(405);
            exit("Método não permitido.");
        }

        try {
            $usuarioId = $this->getUsuarioId();
            $dados = $this->validarDados($_POST);
            
            $this->produto->criar($dados, $usuarioId);

            $this->redirecionar(
                "Produto cadastrado com sucesso!"
            );
        } catch (InvalidArgumentException $e) {
            $this->redirecionar($e->getMessage());
        } catch (PDOException $e) {
            error_log($e->getMessage());

            $mensagem = $e->getCode() === "23000"
                ? "O código do produto já existe."
                : "Erro ao cadastrar produto.";

            $this->redirecionar($mensagem);
        }
    }

    public function editar(): void
    {
        $usuarioId = $this->getUsuarioId();
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            http_response_code(400);
            exit("ID inválido.");
        }

        $produto = $this->produto->buscarPorId($id, $usuarioId);

        if (!$produto) {
            http_response_code(404);
            exit("Produto não encontrado.");
        }

        // Instancia e busca a lista de categorias do usuário logado para o <select> da View
        global $pdo;
        $categoriaModel = new Categoria($pdo);
        $categorias = $categoriaModel->listarPorUsuario($usuarioId);

        $this->renderizar("produtos/editar", [
            "produto" => $produto,
            "categorias" => $categorias,
            "mensagem" => ""
        ]);
    }

    public function atualizar(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            http_response_code(405);
            exit("Método não permitido.");
        }

        $usuarioId = $this->getUsuarioId();
        $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            http_response_code(400);
            exit("ID inválido.");
        }

        if (!$this->produto->buscarPorId($id, $usuarioId)) {
            http_response_code(404);
            exit("Produto não encontrado.");
        }

        try {
            $dados = $this->validarDados($_POST);
            $this->produto->atualizar($id, $dados, $usuarioId);

            $this->redirecionar(
                "Produto atualizado com sucesso!"
            );
        } catch (InvalidArgumentException $e) {
            $this->redirecionar($e->getMessage());
        } catch (PDOException $e) {
            error_log($e->getMessage());

            $mensagem = $e->getCode() === "23000"
                ? "O código já pertence a outro produto."
                : "Erro ao atualizar produto.";

            $this->redirecionar($mensagem);
        }
    }

    public function excluir(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            http_response_code(405);
            exit("Método não permitido.");
        }

        $usuarioId = $this->getUsuarioId();
        $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            $this->redirecionar("ID de produto inválido.");
        }

        try {
            $excluido = $this->produto->excluir($id, $usuarioId);

            $mensagem = $excluido
                ? "Produto excluído com sucesso!"
                : "Produto não encontrado.";

            $this->redirecionar($mensagem);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $this->redirecionar(
                "Não foi possível excluir o produto."
            );
        }
    }
}