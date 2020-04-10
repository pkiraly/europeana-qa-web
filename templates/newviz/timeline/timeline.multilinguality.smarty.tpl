<h2>Changes of multilinguality scores</h2>

<div id="svg-container"></div>

<h3>General multilinguality scores</h3>
<table class="timeline timeline-multilinguality-general" xmlns="http://www.w3.org/1999/html">
  <thead>
    <tr>
      <th>property</th>
      <th>location</th>
      {foreach $data->files as $version => $file}
        <th>{$version}</th>
      {/foreach}
    </tr>
  </thead>
  <tbody>
    {foreach $data->multilinguality_general_properties as $property => $property_label}
      {assign var="i" value="0"}
      {assign var="locations" value=$data->timelines['general'][$property]}
      {foreach $data->multilinguality_general_locations as $location => $location_label}
        {$i = $i + 1}
        {assign var="timeline" value=$locations[$location]}
        <tr {if $i == 1}class="newline"{/if}>
          {if $i == 1}
            <td rowspan="3" class="property">{$property_label}</td>
          {/if}
          <td class="property">{$location_label}</td>
          {foreach $data->files as $version => $file}
            <td class="num" {if isset($timeline[$version])}data="{$timeline[$version]}"{/if}>
              {if isset($timeline[$version])}{$timeline[$version]|number_format:3}{/if}
            </td>
          {/foreach}
        </tr>
      {/foreach}
    {/foreach}
  </tbody>
</table>

<h3>Multilinguality scores per fields</h3>
<table class="timeline timeline-multilinguality-fields" xmlns="http://www.w3.org/1999/html">
  <thead>
    <tr>
      <th>field</th>
      <th>location</th>
      <th>property</th>
      {foreach $data->files as $version => $file}
        <th>{$version}</th>
      {/foreach}
    </tr>
  </thead>
  <tbody>
    {foreach $data->timelines['fields'] as $field => $locations}
      {assign var="i" value="0"}
      {foreach $locations as $location => $properties}
        {$i = $i + 1}
        {assign var="j" value="0"}
        {foreach $properties as $property => $timeline}
          {$j = $j + 1}
          <tr {if $i == 1 && $j == 1}class="newline"{/if}>
            <td>{if $i == 1 && $j == 1}{$field}{/if}</td>
            <td>{if $j == 1}{$data->multilinguality_field_locations[$location]}{/if}</td>
            <td class="property">{$data->multilinguality_field_properties[$property]}</td>
              {foreach $data->files as $version => $file}
                <td class="num {if $timeline[$version] == 0.0}nil{/if}"
                    {if isset($timeline[$version])}data="{$timeline[$version]}"{/if}>
                  {if isset($timeline[$version])}{$timeline[$version]|number_format:3}{/if}
                </td>
              {/foreach}
          </tr>
        {/foreach}
      {/foreach}
    {/foreach}
  </tbody>
</table>

{literal}
  <style type="text/css">
    #svg-container {
      background-color: #428bca;
      padding: 20px;
      height: 140px;
    }
  </style>
<script type="text/javascript">
$(document).ready(function () {
  var timeline_w = 500;
  var timeline_h = 100;
  var timeline_barPadding = 2;

  var svg = d3.select("div#svg-container")
              .append("svg")
              .attr("width", timeline_w)
              .attr("height", timeline_h);

  var dataset = [];
  $('table.timeline td.property').on('click', function(e) {
    dataset = [];

    $(this).siblings('td').each(function() {
      if ($(this).hasClass('num') && $(this).attr('data') !== typeof undefined) {
        var value = Number($(this).attr('data'));  // or $(this).html()
        dataset.push(value);
      }
    });

    $("#svg-container svg").children().each(function(e) {$(this).remove()})
    var max = d3.max(dataset);
    var min = d3.min(dataset);
    var range = max - min;
    var minmaxPadding = range / 4;

    var yScale = d3.scale.linear()
                   .domain([min - minmaxPadding, max + minmaxPadding])
                   .range([0, timeline_h]);

    svg.selectAll("rect")
      .data(dataset)
      .enter()
      .append("rect")
      .transition()
      .duration(1000)
      .attr("x", function(d, i) {
        return i * (timeline_w / dataset.length);
      })
      .attr("y", function(d) {
        return timeline_h - yScale(d);
      })
      .attr("width", timeline_w / dataset.length - timeline_barPadding)
      .attr("height", function(d) {
        return yScale(d);
      })
    ;

    svg.selectAll("text")
      .data(dataset)
      .enter()
      .append("text")
      .text(function(d) {
        return Math.ceil(d * 1000) / 1000;
      })
      .attr("x", function(d, i) {
        return i * (timeline_w / dataset.length) + (timeline_w / dataset.length - timeline_barPadding) / 2;
      })
      .attr("y", function(d) {
        return timeline_h - yScale(d) + 14;
      })
      .attr("font-family", "sans-serif")
      .attr("font-size", "11px")
      .attr("fill", "white")
      .attr("text-anchor", "middle")
    ;

  });
});
</script>
{/literal}
