<h2>Changes of completeness scores</h2>

<div id="svg-container-completeness" class="svg-container"></div>

{foreach $data->timelines as $entity => $fields}
  <h3>{$entity}</h3>
  <table class="timeline timeline-completeness" xmlns="http://www.w3.org/1999/html">
    <thead>
      <tr>
        <th>field</th>
        <th>location</th>
        {foreach $data->files as $version => $file}
          <th>{$version}</th>
        {/foreach}
      </tr>
    </thead>
    <tbody>
      {foreach $fields as $field => $field_properties}
        {assign var="i" value="0"}
        {foreach $field_properties as $location => $timeline}
          {$i = $i + 1}
          <tr {if $i == 1}class="newline"{/if}>
            <td>{if $i == 1}{$field}{/if}</td>
            <td class="property">{if $location == 'provider'}original{else}enrichment{/if}</td>
            {foreach $data->files as $version => $file}
              <td class="num" data="{if isset($timeline[$version])}{$timeline[$version]}{/if}">
                {if isset($timeline[$version])}
                  {if ($data->statistic == 'mean' || $data->statistic == 'stddev')}
                    {$timeline[$version]|number_format:3}
                  {else}
                    {$timeline[$version]|number_format}
                  {/if}
                {/if}
              </td>
            {/foreach}
          </tr>
        {/foreach}
      {/foreach}
    </tbody>
  </table>
{/foreach}

<script type="text/javascript" src="js/timeline.js"></script>
<script src="https://d3js.org/d3-time.v1.min.js"></script>
<script src="https://d3js.org/d3-time-format.v2.min.js"></script>
<script type="text/javascript">
  var versions = [{foreach from=$data->files key=version item=file name=versions}'{$version}'{if !$smarty.foreach.posts.last},{/if}{/foreach}];

  $(document).ready(function () {
    startInteractiveTimeline('svg-container-completeness', 'timeline-completeness');
  });
</script>