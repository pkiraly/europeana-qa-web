{if $displayTitle}
  <h3>Number of field occurrences in individual records</h3>
{/if}
<table class="histogram" id="{$field}-histogram">
  <tr>
    <td class="legend">range of values</td>
    {foreach $histogram as $value}
      <td data-toggle="histogram-popover"
          data-content="@{toSolrField($field)}|{toSolrRangeQuery($value)}|{(int)$value->min}-{(int)$value->max}|{$fq}"
          title="List records">
        <span data-toggle="tooltip" title="Get examples">{strip}
          {if $value->min == 0}
          0
          {else}
            {if $value->min == floor($value->min)}
              {$value->min|string_format:"%.0f"}
            {else}
              {$value->min|string_format:"%.2f"}
            {/if}
            {if $value->min != $value->max}
              -
              {if $value->max == floor($value->max)}
                {$value->max|string_format:"%.0f"}
              {else}
                {$value->max|string_format:"%.2f"}
              {/if}
            {/if}
          {/if}
        {/strip}</span>
      </td>
    {/foreach}
  </tr>
  <tr>
    <td class="legend">nr of records</td>
    {foreach $histogram as $value}
      <td>{$value->count|number_format:0:'.':','}</td>
    {/foreach}
  </tr>
  <tr>
    <td class="legend">percentage</td>
    {foreach $histogram as $value}
      {assign var=percentage value=$value->count / $proxyCount * 100}
      <td title="{$percentage}">{$percentage|string_format:"%.2f%%"}</td>
    {/foreach}
  </tr>
</table>
