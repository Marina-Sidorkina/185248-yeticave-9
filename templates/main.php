<section class="promo">
  <h2 class="promo__title">Нужен стафф для катки?</h2>
  <p class="promo__text">
    На нашем интернет-аукционе ты найдёшь самое эксклюзивное
    сноубордическое и горнолыжное снаряжение.
  </p>
  <ul class="promo__list">
  <?php foreach ($categories as $value): ?>
    <li class="promo__item promo__item--<?=htmlspecialchars($value["char_code"]); ?>">
      <a class="promo__link"
        href="pages/all-lots.html"><?=htmlspecialchars($value["title"]); ?>
      </a>
    </li>
  <?php endforeach; ?>
  </ul>
</section>
<section class="lots">
  <div class="lots__header">
    <h2>Открытые лоты</h2>
  </div>
  <ul class="lots__list">
  <?php foreach ($lots as $value): ?>
    <li class="lots__item lot">
      <div class="lot__image">
        <img src="<?=htmlspecialchars($value["url"]); ?>" width="350" height="260" alt="">
      </div>
      <div class="lot__info">
        <span class="lot__category"><?=htmlspecialchars($value["category"]); ?></span>
        <h3 class="lot__title">
          <a class="text-link"
            href="lot.php?lot_id=<?=htmlspecialchars($value["id"]); ?>">
            <?=htmlspecialchars($value["title"]); ?>
          </a>
        </h3>
        <div class="lot__state">
          <div class="lot__rate">
            <span class="lot__amount">Стартовая цена</span>
            <span class="lot__cost">
              <?=htmlspecialchars(format_price($value["price"])); ?><b class='rub'>р</b>
            </span>
          </div>
          <div class="lot__timer timer
            <?=get_time_params(htmlspecialchars($value["expirationDate"]))["expiration_mark"]; ?>">
            <?=get_time_params(htmlspecialchars($value["expirationDate"]))["hours_left"]; ?> ч
            <?=get_time_params(htmlspecialchars($value["expirationDate"]))["minutes_left"]; ?> мин
          </div>
        </div>
      </div>
    </li>
  <?php endforeach; ?>
  </ul>
</section>
