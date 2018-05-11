{foreach $data->histogram as $field => $hist}
  <div>
    <h2>{$field|replace:'proxy_':''|replace:'_':':'}</h2>
    <table class="histogram uniqueness-statistics" id="{$field}-uniqueness-statistics">
      <caption>Basic statistics &mdash; Note: it is an experimental table, subject of future changes</caption>
      <tr>
        <th>n</th>
        <th>mean</th>
        <th>std. deviation</th>
        <th>minimum</th>
        <th>maximum</th>
      </tr>
      <tr>
        <td class="udata">{$hist->statistics[0]->n|number_format:0:'.':','}</td>
        <td class="udata">{$hist->statistics[0]->mean}</td>
        <td class="udata">{$hist->statistics[0]->sd}</td>
        <td class="udata" title="{$hist->statistics[0]->recMin}">
          <a href="record.php?version={$data->version}&id={$hist->statistics[0]->recMin}">
            {$hist->statistics[0]->min}
          </a>
        </td>
        <td class="udata" title="{$hist->statistics[0]->recMax}">
          <a href="record.php?version={$data->version}&id={$hist->statistics[0]->recMax}">
            {$hist->statistics[0]->max}
          </a>
        </td>
      </tr>
    </table>

    <table class="histogram uniqueness-histogram" id="{$field}-histogram">
      <caption>Distribution</caption>
      <tr>
        <td colspan="2"></td>
        <td colspan="5">Non-unique values</td>
      </tr>
      <tr>
        <td class="legend">stars</td>
        {foreach $data->stars as $star}
          <td class="udata"><h2>{$star}</h2></td>
        {/foreach}
      </tr>
      <tr>
        <td class="legend">repetitions</td>
        {foreach $hist->frequencies as $frequency}
          <td class="udata">{$frequency->label}</td>
        {/foreach}
      </tr>
      <tr>
        <td class="legend">nr of records</td>
        {foreach $hist->frequencies as $frequency}
          <td class="udata">{$frequency->count|number_format:0:'.':','}</td>
        {/foreach}
      </tr>
      <tr>
        <td class="legend">percentage</td>
        {foreach $hist->frequencies as $frequency}
          <td class="udata">{$frequency->percent * 100|string_format:"%.2f%%"}</td>
        {/foreach}
      </tr>
    </table>
  </div>
{/foreach}