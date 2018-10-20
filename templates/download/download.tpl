{include file="../common/html-header.smarty.tpl"}
<body>
<!-- load jQuery and tablesorter scripts -->
<script type="text/javascript" src="jquery/jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="jquery/jquery.tablesorter.min.js"></script>
<!-- tablesorter widgets (optional) -->
<script type="text/javascript" src="jquery/jquery.tablesorter.widgets.min.js"></script>

<div class="container">
  <div class="page-header">
    <h3>Metadata Quality Assurance Framework for Europeana</h3>
  </div>
  <h2>download files</h2>

  <p>Here you can download selected snapshots of the whole Europeana database.</p>

  <p>versions:</p>
  <ul type="square">
    {foreach $versions as $v}
      {if $v != 'v2015-12'}
        <li>
          {if $version == $v}
            {$v}
          {else}
            <a href="download.php?version={$v}">{$v}</a>
          {/if}
        </li>
      {/if}
    {/foreach}
  </ul>
  <table id="dataset" class="table table-condensed table-striped tablesorter">
    <thead>
      <tr>
        <th>dataset name</th>
        <th>number of records</th>
        <th>size of uncompresed JSON file (in MB)</th>
      </tr>
    </thead>
    <tbody>
      {foreach $files as $file}
        <tr>
          <td>
            {if $file['file'] == 'total'}
              {$file['file']}
            {else}
              <a href="download.php?version={$version}&file={$file['file']}">{$file['file']}</a>
            {/if}
          </td>
          <td>{$file['records']|number_format:0:'.':'&nbsp;'}</td>
          <td>{($file['bytes'] / (1024 * 1024))|number_format:2:'.':'&nbsp;'}</td>
        </tr>
      {/foreach}
    </tbody>
  </table>

  <footer>
    {include file="../common/footer.smarty.tpl"}
  </footer>
</div>

<script type="text/javascript">
{literal}
  $(document).ready(function() {
    $("#dataset").tablesorter();
  });
{/literal}
</script>

</body>
</html>

