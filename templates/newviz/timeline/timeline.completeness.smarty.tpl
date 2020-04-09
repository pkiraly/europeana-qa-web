<table class="timeline" xmlns="http://www.w3.org/1999/html">
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
    {foreach $data->timelines as $entity => $fields}
      <tr>
        <td colspan="{count($data->files) + 2}">{$entity}</td>
      </tr>
      {foreach $fields as $field => $field_properties}
        {assign var="i" value="0"}
        {foreach $field_properties as $location => $timeline}
          {$i = $i + 1}
          <tr>
            <td>{if $i == 1}{$field}{/if}</td>
            <td>{if $location == 'provider'}original{else}enrichment{/if}</td>
            {foreach $data->files as $version => $file}
              <td>{if isset($timeline[$version])}{$timeline[$version]|number_format:2}{/if}</td>
            {/foreach}
          </tr>
        {/foreach}
      {/foreach}
    {/foreach}
  </tbody>
</table>