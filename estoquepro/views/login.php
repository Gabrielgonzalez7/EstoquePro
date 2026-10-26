<div class="row justify-content-center my-5">
    <div class="col-md-5">
        <div class="card shadow border-0" style="background-color: #1e293b; color: #ffffff;">
            <div class="card-header bg-primary text-white text-center py-3">
                <h3 class="mb-0">EstoquePro - Entrar</h3>
            </div>
            
            <div class="card-body p-4">
                <!-- Exibição de Mensagens de Alerta ou Erro -->
                <?php if (!empty($mensagem)): ?>
                    <div class="alert alert-danger text-dark fw-bold mb-3" role="alert">
                        <?= e($mensagem) ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?acao=autenticar" method="POST">
                    <!-- Campo de E-mail de Acesso -->
                    <div class="campo mb-3">
                        <label for="email" style="color: #ffffff; font-weight: 600; display: block; margin-bottom: 5px;">E-mail de Acesso</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="seu@email.com" required style="background-color: #ffffff; color: #0f172a;">
                    </div>

                    <!-- Campo de Senha -->
                    <div class="campo mb-3">
                        <label for="senha_login" style="color: #ffffff; font-weight: 600; display: block; margin-bottom: 5px;">Senha</label>
                        <input type="password" id="senha_login" name="senha" class="form-control" placeholder="••••••" required style="background-color: #ffffff; color: #0f172a;">
                        
                        <!-- Opção de Mostrar/Ocultar Senha -->
                        <div style="margin-top: 8px; display: flex; align-items: center; gap: 6px;">
                            <input type="checkbox" id="ver_senha_login" onclick="toggleSenha('senha_login')" style="width: auto; cursor: pointer;">
                            <label for="ver_senha_login" style="color: #cbd5e1; font-size: 13px; cursor: pointer; user-select: none; margin: 0;">Mostrar senha</label>
                        </div>
                    </div>

                    <!-- Botão de Login -->
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">Acessar Sistema</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script em JS para alternar a visibilidade da senha -->
<script>
function toggleSenha(idCampo) {
    const campo = document.getElementById(idCampo);
    if (campo) {
        campo.type = (campo.type === "password") ? "text" : "password";
    }
}
</script>