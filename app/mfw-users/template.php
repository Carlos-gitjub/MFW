<?php 
require_once __DIR__ . '/../mfw-url/helpers.php';
require_once __DIR__ . '/../mfw-security/functions.php';
?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <h2 class="mb-4">Crear cuenta</h2>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= mfw_url('/register') ?>">
      <div class="mb-3">
        <label for="username" class="form-label">Nombre de usuario</label>
        <input type="text" id="username" name="username" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" id="password" name="password" class="form-control" required>
      </div>

      <?php mfw_csrf_input(); ?>

      <button type="submit" class="btn btn-success w-100">Crear cuenta</button>
    </form>
  </div>
</div>
