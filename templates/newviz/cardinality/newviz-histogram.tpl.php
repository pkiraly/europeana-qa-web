<h3>Cardinality histogram</h3>
<table class="histogram" id="<?= $data->field; ?>-histogram">
  <tr>
    <td class="legend">range of values</td>
    <?php for($i = 0; $i < count($data->values); $i++) { ?>
      <td><?= $data->values[$i]->label; ?></td>
    <?php } ?>
  </tr>
  <tr>
    <td class="legend">count</td>
    <?php for($i = 0; $i < count($data->values); $i++) { ?>
      <td><?= $data->values[$i]->count; ?></td>
    <?php } ?>
  </tr>
  <tr>
    <td class="legend">percentage</td>
    <?php for($i = 0; $i < count($data->values); $i++) { ?>
      <td><?php printf("%.2f%%", $data->values[$i]->density); ?></td>
    <?php } ?>
  </tr>
</table>
