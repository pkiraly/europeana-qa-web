<h3>Number of records where the fields are available</h3>
<table class="histogram">
  <tr>
    <td class="legend"></td>
<?php foreach ($data->values as $value => $frequency) { ?>
    <td><span title="<?= $value ?>"><?= ($value == 0) ? 'not available' : 'available' ?></span></td>
<?php } ?>
  </tr>
  <tr>
    <td class="legend">nr of records</td>
<?php foreach ($data->values as $value => $frequency) { ?>
      <td><?= number_format($frequency[0], 0, '.', ','); ?></td>
<?php } ?>
  </tr>
  <tr>
    <td class="legend">percentage</td>
<?php foreach ($data->values as $value => $frequency) { ?>
    <td>
      <span title="<?= $frequency[0] * 100 / $data->entityCount; ?>"><?php
         printf("%.2f%%", ($frequency[0] * 100 / $data->entityCount)); ?></span>
    </td>
<?php } ?>
  </tr>
</table>
