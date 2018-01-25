<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $collectionId ?> | <?= $title ?></title>
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
<style type="text/css">
.node {
  border: solid 1px white;
  font: 10px sans-serif;
  line-height: 12px;
  overflow: hidden;
  position: absolute;
  text-indent: 2px;
}
</style>
</head>
<body>

<div class="container">

<div class="page-header">
  <h1>Investigation of <?php if($type == 'c'){ ?>dataset<?php } else { ?>data provider<?php } ?><br/><?= $collectionId ?></h1>
  <h3><a href=".">Metadata Quality Assurance Framework for Europeana</a></h3>
</div>

<!-- div class="col-md-12" -->

<h2>Table of contents</h2>
<ul type="square">
  <li><a href="#n">Number of records</a></li>
  <li><a href="#frequency">Field frequency</a></li>
  <li><a href="#cardinality">Field cardinality</a></li>
  <li><a href="#languages">Languages</a></li>
</ul>

<table id="dataset" class="table table-condensed table-striped tablesorter">
  <thead>
    <tr>
      <th></th>
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
<?php
  $counter = 1; 
  foreach ($graphs as $metric => $info) {
    if (isset($assocStat[$metric])) { 
      $obj = $assocStat[$metric];
?>
    <tr>
      <td><?= $counter++ ?></td>
      <td><a href="#<?= $metric ?>"><?= $info['label'] ?></a></td>
      <td><?= $obj->min ?></td>
      <td><?= $obj->max ?></td>
      <td><?= $obj->range ?></td>
      <td><?= $obj->median ?></td>
      <td><?= $obj->mean ?></td>
      <td><?= $obj->{'std.dev'} ?></td>
    </tr>
  <?php } ?>
<?php } ?>
 </tbody>
</table>

<h2 id="n">Number of records</h2>
<p><?php echo number_format($n, 0, '.', ' '); ?></p>

<?php if ($freqFileExists) { ?>
  <h2 id="frequency">Field frequency</h2>

  <p>This chart shows the frequency of the analyzed fields in the current record set. 100% means that the field is available in every records, 0 means that this field is never available. The numbers are rounded to 2 decimals.</p>

  <div id="frequency-chart" class="chart"></div>
<?php } ?>

<?php if ($cardinalityFileExists) { ?>
  <h2 id="cardinality">Field cardinality</h2>

  <p>This chart shows the cardinality of the analyzed fields in the current record set.</p>
  
  <p>
    Select statistics:
    [<a href="<?php printf("dataset.php?id=%s&name=%s&type=%s&cardinality-property=%s", $id, $collectionId, $type, 'sum') ?>#cardinality">number of field instances</a>]
    [<a href="<?php printf("dataset.php?id=%s&name=%s&type=%s&cardinality-property=%s", $id, $collectionId, $type, 'mean') ?>#cardinality">average</a>]
    [<a href="<?php printf("dataset.php?id=%s&name=%s&type=%s&cardinality-property=%s", $id, $collectionId, $type, 'median') ?>#cardinality">median</a>]
  </p>

  <div id="cardinality-chart" class="chart"></div>
<?php } ?>

<?php foreach ($graphs as $name => $function) { ?>
  <h2 id="<?= $name ?>"><?= $function['label'] ?></h2>

  <?php if (isset($function['fields']) && !empty($function['fields'])) { ?>
    <h3>Fields covered</h3>
    <p><?php print join($function['fields'], ", "); ?></p>
  <?php } ?>

  <h3>Basic statistics</h3>
  <table>
  <?php foreach (get_object_vars($assocStat[$name]) as $key => $value) { ?>
    <?php if ($key == 'recMin') { ?>
      <tr><td>A record with minimal score</td><td><a href="../../record.php?id=<?= $value ?>"><?= $value ?></a></td>
    <?php } else if ($key == 'recMax') { ?>
      <tr><td>A record with maximal score</td><td><a href="../../record.php?id=<?= $value ?>"><?= $value ?></a></td>
    <?php } else { ?>
      <tr><td><?= $key ?></td><td><?= $value ?></td>
    <?php } ?>
  <?php } ?>
  </table>

  <?php if ($frequencyTable !== FALSE && isset($frequencyTable->$name)) { ?>
    <h3>Frequency table</h3>
    <table class="histogram">
      <tr>
        <td class="legend">values</td>
        <?php foreach ($frequencyTable->$name as $value => $frequency) { ?>
          <td><span title="<?= $value ?>"><?= sprintf("%.3f", $value); ?></span></td>
        <?php } ?>
      </tr>
      <tr>
        <td class="legend">count</td>
        <?php foreach ($frequencyTable->$name as $value => $frequency) { ?>
          <td><?= $frequency[0]; ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td class="legend">percentage</td>
        <?php foreach ($frequencyTable->$name as $value => $frequency) { ?>
          <td><span title="<?= $frequency[0] * 100 / $n; ?>"><?php printf("%.2f%%", ($frequency[0] * 100 / $n)); ?></span></td>
        <?php } ?>
      </tr>
    </table>
  <?php } ?>

  <?php if ($histograms !== FALSE && isset($histograms->$name)) { ?>
    <h3>Histogram</h3>
    <table class="histogram">
      <tr>
        <td class="legend">range of values</td>
        <?php for($i = 0; $i < count($histograms->$name); $i++) { ?>
          <td><?= $histograms->{$name}[$i]->label; ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td class="legend">count</td>
        <?php for($i = 0; $i < count($histograms->$name); $i++) { ?>
          <td><?= $histograms->{$name}[$i]->count; ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td class="legend">percentage</td>
        <?php for($i = 0; $i < count($histograms->$name); $i++) { ?>
          <td><?php printf("%.2f%%", $histograms->{$name}[$i]->density); ?></td>
        <?php } ?>
      </tr>
    </table>

  <?php } ?>

  <?php if (file_exists('img/' . $type . $id . '/' . $type . $id . '-' . strtolower($name) . '.png')) { ?>
    <h3>Graphs</h3>
    <img src="img/<?= $type . $id ?>/<?= $type . $id ?>-<?= $name ?>.png" height="300" />
  <?php } ?>
<?php } ?>

<h2 id="languages">Languages</h2>
<form id="tree-form" onchange="formUpdated(this);" >
  <input type="hidden" name="collectionId" value="<?= $type . $id ?>" />
  <input type="hidden" name="field" value="aggregated" />
  <input type="hidden" name="targetId" value="#language-treemap" />
  <input type="checkbox" name="excludeZeros" value="0" id="excludeZeros"/>
  <label for="excludeZeros">Exclude records without specified language</label>
  <input type="checkbox" name="showNoInstances" value="1" id="showNoInstances"/>
  <label for="showNoInstances">Show records without field</label>
</form>
<div id="language-treemap"></div>
<table>
  <?php foreach ($languages as $fieldName => $languageCounts) { ?>
    <?php if (!(count(get_object_vars($languageCounts)) == 1 && isset($languageCounts->{'no field instance'}))) { ?>
      <tr>
        <td valign="top">
          <a href="#" onclick="updateData('<?= $type . $id ?>', '<?= $fieldName ?>', '#language-treemap'); return false;"><?= 
          isset($fields[$fieldName]) ? $fields[$fieldName] : $fieldName ?></a>
        </td>
        <td>
          <ol>
            <?php foreach ($languageCounts as $language => $count) { ?>
              <li><strong><?= $language ?></strong>: <?= number_format($count, 0, ' ', ' '); ?></li>
            <?php } ?>
          </ol>
        </td>
      </tr>
    <?php } ?>
  <?php } ?>
</table>

<footer>
  <p><a href="http://pkiraly.github.io/">What is this?</a> &ndash; about the Metadata Quality Assurance Framework project.</p>
</footer>
</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  $("#dataset").tablesorter();
});
</script>

<script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<?php if ($freqFileExists || $cardinalityFileExists ) { ?>
  <script type="text/javascript" src="http://d3js.org/d3.v3.js"></script>
  <script type="text/javascript" src="treemap.js"></script>
  <script type="text/javascript">
    updateData('<?= $type . $id ?>', 'aggregated', '#language-treemap', false, false);
    var labelSource = [];
  </script>
  <?php if ($freqFileExists) { ?>
    <script type="text/javascript" src="chart.js.php?filename=<?= $freqFile ?>&type=<?= $type ?>&target=frequency-chart&property=frequency"></script>
  <?php } ?>
  <?php if ($cardinalityFileExists) { ?>
    <script type="text/javascript" src="chart.js.php?filename=<?= $cardinalityFile ?>&type=<?= $type ?>&target=cardinality-chart&property=<?= $cardinalityProperty ?>"></script>
  <?php } ?>
  <link rel="stylesheet" href="chart.css" />
<?php } ?>
</body>
</html>
