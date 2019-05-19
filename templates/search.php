<nav class="nav">
  <ul class="nav__list container">
  <?php foreach ($categories as $value): ?>
    <li class="nav__item">
      <a href="all-lots.html"><?=htmlspecialchars($value["title"]); ?></a>
    </li>
  <?php endforeach; ?>
  </ul>
</nav>
<div class="container">
  <section class="lots">
    <h2>Результаты поиска по запросу «<span><?=htmlspecialchars($search); ?></span>»</h2>
    <ul class="lots__list">
    <?php foreach ($lots as $value): ?>
      <li class="lots__item lot">
        <div class="lot__image">
          <img src="<?=htmlspecialchars($value["url"]); ?>" width="350" height="260" alt="">
        </div>
        <div class="lot__info">
          <span class="lot__category"><?=htmlspecialchars($value["category"]); ?></span>
          <h3 class="lot__title"><a class="text-link" href="lot.php?lot_id=<?=htmlspecialchars($value["id"]); ?>"><?=htmlspecialchars($value["title"]); ?></a></h3>
          <div class="lot__state">
            <div class="lot__rate">
              <span class="lot__amount"><?=get_lot_amount_block_text($value["id"]); ?></span>
              <span class="lot__cost"><?=htmlspecialchars(format_price($value["price"])); ?><b class='rub'>р</b></span>
            </div>
            <div class="lot__timer timer <?=get_time_params($value["expirationDate"])["expiration_mark"]; ?>">
              <?=get_time_params($value["expirationDate"])["hours_left"]; ?> ч <?=get_time_params($value["expirationDate"])["minutes_left"]; ?> мин
            </div>
          </div>
        </div>
      </li>
    <?php endforeach; ?>
    </ul>
  </section>
  <ul class="pagination-list">
    <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
    <li class="pagination-item pagination-item-active"><a>1</a></li>
    <li class="pagination-item"><a href="#">2</a></li>
    <li class="pagination-item"><a href="#">3</a></li>
    <li class="pagination-item"><a href="#">4</a></li>
    <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
  </ul>
</div>
