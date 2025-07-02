<hr class="my-4">
<h3 class="mb-3">📊 Subscription Platform Ranking</h3>
<?php if (empty($platformDetails)): ?>
    <div class="alert alert-warning">No platforms found for the selected titles.</div>
<?php else: ?>
    <div class="accordion" id="platformAccordion">
        <?php foreach ($platformDetails as $name => $info): 
            $id = md5($name); ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-<?= $id ?>">
                    <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse-<?= $id ?>"
                        aria-expanded="false"
                        aria-controls="collapse-<?= $id ?>">
                        📺 <?= htmlspecialchars($name) ?> (<?= count($info['movies']) ?> titles)
                    </button>
                </h2>
                <div id="collapse-<?= $id ?>" class="accordion-collapse collapse"
                    aria-labelledby="heading-<?= $id ?>" data-bs-parent="#platformAccordion">
                    <div class="accordion-body">
                        <ul class="mb-0">
                            <?php foreach ($info['movies'] as $movie): ?>
                                <li><strong><?= htmlspecialchars($movie['title']) ?></strong> (<?= htmlspecialchars($movie['year']) ?>)</li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="text-center mt-4">
    <a href="<?= mfw_url('/where-to-watch/advanced/subscription-most') . '?region=' . urlencode($region) ?>" class="btn btn-outline-primary">
        🧹 Clear and Start Over
    </a>
</div>