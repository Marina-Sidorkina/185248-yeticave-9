<section class="rates container">
  <h2>Мои ставки</h2>
  <table class="rates__list">
  <?php foreach ($user_bets as $value): ?>
    <tr class="rates__item
    <?=(check_bet_date_and_winner($value["expired_at"], $value["winner_id"])) ?
       "rates__item--win" : ""?>">
      <td class="rates__info">
        <div class="rates__img">
          <img src="<?=htmlspecialchars($value["url"]); ?>" width="54" height="40" alt="Сноуборд">
        </div>
        <div>
          <h3 class="rates__title">
            <a href="lot.php?lot_id=<?=htmlspecialchars($value["lot_id"]); ?>"><?=htmlspecialchars($value["lot_title"]); ?></a>
          </h3>
          <?php if (check_bet_date_and_winner($value["expired_at"], $value["winner_id"])) : ?>
          <p><?=htmlspecialchars($value["contacts"]); ?></p>
          <?php endif; ?>
        </div>
      </td>
      <td class="rates__category">
        <?=htmlspecialchars($value["category"]); ?>
      </td>
      <td class="rates__timer">
      <?php if (strtotime(htmlspecialchars($value["expired_at"])) > time()
               and htmlspecialchars($value["winner_id"]) !== $_SESSION["user"]["id"]) : ?>
        <div class="timer <?=get_time_params(htmlspecialchars($value["expired_at"]))["expiration_mark"]; ?>">
          <?=get_time_params(htmlspecialchars($value["expired_at"]))["hours_left"]; ?>:
          <?=get_time_params(htmlspecialchars($value["expired_at"]))["minutes_left"]; ?>:
          <?=get_time_params(htmlspecialchars($value["expired_at"]))["seconds_left"]; ?>
        </div>
      <?php elseif (check_bet_date_and_winner($value["expired_at"], $value["winner_id"])) : ?>
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
        <?=get_formatted_time(htmlspecialchars($value["bet_date"])); ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </table>
</section>
