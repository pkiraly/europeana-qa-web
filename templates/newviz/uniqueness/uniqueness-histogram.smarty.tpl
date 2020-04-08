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
          {if (substr($frequency->label, strlen($frequency->label)-1) == '-')}
            {assign var=label value="{$frequency->label}*"}
          {else}
            {assign var=label value=$frequency->label}
          {/if}
          <td class="udata" data-toggle="uniqueness-popover"
              data-content="@uniq_{$field}_f|{$label|solrRangeQuery}|{$frequency->label}|{$data->fq}"
              title="List records">
            {$frequency->label}
          </td>
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

<script>
{literal}
$(document).ready(function () {
  $("[data-toggle='uniqueness-popover']").each(function() {
    $(this).css('cursor', 'pointer');
    $(this).css('color', '#23527c');
  });
  $("[data-toggle='uniqueness-popover']").on('show.bs.popover', function() {
    processUniquenessPopoverContent($(this));
  });
  $('[data-toggle="uniqueness-popover"]').popover({html: true});
});

function processUniquenessPopoverContent(element) {
  var content = element.attr('data-content');
  if (content.substring(0, 1) != '@') {
    content = content.replace(/^.*data-content="([^"]+)".*$/, "$1")
  }
  if (content.substring(0, 1) == '@') {
    var parts = content.substring(1).split('|');

    var field = parts[0];
    var q = field + ':' + parts[1];
    var range = parts[2];
    var fq = parts[3];
    var targetId = 'histogram-range-' + field + '-' + range;
    var html = '<span id="' + targetId + '" data-content="' + content + '"></span>';
    element.attr('data-content', html);

    var query = {'q': q, 'fq': fq, 'rows': 10};
    $.get('newviz/solr-ajax.php', query)
     .done(function(data) {
       var portalUrl = 'https://www.europeana.eu/portal/en/record';
       var items = new Array();
       for (i in data.ids) {
         var recordId = data.ids[i];
         var item = '<a target="_blank" href="' + getRecordLink(recordId) + '"'
                  + ' title="record id: ' + recordId + '">visit record</a>';
         items.push('<li>' + item + '</li>');
       }
       var content = '<ul>' + items.join('') + '</ul>';
       $('#' + targetId).html(content);
     });
  }
}

function getRecordLink(recordId) {
  return 'https://www.europeana.eu/api/v2/record' + recordId + '.json?wskey=hgQQMdjcG';
}
{/literal}
</script>