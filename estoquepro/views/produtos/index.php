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

<!-- 1. Painel de Indicadores do Estoque -->
<section class="indicadores mb-4">
    <article class="card">
        <span>Produtos Cadastrados</span>
        <strong><?= e($resumo["total_produtos"] ?? 0) ?></strong>
    </article>

    <article class="card">
        <span>Unidades em Estoque</span>
        <strong><?= e($resumo["total_itens"] ?? 0) ?></strong>
    </article>

    <article class="card">
        <span>Valor do Estoque</span>
        <strong style="color: #4ade80;">
            R$ <?= number_format(
                (float) ($resumo["valor_estoque"] ?? 0),
                2, ",", "."
            ) ?>
        </strong>
    </article>

    <article class="card alerta">
        <span>Estoque Baixo</span>
        <strong><?= e($resumo["estoque_baixo"] ?? 0) ?></strong>
    </article>
</section>

<!-- 2. Formulário de Cadastro de Produto -->
<section class="painel mb-4">
    <h2 class="mb-3" style="color: #ffffff; border-bottom: 2px solid #2563eb; padding-bottom: 8px;">Cadastrar Produto</h2>

    <form action="index.php?acao=criar" method="POST" class="formulario">
        <div class="campo">
            <label for="nome" style="color: #ffffff; font-weight: 600; background: transparent;">Nome do Produto</label>
            <input id="nome" name="nome" maxlength="120" placeholder="Ex: Teclado Mecânico" required style="background-color: #ffffff; color: #0f172a;">
        </div>

        <div class="campo">
            <label for="codigo" style="color: #ffffff; font-weight: 600; background: transparent;">Código do Produto</label>
            <input id="codigo" name="codigo" maxlength="40" placeholder="Ex: TEC-001" required style="background-color: #ffffff; color: #0f172a;">
        </div>

        <div class="campo">
            <label for="preco" style="color: #ffffff; font-weight: 600; background: transparent;">Preço (R$)</label>
            <input id="preco" name="preco" type="number" min="0" max="99999999.99" step="0.01" placeholder="0,00" required style="background-color: #ffffff; color: #0f172a;">
        </div>

        <div class="campo">
            <label for="quantidade" style="color: #ffffff; font-weight: 600; background: transparent;">Quantidade Inicial</label>
            <input id="quantidade" name="quantidade" type="number" min="0" max="1000000" placeholder="0" required style="background-color: #ffffff; color: #0f172a;">
        </div>

        <div class="campo">
            <label for="estoque_minimo" style="color: #ffffff; font-weight: 600; background: transparent;">Estoque Mínimo</label>
            <input id="estoque_minimo" name="estoque_minimo" type="number" min="0" max="1000000" value="5" required style="background-color: #ffffff; color: #0f172a;">
        </div>

        <!-- CAMPO SELEÇÃO DE CATEGORIA -->
            <div class="campo">
        <label for="categoria_id" style="color: #ffffff; font-weight: 600; background: transparent;">Categoria</label>
        <select id="categoria_id" name="categoria_id" style="background-color: #ffffff; color: #0f172a; padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
            <option value="">-- Sem Categoria --</option>
            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= (int) $cat['id'] ?>">
                        <?= e($cat['nome']) ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>

        <div class="campo" style="grid-column: span 2; margin-top: 10px;">
            <button type="submit" style="background-color: #16a34a; border: none; font-weight: 600;">+ Cadastrar Produto</button>
        </div>
    </form>
</section>

<!-- 3. Tabela de Produtos Cadastrados com Pesquisa -->
<section class="painel">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
        <h2 style="color: #ffffff; margin: 0;">Produtos Cadastrados</h2>

        <!-- Formulário de Pesquisa -->
        <form method="GET" action="index.php" class="pesquisa" style="margin: 0; min-width: 300px;">
            <input type="hidden" name="acao" value="listar">

            <input type="search" name="busca"
                   placeholder="Buscar por nome, código ou categoria..."
                   value="<?= e($busca ?? '') ?>"
                   style="background-color: #ffffff; color: #0f172a;">

            <button type="submit">Pesquisar</button>
            <?php if (!empty($busca)): ?>
                <a href="index.php?acao=listar" class="botao-secundario" style="background: #475569;">Limpar</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="tabela-container">
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Produto</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Status</th>
                    <th style="text-align: right; padding-right: 20px;">Ações</th>
                </tr>
            </thead>

            <tbody>
            <?php if (empty($produtos)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #94a3b8; padding: 20px 0;">
                        Nenhum produto encontrado.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><code style="color: #93c5fd; background: #0f172a; padding: 3px 6px; border-radius: 4px;"><?= e($produto["codigo"]) ?></code></td>
                        <td><strong style="color: #f8fafc;"><?= e($produto["nome"]) ?></strong></td>
                        
                        <!-- COLUNA CATEGORIA NA TABELA -->
                        <td>
                            <?php if (!empty($produto['categoria_nome'])): ?>
                                <span class="badge bg-info text-dark" style="padding: 4px 8px; font-weight: 600;"><?= e($produto['categoria_nome']) ?></span>
                            <?php else: ?>
                                <span style="color: #94a3b8; font-style: italic;">Sem categoria</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            R$ <?= number_format(
                                (float) $produto["preco"],
                                2, ",", "."
                            ) ?>
                        </td>
                        <td><?= e($produto["quantidade"]) ?></td>
                        <td>
                            <?php if ($produto["quantidade"] <= $produto["estoque_minimo"]): ?>
                                <span class="status baixo">Estoque baixo</span>
                            <?php else: ?>
                                <span class="status normal">Normal</span>
                            <?php endif; ?>
                        </td>
                        <td class="acoes" style="text-align: right; padding-right: 20px;">
                            <a href="index.php?acao=editar&id=<?= (int) $produto["id"] ?>" style="color: #f39c12; font-weight: 600;">
                                Editar
                            </a>

                            <form action="index.php?acao=excluir" method="POST" style="display: inline;" onsubmit="return confirm('Deseja realmente excluir este produto?')">
                                <input type="hidden" name="id" value="<?= (int) $produto["id"] ?>">
                                <button type="submit" class="botao-excluir" style="font-weight: 600;">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>