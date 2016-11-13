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

<p>This site shows the results of an experimental research. Our hypothesis is that structural features of metadata records might be indicators of the quality of the record. We measure a snapshot of <a href="http://europeana.eu">Europeana</a> created in the end of 2015. This snapshot contains more than 46 million records, however Europeana is an ever growing collection (the last year growth is 9.4%), so you can not find newer records in this snapshot. Currently we measure things related to the completeness of the record: field frequency, cardinality, metadata saturation, language coverage, and uniqueness and problem catalog. This research is running in close cooperation with Europeana Network's <a href="http://pro.europeana.eu/page/data-quality-committee">Data Quality Committee</a>. The methods applied here can be applicable to other metadata collections and the system is flexible to support that. You can find more info about the research <a href="http://pkiraly.github.io/">here</a>.<br/>
Note: this is a research project, the results here are experimental, they are subject of change.</p>

<div class="row">
  <div class="col-xs-3">
    <h3>Field frequency</h3>
    <p><a href="frequency.php"><img src="style/field-frequency-full.png" /></a></p>
    <p><a href="frequency.php">Field frequency chart.</a></p>
  </div>
  <div class="col-xs-3">
    <h3>Field cardinality</h3>
    <p><a href="cardinality.php"><img src="style/field-cardinality-full.png" /></a></p>
    <p><a href="cardinality.php">Field cardinality chart.</a></p>
  </div>
  <div class="col-xs-3">
    <h3>Language frequency</h3>
    <p><a href="languages.php?field=aggregated&exclusions[]=0"><img src="style/language-frequency-full.png" /></a></p>
    <p><a href="languages.php?field=aggregated&exclusions[]=0">Frequency of language specifications.</a></p>
  </div>
  <div class="col-xs-3">
    <h3>Multilingual saturation</h3>
    <p style="text-align: right;"><a href="saturation.php"><img src="style/multilingual-saturation-full.png"/></a></p>
    <p style="text-align: right;"><a href="saturation.php">Multilingual saturation chart.</a></p>
  </div>
</div>

<h2>Dataset analyses</h2>

<p>Meaning of the columns in the table: <strong>Minimum:</strong> The lowest score. <strong>Maximum:</strong> The highest score. <strong>Range:</strong> The difference between minimum and maximum. <strong>Median:</strong> The middle value. <strong>Mean:</strong> The mean (average) of the scores. <strong>Standard deviation:</strong> <a href="https://en.wikipedia.org/wiki/Standard_deviation">Standard deviation</a> - the amount of variation of a set. Close to 0 indicates that the data points tend to be very close to the mean of the set, while a high standard deviation indicates that the data points are spread out over a wider range of values.</p>

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

<?php if (!empty($problems)) { ?>
<h2>problems</h2>
<ul type="square">
  <?php foreach ($problems as $problem) { ?>
    <li><?= $problem ?>
  <?php } ?>
</ul>
<?php } ?>


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
