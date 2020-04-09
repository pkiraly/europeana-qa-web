<table class="histogram uniqueness-statistics" id="{$field}-uniqueness-statistics" xmlns="http://www.w3.org/1999/html">
  <thead>
    <tr>
      <th>field</th>
      {foreach $data->files as $version => $file}
        <th>{$version}</th>
      {/foreach}
    </tr>
  </thead>
  <tbody>
    {foreach $data->timelines as $field => $timeline}
      <tr>
        <td>{$field}</td>
        {foreach $timeline as $version => $value}
          <td>{$value|number_format:2}</td>
        {/foreach}
      </tr>
    {/foreach}
  </tbody>
</table>
