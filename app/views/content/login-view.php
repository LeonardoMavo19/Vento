<div class="login-wrapper">
    <div class="login-background">
        <div class="login-shape login-shape-1"></div>
        <div class="login-shape login-shape-2"></div>
        <div class="login-shape login-shape-3"></div>
    </div>
    
    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">

                <i class="fas fa-th-large"></i>

            </div>

            <h1 class="login-title">VENTO</h1>
            <p class="login-subtitle">Ingresa tus credenciales para continuar</p>
        </div>

        <form class="login-form" action="" method="POST" autocomplete="off">
            <?php
                if(isset($_POST['login_usuario']) && isset($_POST['login_clave'])){
                    $insLogin->iniciarSesionControlador();
                }
            ?>

            <div class="field login-field">
                <div class="login-input-wrapper">
                    <span class="login-icon"><i class="fas fa-user"></i></span>
                    <input class="login-input" type="text" name="login_usuario" placeholder="Usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
                </div>
            </div>

            <div class="field login-field">
                <div class="login-input-wrapper">
                    <span class="login-icon"><i class="fas fa-lock"></i></span>
                    <input class="login-input" type="password" name="login_clave" placeholder="Contraseña" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
                </div>
            </div>

            <div class="field">
                <button type="submit" class="login-button">
                    <span>Iniciar Sesión</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>

            <div class="login-footer">
                <p><i class="fas fa-shield-alt"></i> Acceso seguro al sistema</p>
            </div>
        </form>
    </div>
</div>
