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
    {foreach $data->timelines['general'] as $property => $locations}
      {assign var="i" value="0"}
      {foreach $data->{'multilinguality-general-locations'} as $location => $label}
        {$i = $i + 1}
        {assign var="timeline" value=$locations[$location]}
        <tr>
          <td>{if $i == 1}{$property}{/if}</td>
          <td>{$label}</td>
          {foreach $data->files as $version => $file}
            <td class="num">{if isset($timeline[$version])}{$timeline[$version]|number_format:2}{/if}</td>
          {/foreach}
        </tr>
      {/foreach}
    {/foreach}
  </tbody>
</table>

<table class="timeline timeline-multilinguality-fields" xmlns="http://www.w3.org/1999/html">
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
    {foreach $data->timelines['fields'] as $field => $locations}
      {assign var="i" value="0"}
      {foreach $locations as $location => $properties}
        {$i = $i + 1}
        {assign var="j" value="0"}
        {foreach $properties as $property => $timeline}
          {$j = $j + 1}
          <tr>
            <td>{if $i == 1 && $j == 1}{$field}{/if}</td>
            <td>{if $j == 1}{$location}{/if}</td>
            <td>{$property}</td>
              {foreach $data->files as $version => $file}
                <td class="num">{if isset($timeline[$version])}{$timeline[$version]|number_format:2}{/if}</td>
              {/foreach}
          </tr>
        {/foreach}
      {/foreach}
    {/foreach}
  </tbody>
</table>
