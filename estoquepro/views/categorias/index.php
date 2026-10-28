<?php
if (!function_exists('e')) {
    function e($valor): string {
        return htmlspecialchars((string) $valor, ENT_QUOTES, "UTF-8");
    }
}
?>

<?php if (!empty($mensagem)): ?>
    <div class="mensagem text-dark fw-bold mb-4 shadow-sm" role="alert">
        <?= e($mensagem) ?>
    </div>
<?php endif; ?>

<section class="painel mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #2563eb; padding-bottom: 8px; margin-bottom: 20px;">
        <h2 style="color: #ffffff; margin: 0;">Minhas Categorias de Produtos</h2>
        <a href="index.php?acao=listar" class="btn btn-outline-secondary btn-sm">Voltar aos Produtos</a>
    </div>

    <!-- Formulário para Criar Categoria -->
    <form action="index.php?acao=cadastrar_categoria" method="POST" class="row g-3 mb-4 p-3 rounded" style="background-color: #1e293b;">
        <div class="col-md-9">
            <label for="nome" class="text-white fw-bold mb-1">Nome da Categoria</label>
            <input type="text" id="nome" name="nome" class="form-control" placeholder="Ex: Eletrônicos, Alimentos, Bebidas..." required style="background-color: #ffffff; color: #0f172a;">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100 fw-bold">+ Cadastrar Categoria</button>
        </div>
    </form>

    <!-- Tabela de Categorias da Empresa -->
    <div class="tabela-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome da Categoria</th>
                    <th style="text-align: right; padding-right: 20px;">Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($categorias)): ?>
                <tr>
                    <td colspan="3" style="text-align: center; color: #94a3b8; padding: 20px 0;">
                        Nenhuma categoria cadastrada para a sua empresa.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($categorias as $cat): ?>
                    <tr>
                        <td><code style="color: #93c5fd; background: #0f172a; padding: 3px 6px; border-radius: 4px;"><?= (int) $cat['id'] ?></code></td>
                        <td><strong style="color: #f8fafc;"><?= e($cat['nome']) ?></strong></td>
                        <td style="text-align: right; padding-right: 20px;">
                            <form action="index.php?acao=excluir_categoria" method="POST" style="display: inline;" onsubmit="return confirm('Deseja realmente excluir esta categoria?')">
                                <input type="hidden" name="id" value="<?= (int) $cat['id'] ?>">
                                <button type="submit" class="botao-excluir" style="font-weight: 600;">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>