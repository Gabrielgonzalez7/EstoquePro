<?php
// Proteção contra erro de redeclaração da função e()
if (!function_exists('e')) {
    function e($valor): string {
        return htmlspecialchars(
            (string) $valor,
            ENT_QUOTES,
            "UTF-8"
        );
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EstoquePro</title>
    <!-- CSS do Bootstrap para estilizar os botões, badges e alertas -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header class="cabecalho mb-4">
        <div class="container d-flex align-items-center py-2">
            <a href="index.php" class="d-flex align-items-center text-decoration-none">
                <!-- Caminho dinâmico para a imagem da logo -->
                <img src="public/img/logo.png" 
                     alt="EstoquePro Logo" 
                     style="height: 60px; width: auto; object-fit: contain;" 
                     class="me-3"
                     onerror="this.onerror=null; this.src='public/img/logo.jpg';">
                
                <h1 class="h3 mb-0 text-white fw-bold">EstoquePro</h1>
            </a>
        </div>
    </header>

    <main class="container">
        <!-- Barra do usuário logado (Exibida apenas se houver sessão ativa) -->
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded border shadow-sm text-dark">
                <span class="text-dark">
                    Conectado como: <strong class="text-dark"><?= e($_SESSION['usuario_nome'] ?? 'Usuário') ?></strong>
                    <span class="badge bg-primary ms-1"><?= e(strtoupper($_SESSION['usuario_papel'] ?? 'CLIENTE')) ?></span>
                </span>
                <div>
                    <!-- Botões de Navegação -->
                    <a href="index.php?acao=listar" class="btn btn-outline-secondary btn-sm me-2">Meus Produtos</a>
                    <a href="index.php?acao=listar_categorias" class="btn btn-outline-info btn-sm me-2">Categorias</a>

                    <!-- O botão de gerenciar usuários SÓ aparece se o usuário for Administrador -->
                    <?php if (($_SESSION['usuario_papel'] ?? '') === 'admin'): ?>
                        <a href="index.php?acao=listar_usuarios" class="btn btn-outline-primary btn-sm me-2">Gerenciar Usuários</a>
                    <?php endif; ?>

                    <a href="index.php?acao=logout" class="btn btn-danger btn-sm">Sair</a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Exibe o conteúdo dinâmico capturado pelo buffer do Controller -->
        <?php if (isset($conteudo)): ?>
            <?= $conteudo ?>
        <?php endif; ?>
    </main>

    <footer class="rodape mt-5">
        <div class="container text-center text-muted py-3 border-top">
            <p class="mb-0">&copy; <?= date('Y') ?> EstoquePro - Sistema de Gestão de Estoque</p>
        </div>
    </footer>
</body>
</html>