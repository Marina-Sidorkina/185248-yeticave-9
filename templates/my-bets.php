<nav class="nav">
  <ul class="nav__list container">
  <?php foreach ($categories as $value): ?>
    <li class="nav__item">
      <a href="all-lots.html"><?=htmlspecialchars($value["title"]); ?></a>
    </li>
  <?php endforeach; ?>
  </ul>
</nav>
<section class="rates container">
  <h2>Мои ставки</h2>
  <table class="rates__list">
  <?php foreach ($user_bets as $value): ?>
    <tr class="rates__item">
      <td class="rates__info">
        <div class="rates__img">
          <img src="<?=$value["url"]; ?>" width="54" height="40" alt="Сноуборд">
        </div>
        <h3 class="rates__title">
          <a href="lot.php?lot_id=<?=$value["lot_id"]; ?>"><?=$value["lot_title"]; ?></a>
        </h3>
      </td>
      <td class="rates__category">
        <?=$value["category"]; ?>
      </td>
      <td class="rates__timer">
      <?php if (strtotime($value["expired_at"]) > time()) : ?>
        <div class="timer <?=get_time_params($value["expired_at"])["expiration_mark"]; ?>">
          <?=get_time_params($value["expired_at"])["hours_left"]; ?>:
          <?=get_time_params($value["expired_at"])["minutes_left"]; ?>:
          <?=get_time_params($value["expired_at"])["seconds_left"]; ?>
        </div>
      <?php elseif ($value["winner_id"] === $_SESSION["user"]["id"]) : ?>
        <div class="timer timer--win">
            Ставка выиграла
        </div>
      <?php else : ?>
        <div class="timer timer--end">
            Торги окончены
        </div>
      <?php endif; ?>
      </td>
      <td class="rates__price">
        <?=$value["price"]; ?> р
      </td>
      <td class="rates__time">
        <?=get_formatted_time($value["bet_date"]); ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </table>
</section>
