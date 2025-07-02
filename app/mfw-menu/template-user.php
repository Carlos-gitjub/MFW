<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="<?= mfw_url('/') ?>">MFW App</a>

    <div class="d-flex align-items-center">
      <a class="btn btn-outline-light me-2" href="<?= mfw_url('/dashboard') ?>">Dashboard</a>

      <div class="dropdown me-2">
  <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownUtilidades" data-bs-toggle="dropdown" aria-expanded="false">
    Utilities
  </button>
  <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="dropdownUtilidades">
    <li><a class="dropdown-item" href="<?= mfw_url('/where-to-watch') ?>">Movies - Streaming services</a></li>
  </ul>
</div>

      <a class="btn btn-danger" href="<?= mfw_url('/logout') ?>">Log out</a>
    </div>
  </div>
</nav>

