<nav class="nav">
  <ul class="nav__list container">
  <?php foreach ($categories as $value): ?>
    <li class="nav__item">
      <a href="all-lots.php?category=<?=htmlspecialchars($value["id"]); ?>"><?=htmlspecialchars($value["title"]); ?></a>
    </li>
  <?php endforeach; ?>
  </ul>
</nav>
