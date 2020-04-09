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
