<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <h2 class="mb-4">Iniciar sesión</h2>
      <form method="POST" action="<?= mfw_url('/login/check') ?>">
        <div class="mb-3">
          <label for="username" class="form-label">Nombre de usuario</label>
          <input type="text" id="username" name="username" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Acceder</button>
      </form>
    </div>
  </div>
</div>
