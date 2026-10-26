<?php

class Categoria {
    public $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarPorUsuario($usuarioId) {
        $sql = "SELECT * FROM categorias WHERE usuario_id = :usuario_id ORDER BY nome ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':usuario_id' => $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar uma categoria específica do usuário para preencher o formulário de edição
    public function buscarPorId($id, $usuarioId) {
        $sql = "SELECT * FROM categorias WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':usuario_id' => $usuarioId
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criar($nome, $usuarioId) {
        $sql = "INSERT INTO categorias (nome, usuario_id) VALUES (:nome, :usuario_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nome' => $nome,
            ':usuario_id' => $usuarioId
        ]);
    }

    // Salvar o novo nome da categoria editada
    public function atualizar($id, $nome, $usuarioId) {
        $sql = "UPDATE categorias SET nome = :nome WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nome' => $nome,
            ':id' => $id,
            ':usuario_id' => $usuarioId
        ]);
    }

    public function excluir($id, $usuarioId) {
        $sql = "DELETE FROM categorias WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':usuario_id' => $usuarioId
        ]);
    }
}