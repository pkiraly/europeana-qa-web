<h3>Number of records where the fields are present</h3>
<table class="histogram">
  <tr>
    <td class="legend"></td>
    {foreach $frequencyTable->values as $value => $frequency}
      <!-- val: {$value}, freq: {$frequency} -->
      <td><span title="{$value}">{if ($value == 0)}not present{else}present{/if}</span></td>
    {/foreach}
  </tr>
  <tr>
    <td class="legend">nr of records</td>
    {foreach $frequencyTable->values as $value => $frequency}
      <td>{$frequency|number_format:0:'.':','}</td>
    {/foreach}
  </tr>
  <tr>
    <td class="legend">percentage</td>
    {foreach $frequencyTable->values as $value => $frequency}
      {assign var=percentage value=$frequency * 100 / $frequencyTable->entityCount}
      <td>
        <span title="{$percentage}">{$percentage|string_format:"%.2f%%"}</span>
      </td>
    {/foreach}
  </tr>
</table>
