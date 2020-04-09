<table class="timeline timeline-multilinguality-general" xmlns="http://www.w3.org/1999/html">
  <thead>
    <tr>
      <th>field</th>
      {foreach $data->files as $version => $file}
        <th>{$version}</th>
      {/foreach}
    </tr>
  </thead>
  <tbody>
    {foreach $data->timelines['general'] as $property => $locations}
      {foreach $data->{'multilinguality-general-locations'} as $location => $label}
        {assign var="timeline" value=$locations[$location]}
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
