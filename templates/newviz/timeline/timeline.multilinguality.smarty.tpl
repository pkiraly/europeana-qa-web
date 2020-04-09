<h3>General properties</h3>
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
          <td>{$location_label}</td>
          {foreach $data->files as $version => $file}
            <td class="num">{if isset($timeline[$version])}{$timeline[$version]|number_format:3}{/if}</td>
          {/foreach}
        </tr>
      {/foreach}
    {/foreach}
  </tbody>
</table>

<h3>Fields</h3>
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
            <td>{$data->multilinguality_field_properties[$property]}</td>
              {foreach $data->files as $version => $file}
                <td class="num {if $timeline[$version] == 0.0}nil{/if}">
                  {if isset($timeline[$version])}{$timeline[$version]|number_format:3}{/if}
                </td>
              {/foreach}
          </tr>
        {/foreach}
      {/foreach}
    {/foreach}
  </tbody>
</table>
