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

<!-- Exibição de Mensagens de Alerta e Notificações -->
<?php if (!empty($mensagem)): ?>
    <div class="mensagem text-dark fw-bold mb-4 shadow-sm" role="alert">
        <?= e($mensagem) ?>
    </div>
<?php endif; ?>

<!-- Tabela de Gerenciamento de Usuários -->
<section class="painel">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #2563eb; padding-bottom: 8px; margin-bottom: 20px;">
        <h2 style="color: #ffffff; margin: 0;">Gerenciamento de Usuários</h2>
        <a href="index.php?acao=cadastrar_usuario" class="botao-secundario" style="background: #16a34a;">+ Cadastrar Novo Usuário</a>
    </div>

    <div class="tabela-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Nível (Papel)</th>
                    <th style="text-align: right; padding-right: 20px;">Ações</th>
                </tr>
            </thead>

            <tbody>
            <?php if (empty($usuarios)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px 0;">
                        Nenhum usuário cadastrado.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($usuarios as $usr): ?>
                    <tr>
                        <td><code style="color: #93c5fd; background: #0f172a; padding: 3px 6px; border-radius: 4px;"><?= (int) $usr['id'] ?></code></td>
                        <td><strong style="color: #f8fafc;"><?= e($usr['nome']) ?></strong></td>
                        <td><?= e($usr['email']) ?></td>
                        <td>
                            <?php if (($usr['papel'] ?? '') === 'admin'): ?>
                                <span class="status" style="background: #1d4ed8; color: #ffffff;">ADMIN</span>
                            <?php else: ?>
                                <span class="status normal">CLIENTE</span>
                            <?php endif; ?>
                        </td>
                        <td class="acoes" style="text-align: right; padding-right: 20px;">
                            <a href="index.php?acao=editar_usuario&id=<?= (int) $usr['id'] ?>" style="color: #f39c12; font-weight: 600;">
                                Editar
                            </a>

                            <!-- Impede o admin de renderizar o botão para excluir a própria conta -->
                            <?php if ((int)$usr['id'] !== (int)($_SESSION['usuario_id'] ?? 0)): ?>
                                <form action="index.php?acao=excluir_usuario" method="POST" style="display: inline;" onsubmit="return confirm('Deseja realmente excluir este usuário?')">
                                    <input type="hidden" name="id" value="<?= (int) $usr['id'] ?>">
                                    <button type="submit" class="botao-excluir" style="font-weight: 600;">
                                        Excluir
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>