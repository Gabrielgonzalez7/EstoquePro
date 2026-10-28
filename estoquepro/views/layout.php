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
    <!-- CSS do Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body style="background-color: #0f172a; color: #f8fafc;">

    <header class="cabecalho mb-4" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
        <div class="container d-flex align-items-center py-2">
            <a href="index.php" class="d-flex align-items-center text-decoration-none">
                <!-- Caminho para a logo -->
                <img src="public/img/logo.png" 
                     alt="EstoquePro Logo" 
                     style="height: 50px; width: auto; object-fit: contain;" 
                     class="me-3"
                     onerror="this.onerror=null; this.src='public/img/logo.jpg';">
                
                <h1 class="h3 mb-0 text-white fw-bold">EstoquePro</h1>
            </a>
        </div>
    </header>

    <main class="container">
        <!-- Barra do usuário logado (Exibida apenas se houver sessão ativa) -->
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <div style="
                background-color: #1e293b; 
                border: 1px solid #334155; 
                border-radius: 8px; 
                padding: 16px; 
                margin-bottom: 24px; 
                display: flex; 
                flex-wrap: wrap; 
                align-items: center; 
                justify-content: space-between; 
                gap: 12px;
            ">
                <!-- Lado Esquerdo: Identificação do Usuário -->
                <div style="display: flex; align-items: center; gap: 8px; font-size: 0.95rem; color: #f8fafc;">
                    <span style="color: #94a3b8;">Conectado como:</span>
                    <strong style="color: #ffffff;"><?= e($_SESSION['usuario_nome'] ?? 'Usuário') ?></strong>
                    <span style="
                        background-color: #2563eb; 
                        color: #ffffff; 
                        font-size: 0.75rem; 
                        font-weight: bold; 
                        padding: 3px 8px; 
                        border-radius: 12px; 
                        text-transform: uppercase;
                    ">
                        <?= e($_SESSION['usuario_papel'] ?? $_SESSION['usuario_perfil'] ?? 'CLIENTE') ?>
                    </span>
                </div>

                <!-- Lado Direito: Botões de Navegação Flexíveis -->
                <div style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
                    <a href="index.php?acao=listar" class="btn btn-outline-light btn-sm" style="white-space: nowrap;">Meus Produtos</a>
                    <a href="index.php?acao=listar_categorias" class="btn btn-outline-info btn-sm" style="white-space: nowrap;">Categorias</a>

                    <!-- Botão do Admin (Suporta tanto 'papel' quanto 'perfil') -->
                    <?php 
                        $perfil = strtolower($_SESSION['usuario_papel'] ?? $_SESSION['usuario_perfil'] ?? '');
                        if ($perfil === 'admin'): 
                    ?>
                        <a href="index.php?acao=listar_usuarios" class="btn btn-outline-primary btn-sm" style="white-space: nowrap;">Gerenciar Usuários</a>
                    <?php endif; ?>

                    <a href="index.php?acao=logout" class="btn btn-danger btn-sm" style="white-space: nowrap;">Sair</a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Conteúdo dinâmico carregado do Controller -->
        <?php if (isset($conteudo)): ?>
            <?= $conteudo ?>
        <?php endif; ?>
    </main>

    <footer class="rodape mt-5">
        <div class="container text-center text-muted py-3 border-top border-secondary">
            <p class="mb-0" style="color: #94a3b8;">&copy; <?= date('Y') ?> EstoquePro - Sistema de Gestão de Estoque</p>
        </div>
    </footer>
</body>
</html>