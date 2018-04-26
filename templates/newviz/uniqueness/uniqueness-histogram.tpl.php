<?php foreach ($data->histogram as $field => $hist) { ?>
  <div>
    <h2><?= str_replace('_', ':', str_replace('proxy_', '', $field)) ?></h2>
    <table class="histogram uniqueness-statistics" id="<?= $field; ?>-uniqueness-statistics">
      <caption>Basic statistics</caption>
      <tr>
        <th>mean</th>
        <th>std. deviation</th>
        <th>minimum</th>
        <th>maximum</th>
      </tr>
      <tr>
        <td class="udata"><?= $hist->statistics[0]->mean; ?></td>
        <td class="udata"><?= $hist->statistics[0]->sd; ?></td>
        <td class="udata" title="<?= $hist->statistics[0]->recMin; ?>"><a href="record.php?version=<?= $data->version ?>&id=<?= $hist->statistics[0]->recMin; ?>"><?= $hist->statistics[0]->min; ?></a></td>
        <td class="udata" title="<?= $hist->statistics[0]->recMax; ?>"><a href="record.php?version=<?= $data->version ?>&id=<?= $hist->statistics[0]->recMax; ?>"><?= $hist->statistics[0]->max; ?></a></td>
      </tr>
    </table>

    <table class="histogram uniqueness-histogram" id="<?= $field; ?>-histogram">
      <caption>Distribution</caption>
      <tr>
        <td colspan="2"></td>
        <td colspan="5">Non-unique values</td>
      </tr>
      <tr>
        <td class="legend">stars</td>
        <?php for($i = 0; $i < count($hist->frequencies); $i++) { ?>
          <td class="udata"><h2><?= $data->stars[$i]; ?></h2></td>
        <?php } ?>
      </tr>
      <tr>
        <td class="legend">range of occurences</td>
        <?php for($i = 0; $i < count($hist->frequencies); $i++) { ?>
          <td class="udata"><?= $hist->frequencies[$i]->label; ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td class="legend">nr of records</td>
        <?php for($i = 0; $i < count($hist->frequencies); $i++) { ?>
          <td class="udata"><?= number_format($hist->frequencies[$i]->count, 0, '.', ','); ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td class="legend">percentage</td>
        <?php for($i = 0; $i < count($hist->frequencies); $i++) { ?>
          <td class="udata"><?php printf("%.2f%%", ($hist->frequencies[$i]->percent * 100)); ?></td>
        <?php } ?>
      </tr>
    </table>
  </div>
<?php } ?>
