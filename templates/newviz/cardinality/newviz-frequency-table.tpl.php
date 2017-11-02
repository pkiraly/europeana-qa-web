<h3>Frequency table</h3>
<table class="histogram">
  <tr>
    <td class="legend">values</td>
<?php foreach ($data as $value => $frequency) { ?>
    <td><span title="<?= $value ?>"><?= sprintf("%.3f", $value); ?></span></td>
<?php } ?>
  </tr>
  <tr>
    <td class="legend">count</td>
<?php foreach ($data as $value => $frequency) { ?>
      <td><?= $frequency[0]; ?></td>
<?php } ?>
  </tr>
  <tr>
    <td class="legend">percentage</td>
<?php foreach ($data as $value => $frequency) { ?>
    <td>
      <span title="<?= $frequency[0] * 100 / $n; ?>"><?php
         printf("%.2f%%", ($frequency[0] * 100 / $n)); ?></span>
    </td>
<?php } ?>
  </tr>
</table>
