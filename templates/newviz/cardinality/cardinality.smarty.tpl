<h3>Statistics for the number of field occurrences</h3>
<table class="histogram">
  <tr>
    <td class="legend">nr of records</td>
    <td class="legend">nr of instances</td>
    <td class="legend">median</td>
    <td class="legend">mean</td>
  </tr>
  <tr>
    <td>{$cardinality->count|number_format:0:'.':','}</td>
    <td>{$cardinality->sum|number_format:0:'.':','}</td>
    <td>{$cardinality->median}</td>
    <td>{$cardinality->mean|number_format:2}</td>
  </tr>
</table>
