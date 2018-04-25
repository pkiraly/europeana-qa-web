<?php foreach ($data->histogram as $field => $hist) { ?>
  <div>
    <h2><?= str_replace('_', ':', str_replace('proxy_', '', $field)) ?></h2>
    <table class="histogram uniqueness-histogram" id="<?= $field; ?>-histogram">
      <tr>
        <td colspan="2"></td>
        <td colspan="5">Non-unique values</td>
      </tr>
      <tr>
        <td class="legend">stars</td>
        <?php for($i = 0; $i < count($hist); $i++) { ?>
          <td class="udata"><h2><?= $data->stars[$i]; ?></h2></td>
        <?php } ?>
      </tr>
      <tr>
        <td class="legend">range of occurences</td>
        <?php for($i = 0; $i < count($hist); $i++) { ?>
          <td class="udata"><?= $hist[$i]->label; ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td class="legend">nr of records</td>
        <?php for($i = 0; $i < count($hist); $i++) { ?>
          <td class="udata"><?= number_format($hist[$i]->count, 0, '.', ','); ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td class="legend">percentage</td>
        <?php for($i = 0; $i < count($hist); $i++) { ?>
          <td class="udata"><?php printf("%.2f%%", ($hist[$i]->percent * 100)); ?></td>
        <?php } ?>
      </tr>
    </table>
  </div>
<?php } ?>
