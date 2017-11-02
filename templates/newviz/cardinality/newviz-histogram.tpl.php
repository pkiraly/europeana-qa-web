<h3>Cardinality histogram</h3>
<table class="histogram">
  <tr>
    <td class="legend">range of values</td>
    <?php for($i = 0; $i < count($data); $i++) { ?>
      <td><?= $data[$i]->label; ?></td>
    <?php } ?>
  </tr>
  <tr>
    <td class="legend">count</td>
    <?php for($i = 0; $i < count($data); $i++) { ?>
      <td><?= $data[$i]->count; ?></td>
    <?php } ?>
  </tr>
  <tr>
    <td class="legend">percentage</td>
    <?php for($i = 0; $i < count($data); $i++) { ?>
      <td><?php printf("%.2f%%", $data[$i]->density); ?></td>
    <?php } ?>
  </tr>
</table>
