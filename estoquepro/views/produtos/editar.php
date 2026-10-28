<?php
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

<section class="painel">
    <h2>Editar Produto</h2>

    <form action="index.php?acao=atualizar" method="POST" class="formulario">
        <!-- Campo oculto com o ID do produto -->
        <input type="hidden" name="id" value="<?= (int) $produto['id'] ?>">

        <div class="campo">
            <label for="nome">Nome</label>
            <input id="nome" name="nome" maxlength="120" value="<?= e($produto['nome']) ?>" required>
        </div>

        <div class="campo">
            <label for="codigo">Código</label>
            <input id="codigo" name="codigo" maxlength="40" value="<?= e($produto['codigo']) ?>" required>
        </div>

        <div class="campo">
            <label for="preco">Preço (R$)</label>
            <input id="preco" name="preco" type="number"
                   min="0" max="99999999.99" step="0.01"
                   value="<?= e($produto['preco']) ?>" required>
        </div>

        <div class="campo">
            <label for="quantidade">Quantidade</label>
            <input id="quantidade" name="quantidade" type="number"
                   min="0" max="1000000"
                   value="<?= e($produto['quantidade']) ?>" required>
        </div>

        <div class="campo">
            <label for="estoque_minimo">Estoque mínimo</label>
            <input id="estoque_minimo" name="estoque_minimo" type="number"
                   min="0" max="1000000"
                   value="<?= e($produto['estoque_minimo']) ?>" required>
        </div>

        <button type="submit">Salvar Alterações</button>
        <a href="index.php" class="botao-secundario">Cancelar</a>
    </form>
</section>