<h3>Statistics for the number of field occurrences</h3>
<table class="histogram">
  <tr>
    <td class="legend">nr of records</td>
    <td class="legend">nr of instances</td>
    <td class="legend">median</td>
    <td class="legend">mean</td>
  </tr>
  <tr>
    <td><?= number_format($data->count, 0, '.', ','); ?></td>
    <td><?= number_format($data->sum, 0, '.', ','); ?></td>
    <td><?= $data->median; ?></td>
    <td><?= $data->mean; ?></td>
  </tr>
</table>
