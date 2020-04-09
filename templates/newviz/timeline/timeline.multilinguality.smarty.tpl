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
      <tr><td colspan="8">0: {json_encode($locations)}</td></tr>
      {foreach $data->{'multilinguality-general-locations'} as $location => $label}
        {assign var="timeline" value=$locations[$location]}
        <tr><td colspan="8">1: {json_encode($location)}</td></tr>
        <tr><td colspan="8">2: {json_encode($locations[$location])}</td></tr>
        <tr><td colspan="8">3: {json_encode($timeline)}</td></tr>
        <tr>
          <td>{$property}</td>
          <td>{$label}</td>
          {foreach $data->files as $version => $file}
            <td class="num">{if isset($timeline[$version])}{$timeline[$version]|number_format:2}{/if}</td>
          {/foreach}
        </tr>
      {/foreach}
    {/foreach}
  </tbody>
</table>
