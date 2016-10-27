<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Metadata Quality Assurance Framework</title>
  <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.1.0/styles/default.min.css" />
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway" type="text/css" />
  <link rel="stylesheet" href="europeana-qa.css" />
  <!-- choose a theme file -->
  <link rel="stylesheet" href="jquery/theme.default.min.css">
  <!-- load jQuery and tablesorter scripts -->
  <script type="text/javascript" src="jquery/jquery-1.2.6.min.js"></script>
  <script type="text/javascript" src="jquery/jquery.tablesorter.min.js"></script>

  <!-- tablesorter widgets (optional) -->
  <script type="text/javascript" src="jquery/jquery.tablesorter.widgets.min.js"></script>
</head>
<body>

<div class="container">
<h1>Metadata Quality Assurance Framework</h1>

<p>Here you can find some sample result of the first iteration of the Metadata Quality Assurance Framework. All the records come from <a href="http://europeana.eu">Europeana</a>.<br/>
Note: this is a research project, the results here are experimental, they are subject of change.</p>

<h2>Field frequency</h2>

<p>This chart shows the frequency of the analyzed fields in all records. 100% means that the field is available in every records, 0 means that this field is never available. The numbers are rounded to 2 decimals.</p>

<div id="frequency-chart" class="chart"></div>

<h2>Field cardinality</h2>

<p>This chart shows the cardinality of the analyzed fields in all records.</p>

<div id="cardinality-chart" class="chart"></div>

<h2>Language frequency</h2>

<p><a href="languages.php?field=proxy_dc_title&exclusions[]=0">Frequency of language specification in the most important fields.</a></p>

<h2>Dataset analyses</h2>

<p>Meaning of the columns in the table:</p>

<dl>
 <dt>Minimum</dt><dd>The lowest score</dd>
 <dt>Maximum</dt><dd>The highest score</dd>
 <dt>Range</dt><dd>The difference between minimum and maximum.</dd>
 <dt>Median</dt><dd>The median (middle) value.</dd>
 <dt>Meam</dt><dd>The mean (avarage) of the scores.</dd>
 <dt>Standard deviation</dt><dd><a href="https://en.wikipedia.org/wiki/Standard_deviation">Standard deviation</a> - the amount of variation of a set. Close to 0 indicates that the data points tend to be very close to the mean of the set, while a high standard deviation indicates that the data points are spread out over a wider range of values.</dd>
</dl>

<form>
  <label for="feature">Select dimension: </label>
  <select name="feature" onchange="this.form.submit();">
<?php foreach ($features as $name => $label) { ?>
    <option value="<?= $name ?>" <?php if ($name == $feature) { ?>selected="selected"<?php } ?>><?= $label ?></option>
<?php } ?>
  </select>

  <label for="type">groupped by </label>
  <select name="type" onchange="this.form.submit();">
<?php foreach ($types as $name => $label) { ?>
    <option value="<?= $name ?>" <?php if ($name == $type) { ?>selected="selected"<?php } ?>><?= $label ?></option>
<?php } ?>
  </select>
</form>

<table id="dataset" class="table table-condensed table-striped tablesorter">
  <thead>
    <tr>
      <th></th>
      <th># records</th>
      <th>Dataset</th>
      <th>Minimum</th>
      <th>Maximum</th>
      <th>Range</th>
      <th>Median</th>
      <th>Mean</th>
      <th>Standard deviation</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($rows as $counter => $obj) { ?>
    <tr>
      <td><?= $counter ?></td>
      <td><?= $obj->n ?></td>
      <td><a href="dataset.php?id=<?= $obj->id ?>&name=<?= $obj->collectionId ?>&type=<?= $obj->type ?>#<?= $feature ?>"><?= $obj->collectionId ?></a></td>
      <td><?= $obj->min ?></td>
      <td><?= $obj->max ?></td>
      <td><?= $obj->range ?></td>
      <td><?= $obj->median ?></td>
      <td><?= $obj->mean ?></td>
      <td><?= $obj->{'std.dev'} ?></td>
    </tr>
<?php } ?>
 </tbody>
</table>

<h2>Individual record analyses samples</h2>

<ul type="square">
  <li><a href="record.php?id=08501/Athena_Update_ProvidedCHO_Bildarchiv_Foto_Marburg_obj_00020602_1_024_377">08501/Athena_Update_ProvidedCHO_Bildarchiv_Foto_Marburg_obj_00020602_1_024_377</a></li>
  <li><a href="record.php?id=08501/Athena_Update_ProvidedCHO_Bildarchiv_Foto_Marburg_obj_20365830_BC_4_175_3">08501/Athena_Update_ProvidedCHO_Bildarchiv_Foto_Marburg_obj_20365830_BC_4_175_3</a></li>
  <li><a href="record.php?id=09404/id_oai_www_wbc_poznan_pl_22164">09404/id_oai_www_wbc_poznan_pl_22164</a></li>
  <li><a href="record.php?id=09404/id_oai_bbc_mbp_org_pl_342">09404/id_oai_bbc_mbp_org_pl_342</a></li>
</ul>

<h2>Dataset download</h2>

<p><a href="download.html">download page</a></p>

<footer>
  <p><a href="http://pkiraly.github.io/">What is this?</a> &ndash; about the Metadata Quality Assurance Framework project.</p>
</footer>
</div>

<script type="text/javascript">
$(document).ready(function() {
  $("#dataset").tablesorter();
});
</script>

<script type="text/javascript" src="http://d3js.org/d3.v2.js"></script>
<script type="text/javascript">
  var labelSource = [];
</script>
<script type="text/javascript" src="chart.js.php?filename=json/frequency.json&type=<?= $type ?>&target=frequency-chart&property=frequency"></script>
<script type="text/javascript" src="chart.js.php?filename=json/cardinality.json&type=<?= $type ?>&target=cardinality-chart&property=sum"></script>
<link rel="stylesheet" href="chart.css" />

</body>
</html>
