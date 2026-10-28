<?php

class Produto {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Lista os produtos do usuário incluindo o nome da categoria vinculada (com busca opcional)
    public function listar(int $usuarioId, string $busca = ""): array {
        if ($busca === "") {
            $sql = "SELECT p.*, c.nome AS categoria_nome 
                    FROM produtos p 
                    LEFT JOIN categorias c ON p.categoria_id = c.id 
                    WHERE p.usuario_id = :usuario_id 
                    ORDER BY p.id DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([":usuario_id" => $usuarioId]);
            return $stmt->fetchAll();
        }

        $sql = "SELECT p.*, c.nome AS categoria_nome 
                FROM produtos p 
                LEFT JOIN categorias c ON p.categoria_id = c.id 
                WHERE p.usuario_id = :usuario_id 
                  AND (p.nome LIKE :busca OR p.codigo LIKE :busca OR c.nome LIKE :busca) 
                ORDER BY p.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":usuario_id" => $usuarioId,
            ":busca"      => "%$busca%"
        ]);

        return $stmt->fetchAll();
    }

    // Função resumo de estoque calculada para o usuário logado
    public function resumo(int $usuarioId): array|false {
        $sql = "SELECT
                    COUNT(*) AS total_produtos,
                    COALESCE(SUM(quantidade), 0) AS total_itens,
                    COALESCE(SUM(preco * quantidade), 0) AS valor_estoque,
                    COALESCE(SUM(
                        CASE
                            WHEN quantidade <= estoque_minimo THEN 1
                            ELSE 0
                        END
                    ), 0) AS estoque_baixo
                FROM produtos
                WHERE usuario_id = :usuario_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":usuario_id" => $usuarioId]);
        return $stmt->fetch();
    }

    // Busca o produto garantindo que pertença ao usuário logado
    public function buscarPorId(int $id, int $usuarioId): array|false {
        $sql = "SELECT p.*, c.nome AS categoria_nome 
                FROM produtos p 
                LEFT JOIN categorias c ON p.categoria_id = c.id 
                WHERE p.id = :id AND p.usuario_id = :usuario_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            "id"         => $id,
            "usuario_id" => $usuarioId
        ]);
        return $stmt->fetch();
    }

    // Função para criar o produto salvando com a categoria_id e o usuario_id
    public function criar(array $dados, int $usuarioId): bool {
        $sql = "INSERT INTO produtos (nome, codigo, preco, quantidade, estoque_minimo, categoria_id, usuario_id)
                VALUES (:nome, :codigo, :preco, :quantidade, :minimo, :categoria_id, :usuario_id)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            "nome"         => $dados["nome"],
            "codigo"       => $dados["codigo"] ?? null,
            "preco"        => $dados["preco"],
            "quantidade"   => $dados["quantidade"],
            "minimo"       => $dados["estoque_minimo"] ?? 0,
            "categoria_id" => !empty($dados["categoria_id"]) ? $dados["categoria_id"] : null,
            "usuario_id"   => $usuarioId
        ]);
    }

    // Função para atualizar o produto com a categoria
    public function atualizar(int $id, array $dados, int $usuarioId): bool {
        $sql = "UPDATE produtos
                SET nome = :nome,
                    codigo = :codigo,
                    preco = :preco,
                    quantidade = :quantidade,
                    estoque_minimo = :minimo,
                    categoria_id = :categoria_id
                WHERE id = :id AND usuario_id = :usuario_id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            "nome"         => $dados["nome"],
            "codigo"       => $dados["codigo"] ?? null,
            "preco"        => $dados["preco"],
            "quantidade"   => $dados["quantidade"],
            "minimo"       => $dados["estoque_minimo"] ?? 0,
            "categoria_id" => !empty($dados["categoria_id"]) ? $dados["categoria_id"] : null,
            "id"           => $id,
            "usuario_id"   => $usuarioId
        ]);
    }

    // Função para excluir produto garantindo o usuario_id no WHERE
    public function excluir(int $id, int $usuarioId): bool {
        $stmt = $this->pdo->prepare("DELETE FROM produtos WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->execute([
            "id"         => $id,
            "usuario_id" => $usuarioId
        ]);
        return $stmt->rowCount() > 0;
    }
}