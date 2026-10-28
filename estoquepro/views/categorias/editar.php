<?php
if (!function_exists('e')) {
    function e($valor): string {
        return htmlspecialchars((string) $valor, ENT_QUOTES, "UTF-8");
    }
}
?>

<section class="painel mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #2563eb; padding-bottom: 8px; margin-bottom: 20px;">
        <h2 style="color: #ffffff; margin: 0;">Editar Categoria</h2>
        <a href="index.php?acao=listar_categorias" class="btn btn-outline-secondary btn-sm">Voltar</a>
    </div>

    <!-- Formulário para Editar Categoria -->
    <form action="index.php?acao=editar_categoria" method="POST" class="row g-3 p-3 rounded" style="background-color: #1e293b;">
        <input type="hidden" name="id" value="<?= (int) $categoria['id'] ?>">

        <div class="col-md-9">
            <label for="nome" class="text-white fw-bold mb-1">Nome da Categoria</label>
            <input type="text" id="nome" name="nome" class="form-control" value="<?= e($categoria['nome']) ?>" required style="background-color: #ffffff; color: #0f172a;">
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100 fw-bold">Salvar Alterações</button>
        </div>
    </form>
</section>