<div class="mfw-streaming-container mfw-template-main">  <!-- /where-to-watch -->

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="text-primary mb-0 mfw-template-main-title">
            🎬 Where to watch?
        </h1>
        <a href="<?= mfw_url('/where-to-watch/advanced') ?>"
        class="btn btn-outline-secondary btn-lg"
        title="Go to advanced options">
        ⚙️ Advanced options
        </a>
    </div>

    <form method="GET" action="<?= mfw_url('/where-to-watch') ?>" class="mb-4">
        <div class="input-group">
            <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" placeholder="e.g. Inception"
                   class="form-control form-control-lg" required>
            <button type="submit" class="btn btn-primary btn-lg" id="movie-search-button">Search</button>
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

    </form>

    <?php if (!empty($results)): ?>
        <div class="result bg-white border p-4 rounded shadow-sm">

            <h2 class="text-success"><?= htmlspecialchars($results['movie']['title']) ?> 
                <small class="text-muted">(<?= htmlspecialchars((string) $results['movie']['year']) ?>)</small>
            </h2>
            <h4 class="mt-3">Available on:</h4>
            <ul class="list-group mt-2">
                <?php
                $shown = [];
                foreach ($results['sources'] as $source):
                    // Only show the combination of name, type and format once (i.e. no duplicates)
                    $key = $source['name'] . '|' . $source['type'] . '|' . $source['format'];
                    if (isset($shown[$key])) continue;
                    $shown[$key] = true;

                    $type = [
                        'sub' => 'Subscription',
                        'rent' => 'Rent',
                        'buy' => 'Buy'
                    ][$source['type']] ?? ucfirst($source['type']);

                    $price = ($source['type'] === 'sub') ? '(subscription)' : ($source['price']);
                    $url = $source['web_url'] ?? '#';
                ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= htmlspecialchars($source['name']) ?></strong>
                            <span class="badge bg-secondary ms-2"><?= htmlspecialchars($source['format']) ?></span>
                            &nbsp;
                            <a href="<?= htmlspecialchars($url) ?>" target="_blank" rel="noopener noreferrer"
                            class="small text-decoration-underline text-primary">
                                Link <i class="fa-solid fa-up-right-from-square ms-1 mfw-link-icon"></i>
                            </a>
                        </div>
                        <span class="text-muted"><?= $type ?> — <?= $price ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php elseif ($title !== ''): ?>
        <div class="alert alert-warning mt-4">
            <strong>No results found for:</strong> <?= htmlspecialchars($title) ?>
        </div>
    <?php endif; ?>
</div>
