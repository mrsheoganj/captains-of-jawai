<?php
/** Vars: $total, $per, $page */
$pages = (int) ceil($total / max(1, $per));
if ($pages > 1):
    $qs = $_GET; ?>
<nav class="pager">
  <?php for ($n = 1; $n <= $pages; $n++): $qs['page'] = $n; ?>
    <?php if ($pages > 12 && $n > 3 && $n < $pages - 2 && abs($n - $page) > 2): if ($n === 4 || $n === $pages - 3): ?><span>…</span><?php endif; continue; endif; ?>
    <a class="<?= $n === $page ? 'is-active' : '' ?>" href="?<?= e(http_build_query($qs)) ?>"><?= $n ?></a>
  <?php endfor; ?>
</nav>
<?php endif; ?>
