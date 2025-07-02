<div class="mfw-streaming-container mfw-template-subscription-most" data-base-url="<?= mfw_url('/') ?>">  <!-- /where-to-watch/advanced/subscription-most -->

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="text-primary mb-0" style="font-weight: bold;">
            🧱 Build Your Movie List
        </h1>

        <a href="<?= mfw_url('/where-to-watch/advanced') ?>" class="btn btn-outline-secondary">
            ← Back to advanced options
        </a>
    </div>

    <p class="mb-3">Build your custom movie list (max 30) to analyze which platform you can subscribe to watch the most of them.</p>

    <!-- 🔍 Search form -->
    <form id="tmdb-search-form" class="input-group mb-3">
        <input type="text" class="form-control" id="movie-title-input" placeholder="Enter movie title..." required>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <!-- ✅ TMDb result -->
    <div id="search-result" class="alert d-none"></div>

    <!-- 🗂️ Movie list -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <h3 class="mb-0">🎬 Titles Added (<span id="movie-count">0</span>/30)</h3>
        <button id="analyze-button" class="btn btn-success btn-lg d-none">
            🔎 Search Subscription Platforms
        </button>
    </div>

    <div class="d-flex align-items-center gap-2 mt-3">
        <label for="regionDropdown" class="form-label mb-0">🌍 Region:</label>

        <div class="dropdown">
            <button title="Select region" class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" id="regionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <img id="selected-flag" width="25" alt="Flag">
                <span id="selected-emoji"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="regionDropdown">
                <li><a class="dropdown-item d-flex align-items-center gap-2" href="#" data-region="ES" data-emoji="🇪🇸"><img src="https://flagcdn.com/h20/es.png" width="25" alt="ES"> 🇪🇸</a></li>
                <li><a class="dropdown-item d-flex align-items-center gap-2" href="#" data-region="US" data-emoji="🇺🇸"><img src="https://flagcdn.com/h20/us.png" width="25" alt="US"> 🇺🇸</a></li>
                <li><a class="dropdown-item d-flex align-items-center gap-2" href="#" data-region="GB" data-emoji="🇬🇧"><img src="https://flagcdn.com/h20/gb.png" width="25" alt="GB"> 🇬🇧</a></li>
            </ul>
            <input type="hidden" name="region" id="regionInput" value="<?= htmlspecialchars($region) ?>">
        </div>
    </div>

    <ul id="movie-list" class="list-group mt-2"></ul>
    
    <!-- 📊 Subscriptions result -->
    <div id="subscription-result-container" class="mt-4"></div>

</div>
