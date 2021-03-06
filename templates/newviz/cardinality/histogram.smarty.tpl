{if $displayTitle}
  <h3>Number of field occurrences in individual records</h3>
{/if}
<table class="histogram" id="{$histogram->field}-histogram">
  <tr>
    <td class="legend">range of values</td>
    {foreach $histogram->values as $value}
      <td data-toggle="histogram-popover"
          data-content="@{$histogram->solrField}|{$value->label|solrRangeQuery}|{$value->label}|{$histogram->fq}"
          title="List records">
        <span data-toggle="tooltip" title="Get examples">{$value->label}</span>
      </td>
    {/foreach}
  </tr>
  <tr>
    <td class="legend">nr of records</td>
    {foreach $histogram->values as $value}
      <td>{$value->count|number_format:0:'.':','}</td>
    {/foreach}
  </tr>
  <tr>
    <td class="legend">percentage</td>
    {foreach $histogram->values as $value}
      {assign var=percentage value=$value->density * 100}
      <td title="{$percentage}">{$percentage|string_format:"%.2f%%"}</td>
    {/foreach}
  </tr>
</table>
