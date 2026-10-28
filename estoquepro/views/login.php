<?php
// Pega e limpa a mensagem de erro da sessão
$erro = $_SESSION['erro'] ?? null;
unset($_SESSION['erro']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>EstoquePro - Entrar</title>
    <style>
        body { background-color: #0f172a; color: #fff; font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: #1e293b; padding: 30px; border-radius: 8px; width: 320px; box-shadow: 0 4px 6px rgba(0,0,0,0.3); }
        .login-card h2 { text-align: center; color: #38bdf8; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-size: 0.9rem; }
        .form-group input { width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #334155; background: #0f172a; color: #fff; box-sizing: border-box; }
        .btn-entrar { width: 100%; padding: 10px; background: #2563eb; color: #fff; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .btn-entrar:hover { background: #1d4ed8; }
        .alert-erro { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; font-size: 0.85rem; margin-bottom: 15px; text-align: center; }
    </style>
</head>
<body>

<div class="login-card">
    <h2>EstoquePro - Entrar</h2>

    <!-- O alerta vermelho SÓ aparece se houver um erro real de digitação/autenticação -->
    <?php if ($erro): ?>
        <div class="alert-erro">
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <form action="index.php?acao=autenticar" method="POST">
        <div class="form-group">
            <label>E-mail de Acesso</label>
            <input type="email" name="email" placeholder="seu@email.com" required>
        </div>

        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="senha" placeholder="******" required>
        </div>

        <button type="submit" class="btn-entrar">Acessar Sistema</button>
    </form>
</div>

</body>
</html>