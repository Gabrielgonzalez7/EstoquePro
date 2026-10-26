<?php
//protecao contra erro de redeclaracao da funcao (e)

if(!function_exists('e')){
    function e($valor): string{
        return htmlspecialchars($valor, ENT_QUOTES,"URF-8");
    }
}
?>
<!-- Exibição de Mensagens de Alerta e Notificações -->
<?php if (!empty($mensagem)): ?>
    <div class="mensagem text-dark fw-bold mb-4 shadow-sm" role="alert">
        <?= e($mensagem) ?>
    </div>
<?php endif; ?>

<section class="painel mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #2563eb; padding-bottom: 8px; margin-bottom: 20px;">
        <h2 style="color: #ffffff; margin: 0;">Editar Usuário</h2>
        <a href="index.php?acao=listar_usuarios" class="botao-secundario" style="background: #475569;">Voltar para Lista</a>
    </div>

    <form action="index.php?acao=atualizar_usuario" method="POST" class="formulario">
        <!-- Campo Oculto para enviar o ID do Usuário -->
        <input type="hidden" name="id" value="<?= (int) $usuario['id'] ?>">

        <div class="campo">
            <label for="nome" style="color: #ffffff; font-weight: 600; background: transparent;">Nome Completo</label>
            <input type="text" id="nome" name="nome" value="<?= e($usuario['nome'] ?? '') ?>" maxlength="120" required style="background-color: #ffffff; color: #0f172a;">
        </div>

        <div class="campo">
            <label for="email" style="color: #ffffff; font-weight: 600; background: transparent;">E-mail de Acesso</label>
            <input type="email" id="email" name="email" value="<?= e($usuario['email'] ?? '') ?>" maxlength="120" required style="background-color: #ffffff; color: #0f172a;">
        </div>

                <div class="campo">
            <label for="senha" style="color: #ffffff; font-weight: 600; background: transparent;">Nova Senha (Deixe em branco para manter a atual)</label>
            <input type="password" id="senha_editar" name="senha" minlength="6" placeholder="••••••••" style="background-color: #ffffff; color: #0f172a;">
            
            <!-- Opção de ver senha -->
            <div style="margin-top: 6px; display: flex; align-items: center; gap: 6px;">
                <input type="checkbox" id="ver_senha_editar" onclick="toggleSenha('senha_editar')" style="width: auto; cursor: pointer;">
                <label for="ver_senha_editar" style="color: #cbd5e1; font-size: 13px; cursor: pointer; user-select: none;">Mostrar senha</label>
            </div>
        </div>

        <script>
        function toggleSenha(idCampo) {
            const campo = document.getElementById(idCampo);
            if (campo.type === "password") {
                campo.type = "text";
            } else {
                campo.type = "password";
            }
        }
        </script>
        <div class="campo">
            <label for="papel" style="color: #ffffff; font-weight: 600; background: transparent;">Nível de Permissão</label>
            <select id="papel" name="papel" required style="background-color: #ffffff; color: #0f172a; padding: 12px; border-radius: 7px; border: 1px solid #cbd5e1;">
                <option value="cliente" <?= ($usuario['papel'] ?? '') === 'cliente' ? 'selected' : '' ?>>Cliente(Acesso apenas ao seu próprio estoque)</option>
                <option value="admin" <?= ($usuario['papel'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrador (Acesso total ao sistema e usuários)</option>
            </select>
        </div>

        <div class="campo" style="grid-column: span 2; margin-top: 10px;">
            <button type="submit" style="background-color: #2563eb; border: none; font-weight: 600; font-size: 16px;">
                Salvar Alterações
            </button>
        </div>
    </form>
</section>