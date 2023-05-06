<nav class="navbar navbar-expand-lg p-4" style="background-color: #E8B722;">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= base_url('/'); ?>">
        <img src="<?= base_url('assets/images/screen.png'); ?>" alt="image d'un laptop" width="100px"/>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?= base_url('/'); ?>">Page d'accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('/helper_form'); ?>">Helper Form</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Outil générateur de landings</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Outil générateur d'offres</a>
        </li>
      </ul>
    </div>
  </div>
</nav>