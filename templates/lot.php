<nav class="nav">
  <ul class="nav__list container">
  <?php foreach ($categories as $value): ?>
    <li class="nav__item">
      <a href="all-lots.html"><?=htmlspecialchars($value["title"]); ?></a>
    </li>
  <?php endforeach; ?>
  </ul>
</nav>
<section class="lot-item container">
  <h2><?=htmlspecialchars($lot["title"]); ?></h2>
  <div class="lot-item__content">
    <div class="lot-item__left">
      <div class="lot-item__image">
        <img src="<?=htmlspecialchars($lot["url"]); ?>" width="730" height="548" alt="Сноуборд">
      </div>
      <p class="lot-item__category">
        Категория: <span><?=htmlspecialchars($lot["category"]); ?></span>
      </p>
      <p class="lot-item__description"><?=htmlspecialchars($lot["description"]); ?></p>
    </div>
    <div class="lot-item__right">
      <div class="lot-item__state">
        <div class="lot-item__timer timer
          <?=get_time_params(htmlspecialchars($lot["expirationDate"]))["expiration_mark"]; ?>">
          <?=get_time_params(htmlspecialchars($lot["expirationDate"]))["hours_left"]; ?>
          :<?=get_time_params(htmlspecialchars($lot["expirationDate"]))["minutes_left"]; ?>
        </div>
        <div class="lot-item__cost-state">
          <div class="lot-item__rate">
            <span class="lot-item__amount">Текущая цена</span>
            <span class="lot-item__cost"><?=htmlspecialchars($lot["price"]); ?></span>
          </div>
          <div class="lot-item__min-cost">
            Мин. ставка <span><?=htmlspecialchars($lot["step"] + $lot["price"]); ?> р</span>
          </div>
        </div>
        <?php if (get_bet_block_status($lot, $all_bets)) : ?>
        <form class="lot-item__form" action="" method="post"
          autocomplete="off" enctype="multipart/form-data">
          <p class="lot-item__form-item form__item
            <?=isset($errors["cost"]) ? "form__item--invalid" : ""?>">
            <label for="cost">Ваша ставка</label>
            <input id="cost" type="text" name="cost"
              placeholder="<?=htmlspecialchars($lot["step"] + $lot["price"]); ?>">
            <span class="form__error"><?=isset($errors["cost"]) ? htmlspecialchars($errors["cost"]) : "" ?></span>
          </p>
          <button type="submit" class="button">Сделать ставку</button>
        </form>
        <?php endif; ?>
      </div>
      <div class="history">
        <h3>История ставок (<span><?=htmlspecialchars(count($all_bets)); ?></span>)</h3>
        <table class="history__list">
          <?php foreach ($all_bets as $key => $value) : ?>
            <tr class="history__item">
              <td class="history__name"><?=htmlspecialchars($value["user"]); ?></td>
              <td class="history__price"><?=htmlspecialchars($value["price"]); ?></td>
              <td class="history__time"><?=get_formatted_time(htmlspecialchars($value["created_at"])); ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      </div>
    </div>
  </div>
</section>
