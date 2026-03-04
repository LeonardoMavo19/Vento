<nav class="navbar is-fixed-top has-shadow" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a id="btn-menu" role="button" class="navbar-burger" aria-label="menu" aria-expanded="false">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>

        <div class="navbar-item navbar-logo" aria-hidden="true">
            <span class="icon has-text-light"><i class="fas fa-th-large"></i></span>
            <span class="logo-text" style="margin-left:8px;color:#fff;font-weight:600;"><?php echo APP_NAME; ?></span>
        </div>

        <a class="navbar-item is-hidden-desktop">
            <strong><?php echo APP_NAME; ?></strong>
        </a>
    </div>

    <div class="navbar-menu">
        <div class="navbar-start"></div>

        <div class="navbar-end">
            <div class="navbar-item">
                <div style="display:flex;align-items:center;gap:0.6rem;">
                    <label class="has-text-white" style="font-size:0.95rem;margin-bottom:0;">Tasa del día</label>
                    <div class="field has-addons" style="margin:0;">
                        <div class="control">
                            <input id="exchange_rate_input" class="input is-small" type="number" step="0.01" min="0" value="<?php echo defined('EXCHANGE_RATE_USD_TO_BS')?EXCHANGE_RATE_USD_TO_BS:1.00; ?>" aria-label="Tasa USD">
                        </div>
                        <div class="control">
                            <button id="save_exchange_rate_btn" class="button is-small is-primary">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="navbar-item">
                <div class="buttons">
                    <a class="button is-light" href="<?php echo APP_URL."logOut/"; ?>" title="Cerrar sesión" aria-label="Cerrar sesión">
                        <span class="icon"><i class="fas fa-power-off"></i></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>