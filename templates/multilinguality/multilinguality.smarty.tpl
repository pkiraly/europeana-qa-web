<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Metadata Quality Assurance Framework for Europeana</title>
  <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.1.0/styles/default.min.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
        integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway" type="text/css" />
  <script src="https://use.fontawesome.com/feff23b961.js"></script>
  <link rel="stylesheet" href="europeana-qa.css?a={$rand}" />
  <!-- choose a theme file -->
  <link rel="stylesheet" href="jquery/theme.default.min.css">
  <!-- load jQuery and tablesorter scripts -->
  <script type="text/javascript" src="jquery/jquery-1.9.1.min.js"></script>
  <script type="text/javascript" src="jquery/jquery.tablesorter.min.js"></script>

  <!-- tablesorter widgets (optional) -->
  <script type="text/javascript" src="jquery/jquery.tablesorter.widgets.min.js"></script>
</head>
<body>

<div class="container">

  <div class="page-header">
    <h3>Metadata Quality Assurance Framework for Europeana</h3>
  </div>

<h2>Multilingual saturation</h2>

<form>
  <label for="type">grouped by </label>
  <select name="type" onchange="this.form.submit();">
    {foreach $types as $name => $label}
      <option value="{$name}" {if ($name == $type)} selected="selected"{/if}>{$label}</option>
    {/foreach}
  </select>

  <label for="feature"> feature: </label>
  <select name="feature" onchange="this.form.submit();">
    {foreach $features as $name => $label}
      <option value="{$name}" {if ($name == $feature)} selected="selected"{/if}>{$label}</option>
    {/foreach}
  </select>

  {if $feature == 'all'}
    <label for="statistic"> statistic: </label>
    <select name="statistic" onchange="this.form.submit();">
      {foreach $statistics as $name => $label}
        <option value="{$name}" {if ($name == $statistic)} selected="selected"{/if}>{$label}</option>
      {/foreach}
    </select>
  {/if}
</form>

{if $feature == 'all'}
  <table id="dataset" class="table table-condensed table-striped tablesorter">
    <thead>
      <tr>
        <th colspan="3"></th>
        <th colspan="3"># of tagged literals in</th>
        <th colspan="3"># of distinct languages in</th>
        <th colspan="3"># of tagged literals per language in</th>
        <th colspan="3"># of languages per property in</th>
      </tr>
      <tr>
        <th></th>
        <th># records</th>
        <th>Dataset</th>
        <th>prov.</th>
        <th>Eur.</th>
        <th>obj.</th>
        <th>prov.</th>
        <th>Eur.</th>
        <th>obj.</th>
        <th>prov.</th>
        <th>Eur.</th>
        <th>obj.</th>
        <th>prov.</th>
        <th>Eur.</th>
        <th>obj.</th>
      </tr>
    </thead>
    <tbody>
    {foreach $rows as $counter => $obj}
      <tr>
        <td class="text">{$counter}</td>
        <td class="text">{$obj->n}</td>
        <td class="text"><a href="{$datasetLink}?id={$obj->id}&name={$obj->collectionId}&type={$obj->type}#multilingual-score">{$obj->collectionId}</a></td>
        <td class="sep">{$obj->saturation2_taggedliterals_in_providerproxy|conditional_format:FALSE:FALSE:1}</td>
        <td>{$obj->saturation2_taggedliterals_in_europeanaproxy|conditional_format:FALSE:FALSE:1}</td>
        <td>{$obj->saturation2_taggedliterals_in_object|conditional_format:FALSE:FALSE:1}</td>
        <td class="sep">{$obj->saturation2_distinctlanguages_in_providerproxy|conditional_format:FALSE:FALSE:1}</td>
        <td>{$obj->saturation2_distinctlanguages_in_europeanaproxy|conditional_format:FALSE:FALSE:1}</td>
        <td>{$obj->saturation2_distinctlanguages_in_object|conditional_format:FALSE:FALSE:1}</td>
        <td class="sep">{$obj->saturation2_taggedliterals_per_language_in_providerproxy|conditional_format:FALSE:FALSE:1}</td>
        <td>{$obj->saturation2_taggedliterals_per_language_in_europeanaproxy|conditional_format:FALSE:FALSE:1}</td>
        <td>{$obj->saturation2_taggedliterals_per_language_in_object|conditional_format:FALSE:FALSE:1}</td>
        <td class="sep">{$obj->saturation2_languages_per_property_in_providerproxy|conditional_format:FALSE:FALSE:1}</td>
        <td>{$obj->saturation2_languages_per_property_in_europeanaproxy|conditional_format:FALSE:FALSE:1}</td>
        <td>{$obj->saturation2_languages_per_property_in_object|conditional_format:FALSE:FALSE:1}</td>
      </tr>
    {/foreach}
    </tbody>
  </table>
{else}
  <table id="dataset" class="table table-condensed table-striped tablesorter">
    <thead>
      <tr>
        <th></th>
        <th># records</th>
        <th>Dataset</th>
        <th>Mean</th>
        <th>Standard deviation</th>
        <th>Minimum</th>
        <th>Maximum</th>
        <th>Range</th>
        <th>Median</th>
      </tr>
    </thead>
    <tbody>
      {foreach $rows as $counter => $obj}
        <tr>
          <td>{$counter}</td>
          <td>{$obj->n}</td>
          <td><a href="{$datasetLink}?id={$obj->id}&name={$obj->collectionId}&type={$obj->type}#multilingual-score">{$obj->collectionId}</a></td>
          <td>{$obj->mean}</td>
          <td>{if isset($obj->{'std.dev'})}{$obj->{'std.dev'}}{else}'na'{/if}</td>
          <td>{$obj->min}</td>
          <td>{$obj->max}</td>
          <td>{$obj->range}</td>
          <td>{$obj->median}</td>
        </tr>
      {/foreach}
    </tbody>
  </table>
{/if}

{if (!empty($errors))}
<h2>problems</h2>
<ul type="square">
  {foreach $errors as $error}
    <li>{$error}</li>
  {/foreach}
</ul>
{/if}


<footer>
  <p><a href="http://pkiraly.github.io/">What is this?</a> &ndash; about the Metadata Quality Assurance Framework project.</p>
</footer>
</div>

<script type="text/javascript">
$(document).ready(function() {
  $("#dataset").tablesorter();
});
</script>

</body>
</html>
