<table class="histogram uniqueness-statistics" id="{$field}-uniqueness-statistics" xmlns="http://www.w3.org/1999/html">
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
        <td>{$entity}</td>
      </tr>
      {foreach $fields as $field => $field_properties}
        {foreach $field_properties as $location => $timeline}
          <tr>
            <td>{$field}</td>
            <td>{$location}</td>
            {foreach $timeline as $version => $value}
              <td>{$value|number_format:2}</td>
            {/foreach}
          </tr>
        {/foreach}
      {/foreach}
    {/foreach}
  </tbody>
</table>
