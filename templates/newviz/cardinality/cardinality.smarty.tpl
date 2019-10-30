{if $displayTitle}
  <h3>Statistics for the number of field occurrences</h3>
{/if}
<table class="histogram">
  <tr>
    <td class="legend">nr of records</td>
    <td class="legend">nr of occurrences</td>
    {if $displayMedian}
      <td class="legend">median</td>
    {/if}
    <td class="legend">mean</td>
  </tr>
  <tr>
    <td>{$cardinality->count|number_format:0:'.':','}</td>
    <td>{$cardinality->sum|number_format:0:'.':','}</td>
    {if $displayMedian}
      <td>{$cardinality->median}</td>
    {/if}
    <td>{$cardinality->mean|number_format:2}</td>
  </tr>
</table>
