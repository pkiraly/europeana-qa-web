<p>
  What fields make typical records? In other words: what fields do the data
  providers make use?
  This table shows you the field patterns found in records group by clusters,
  so similar patterns are belong to the same cluster.
  The table is ordered by the number of records, so the more typical records
  are on the top. With the <i class="fa fa-plus" aria-hidden="true"></i> sign
  you can check the patterns belong to the cluster. If the field is not
  present in all records within the cluster only in some patterns, then
  it is grayed.
  Each line contains information about which fields are present in the
  record, and how many records has this pattern.
  By default we do not display patterns occur in less than 1% of
  the records, but you can unhide them with the &ldquo;Show infrequent patterns!&rdquo;
  link.
</p>
<ul>
  <li>
    <em>cluster ID</em> &mdash; the count number of the cluster.
    With clicking on the down arrow one can display/hide the patterns belong
    to this cluster.
  </li>
  <li>
    The first columns contain the provider proxy fields present in the records.
    The order is the same as in the EDM documentation. It does not contain the fields
    which are not present in any of the records (within the dataset). If a cell is green,
    the field is present, otherwise not.
  </li>
  <li>
    <em>#fields</em> &mdash; the number of fields present in the pattern
  </li>
  <li>
    <em>#patterns</em> &mdash; the number of patterns this cluster covers
  </li>
  <li>
    <em>occurence</em> &mdash; the number of records in which this pattern is present
  </li>
  <li>
    <em>percent</em> &mdash; the percent of records compared to the whole dataset in
    which this pattern is present
  </li>
</ul>

<table id="field-patterns">
  <thead>
    <tr>
      <th></th>
      <th title="cluster/pattern identifier">cluster&nbsp;ID</th>
      {foreach $data->fields as $field => $count}
        <th title="{$field}">{$field}&nbsp;<span class="count">({$count|number_format:0:'.':'&nbsp;'})</span></th>
      {/foreach}
      <th title="the number of fields in a record">#fields</th>
      <th title="the number of different patterns in the cluster">#patterns</th>
      <th title="the number of records the cluster/pattern occures in">occurence</th>
      <th title="the percent of records the cluster/pattern occures in">percent</th>
    </tr>
  </thead>
  <tbody class="jakob">
    <tr class="barchart">
      <td colspan="2"></td>
      {assign var=first value=0}
      {foreach $data->fields as $field => $count}
        {if $first==0}{assign var=first value=$count}{/if}
        <td class="bar">
          <div style="height: {sprintf("%d", $count * 50 / $first)}px;" title="{$count|number_format:0:'.':' '}"></div>
        </td>
      {/foreach}
      <td colspan="4"></td>
    </tr>
    {assign var=has_hidden_cluster value=false}
    {foreach $data->profiles as $cluster}
      {if $cluster['underOne']}
        {assign var=has_hidden_cluster value=true}
      {/if}
      <tr class="profile profile-hidden profile-{$cluster['id']} spacer">
        <td colspan="{$data->fields|count+6}"class="noborder"></td>
      </tr>
      <tr{if $cluster['underOne']} class="under-one{if $cluster['drawLine']} draw-line{/if}"{/if}>
        {strip}
        <td align="right" class="opener cluster cluster-{$cluster['id']}">
          {if $cluster['patternCount'] > 1}
            <a class="qa-show-profiles {$cluster['id']}" href="#"
               title="Show the patterns of this cluster">
              <i class="fa fa-plus" aria-hidden="true"></i>
            </a>
          {/if}
        </td>
        <td align="right" class="cluster cluster-{$cluster['id']}">
          {$cluster['id']+1}
        </td>
        {foreach $data->fields as $field => $count}
          {assign var=class value=''}
          {assign var=opacity value=''}
          {assign var=title value=$field}
          {if isset($cluster['profileFields'][$field])}
            {assign var=class value=sprintf(
              'class="%s"', $cluster['profileFields'][$field]['class'])
            }
            {assign var=title
              value=sprintf("%s (%.2f%%)",
              field, ($cluster['profileFields'][$field]['opacity']*100))
            }
            {if !$cluster['underOne'] && $cluster['profileFields'][$field]['opacity'] < 1.0}
              {assign var=opacity
                value=sprintf('style="opacity: %.2f"',
                ($cluster['profileFields'][$field]['opacity'] * 0.8) + 0.1)
              }
            {/if}
          {/if}
          <td align="center" {$class} {$opacity} title="{$title}"></td>
        {/foreach}
        <td align="right" class="length">{$cluster['length']}</td>
        <td align="right" class="patternCount">{$cluster['patternCount']}</td>
        <td align="right" class="count">{$cluster['count']}</td>
        <td align="right" class="percent">{$cluster['percent']|string_format:"%.02f%%"}</td>
        {/strip}
      </tr>
      {if $cluster['patternCount'] > 1}
        <tr class="profile profile-hidden profile-{$cluster['id']} in-between">
          <td colspan="{$data->fields|count + 6}"></td>
        </tr>
        {foreach $cluster['rows'] as $i => $profile}
          <tr class="profile profile-hidden profile-{$cluster['id']} {if $i == 0}profile-first{elseif $i == $cluster['patternCount']-1}profile-last{/if}">
            {strip}
            <td></td>
            <td align="right" class="profile-id">{$cluster['id']+1}-{$i+1}</td>
            {foreach $data->fields as $field => $count}
              <td align="center"{if in_array($field, $profile['profileFields'])} class="profile-filled"{/if} title="{$field}"></td>
            {/foreach}
            <td align="right">{$profile['length']}</td>
            <td align="right"></td>
            <td align="right">{$profile['count']}</td>
            <td align="right">{$profile['percent']|string_format:"%.02f%%"}</td>
            {/strip}
          </tr>
        {/foreach}
        <tr class="profile profile-hidden profile-{$cluster['id']} spacer">
          <td colspan="{$data->fields|count+6}" class="noborder"></td>
        </tr>
      {/if}
    {/foreach}
    {strip}
      {if $data->has_hidden}
        <tr class="show-more">
          <td colspan="{$data->fields|count+6}" class="noborder">
            <a class="qa-show-infrequent-patterns" href="#"
               title="display infrequent patterns">
              <i class="fa fa-plus" aria-hidden="true"></i>
              &nbsp; <span class="show-more-label">Show infrequent patterns!</span>
            </a>
          </td>
        </tr>
      {/if}
    {/strip}
  </tbody>
</table>

{literal}
<script>
$('a.qa-show-profiles').click(function (event) {
  event.preventDefault();
  var parent = $(this).parent("td").parent("tr");
  var profileId = $(this).attr('class').replace('qa-show-profiles ', 'profile-');
  $('tr.' + profileId).toggle(1000);
  var icon = $('i', $(this));
  var isShown = icon.attr('class') == 'fa fa-plus';
  var toggledClass = isShown ? 'fa fa-minus' : 'fa fa-plus';
  icon.attr('class', toggledClass);

  var toggledTitle = isShown
    ? 'Hide the patterns of this cluster!'
    : 'Show the patterns of this cluster!';
  $(this).attr('title', toggledTitle);

  if (isShown) {
    parent.addClass("cluster-selected");
  } else {
    parent.removeClass("cluster-selected")
  }
});

$('a.qa-show-infrequent-patterns').click(function (event) {
  event.preventDefault();
  $('tr.under-one').toggle(1000);

  var icon = $('i', $(this));
  var isShown = icon.attr('class') == 'fa fa-plus';
  var toggledClass = isShown ? 'fa fa-minus' : 'fa fa-plus';
  icon.attr('class', toggledClass);

  var toggledTitle = isShown
    ? 'Hide infrequent patterns!'
    : 'Show infrequent patterns!';
  $('tr.show-more span.show-more-label').text(toggledTitle);
});

</script>
{/literal}