<?php

class Usuario {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Busca o usuário pelo e-mail para validar no login
    public function buscarPorEmail(string $email): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    // Busca o usuário pelo ID
    public function buscarPorId(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Lista todos os usuários cadastrados (para o painel do Admin)
    public function listarTodos(): array {
        $stmt = $this->pdo->query("SELECT id, nome, email, papel FROM usuarios ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    // Cadastra um novo usuário
    public function criar(string $nome, string $email, string $senha, string $papel = 'cliente'): bool {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nome, email, senha_hash, papel) 
                VALUES (:nome, :email, :senha_hash, :papel)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nome'       => $nome,
            ':email'      => $email,
            ':senha_hash' => $senhaHash,
            ':papel'      => $papel
        ]);
    }

    // Atualiza os dados do usuário (com opção de alterar senha apenas se for preenchida)
    public function atualizar(int $id, string $nome, string $email, string $papel, ?string $senha = null): bool {
        if (!empty($senha)) {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET nome = :nome, email = :email, papel = :papel, senha_hash = :senha_hash WHERE id = :id";
            $params = [
                ':nome'       => $nome,
                ':email'      => $email,
                ':papel'      => $papel,
                ':senha_hash' => $senhaHash,
                ':id'         => $id
            ];
        } else {
            $sql = "UPDATE usuarios SET nome = :nome, email = :email, papel = :papel WHERE id = :id";
            $params = [
                ':nome'  => $nome,
                ':email' => $email,
                ':papel' => $papel,
                ':id'    => $id
            ];
        }

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // Exclui um usuário do banco de dados
    public function excluir(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}