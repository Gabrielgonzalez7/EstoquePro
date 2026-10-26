<div class="card my-4">
    <div class="card-header">
        <h2>Cadastrar Novo Usuário / Cliente</h2>
    </div>
    <div class="card-body">
        <?php if (!empty($mensagem)): ?>
            <div class="alert alert-info">
                <?= e($mensagem) ?>
            </div>
        <?php endif; ?>

        <form action="index.php?acao=salvar_usuario" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome Completo:</label>
                <input type="text" name="nome" id="nome" class="form-control" required placeholder="Ex: Banca 218">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail de Acesso:</label>
                <input type="email" name="email" id="email" class="form-control" required placeholder="Ex: pai@email.com">
            </div>

            <div class="campo">
                <label for="senha" style="color: #ffffff; font-weight: 600; background: transparent;">Senha Inicial</label>
                  <input type="password" id="senha_criar" name="senha" minlength="6" placeholder="••••••••" required style="background-color: #ffffff; color: #0f172a;">
            </div>

                    

            <div style="margin-top: 6px; display: flex; align-items: center; gap: 6px;"></div>        
                <input type="checkbox" id="ver_senha_criar" onclick="toggleSenha('senha_criar')" style="width: auto; cursor: pointer;">
                <label for="ver_senha_criar" style="color: #cbd5e1; font-size: 13px; cursor: pointer; user-select: none;">Mostrar senha</label>
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



            <div class="mb-3">
                <label for="papel" class="form-label">Perfil de Permissão:</label>
                <select name="papel" id="papel" class="form-control">
                    <option value="cliente">Cliente (Apenas gerencia seu próprio estoque)</option>
                    <option value="admin">Administrador (Acesso total)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Cadastrar Usuário</button>
            <a href="index.php?acao=listar" class="btn btn-secondary">Voltar ao Estoque</a>
        </form>
    </div>
</div>