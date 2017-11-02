<h3>Cardinality histogram</h3>
<table class="histogram">
  <tr>
    <td class="legend">nr of records</td>
    <td class="legend">nr of instances</td>
    <td class="legend">mean</td>
    <td class="legend">median</td>
  </tr>
  <tr>
    <td><?= $data->count; ?></td>
    <td><?= $data->sum; ?></td>
    <td><?= $data->mean; ?></td>
    <td><?= $data->median; ?></td>
  </tr>
</table>
