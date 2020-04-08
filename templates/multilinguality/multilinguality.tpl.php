<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $collectionId ?> | <?= $title ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.1.0/styles/default.min.css" />
  <!-- 
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
  -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway" type="text/css" />
  <link rel="stylesheet" href="europeana-qa.css" />
  <!-- choose a theme file -->
  <link rel="stylesheet" href="jquery/theme.default.min.css">
  <!-- load jQuery and tablesorter scripts -->
  <!-- 
  <script type="text/javascript" src="jquery/jquery-1.2.6.min.js"></script>
   -->
  <script type="text/javascript" src="jquery/jquery-1.9.1.min.js"></script>
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

<h2 id="n">Multilinguality scores</h2>
<table id="generic" class="table table-condensed table-striped tablesorter">
  <thead>
    <tr>
      <th></th>
      <th></th>
      <th colspan="6">Provider Proxy</th>
      <th colspan="6">Europeana Proxy</th>
      <th colspan="6">Full object</th>
    </tr>
    <tr>
      <th></th>
      <th>Field</th>
<?php foreach ($generic_prefixes as $prefix) { ?>
      <th>Min</th>
      <th>Max</th>
      <th>Range</th>
      <th>Median</th>
      <th>Mean</th>
      <th>St. dev.</th>
<?php } ?>
    </tr>
  </thead>
  <tbody>
<?php
  $counter = 1; 
  foreach ($assocStat['generic'] as $metric => $proxies) {
?>
    <tr>
      <td><?= $counter++ ?></td>
      <td><a href="#<?= $proxies['provider']->_row ?>"><?= $fields[$metric] ?></a></td>
<?php foreach ($generic_prefixes as $prefix => $label) { ?>
      <td><?= conditional_format($proxies[$prefix]->min); ?></td>
      <td><?= conditional_format($proxies[$prefix]->max); ?></td>
      <td><?= conditional_format($proxies[$prefix]->range); ?></td>
      <td><?= conditional_format($proxies[$prefix]->median); ?></td>
      <td><?= conditional_format($proxies[$prefix]->mean); ?></td>
      <td><?= conditional_format($proxies[$prefix]->{'std.dev'}); ?></td>
<?php } ?>
    </tr>
<?php } ?>
 </tbody>
</table>

<?php if ($id != 'all') { ?>
<h2 id="n">Field level multilinguality</h2>

<div>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist" id="myTabs">
<?php foreach ($specific_types as $specific_type => $type_label) { ?>
    <li role="presentation"<?php if ($specific_type == 'taggedliterals') { ?> class="active"<?php } ?>>
      <a href="#<?= $specific_type ?>-panel" role="tab" id="<?= $specific_type ?>-tab"  data-toggle="tab" aria-controls="tab<?= $specific_type ?>">
        <?= $type_label ?>
      </a>
    </li>
<?php } ?>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content" id="myTabContent">
<?php foreach ($specific_types as $specific_type => $type_label) { ?>
    <div role="tabpanel" 
         class="tab-pane fade<?php if ($specific_type == 'taggedliterals') { ?> in active<?php } ?>"
         id="<?= $specific_type ?>-panel"
         >
    <table id="specific-<?= $specific_type ?>" class="table table-condensed table-striped tablesorter">
      <thead>
        <tr>
          <th></th>
          <th></th>
          <th colspan="6">Provider Proxy</th>
          <th colspan="6">Europeana Proxy</th>
        </tr>
        <tr>
          <th></th>
          <th>Field</th>
<?php foreach ($specific_prefixes as $prefix) { ?>
          <th>Min</th>
          <th>Max</th>
          <th>Range</th>
          <th>Median</th>
          <th>Mean</th>
          <th>St. dev.</th>
<?php } ?>
        </tr>
      </thead>
      <tbody>
<?php
  $counter = 1; 
  foreach ($assocStat['specific'][$specific_type] as $metric => $proxies) {
?>
        <tr>
          <td><?= $counter++ ?></td>
          <td><a href="#<?= $proxies['provider']->_row ?>"><?= $fields[$metric]; ?></a></td>
<?php foreach ($specific_prefixes as $prefix) { ?>
          <td><?= conditional_format($proxies[$prefix]->min); ?></td>
          <td><?= conditional_format($proxies[$prefix]->max); ?></td>
          <td><?= conditional_format($proxies[$prefix]->range); ?></td>
          <td><?= conditional_format($proxies[$prefix]->median); ?></td>
          <td><?= conditional_format($proxies[$prefix]->mean); ?></td>
          <td><?= conditional_format($proxies[$prefix]->{'std.dev'}); ?></td>
<?php } ?>
        </tr>
<?php } ?>
     </tbody>
    </table>
  </div>
<?php } ?>
  </div>
</div>
<?php } // end of field level multilinguality ?>

<h2 id="n">Number of records</h2>
<p><?php echo number_format($count, 0, '.', ' '); ?></p>

<h2>Generic metrics</h2>
<?php foreach ($assocStat['generic'] as $metric => $proxies) { ?>
  <?php $name = $proxies['provider']->_row; ?>
<a name="<?= $proxies['provider']->_row ?>"></a>
<h3><?= $fields[$metric] ?></h3>

<?php foreach ($generic_prefixes as $prefix => $label) { ?>
  <?php $name = $proxies[$prefix]->_row; ?>
  <h4><?= $label ?> <a class="qa-show-details <?= $name ?>" href="#">Show details</a></h4>

  <div class="qa-details" id="details-<?= $name ?>">
    <h5>Basic statistics</h5>
    <table class="qa-basic-statistics">
    <?php $basicStatCounter = 1; ?>
    <?php foreach (get_object_vars($proxies[$prefix]) as $key => $value) { ?>
      <?php if ($key != 'recMin' && $key != 'recMax' && $key != '_row') { ?>
      <?php if ($basicStatCounter % 4 == 1) { ?>
        <tr>
      <?php } ?>
          <td class="qa-label"><?= labelsInBasicStatistics($key) ?></td>
          <td class="qa-value"><?= qa_format($value) ?></td>
      <?php if ($basicStatCounter % 4 == 0) { ?>
        </tr>
      <?php } ?>
      <?php $basicStatCounter++ ?>
      <?php } ?>
    <?php } ?>
    </table>

<?php if ($id != 'all') { ?>
    <p>
      A record with minimal score: <a href="../../record.php?id=<?= $proxies[$prefix]->recMin ?>"><?= $proxies[$prefix]->recMin ?></a><br/>
      A record with maximal score: <a href="../../record.php?id=<?= $proxies[$prefix]->recMax ?>"><?= $proxies[$prefix]->recMax ?></a>
    </p>
<?php } ?>

    <?php if ($frequencyTable !== FALSE && isset($frequencyTable->$name)) { ?>
      <h5>Frequency table</h5>
      <table class="histogram">
        <tr>
          <td class="legend">values</td>
          <?php foreach ($frequencyTable->$name as $value => $frequency) { ?>
            <td><span title="<?= $value ?>"><?= sprintf("%.3f", $value); ?></span></td>
          <?php } ?>
        </tr>
        <tr>
          <td class="legend">number of objects</td>
          <?php foreach ($frequencyTable->$name as $value => $frequency) { ?>
            <td><?= number_format($frequency[0]); ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td class="legend">percentage</td>
          <?php foreach ($frequencyTable->$name as $value => $frequency) { ?>
            <td><span title="<?= $frequency[0] * 100 / $count; ?>"><?php printf("%.2f%%", ($frequency[0] * 100 / $count)); ?></span></td>
          <?php } ?>
        </tr>
      </table>
    <?php } ?>

    <?php if ($histograms !== FALSE && isset($histograms[$name])) { ?>
      <h5>Histogram</h5>
      <table class="histogram">
        <tr>
          <td class="legend">range of values</td>
          <?php for ($i = 0; $i < count($histograms[$name]); $i++) { ?>
            <td><?= format_histogram_range($histograms[$name][$i]->label, $i == (count($histograms[$name]) - 1)); ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td class="legend">number of objects</td>
          <?php for ($i = 0; $i < count($histograms[$name]); $i++) { ?>
            <td><?= number_format($histograms[$name][$i]->count); ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td class="legend">percentage</td>
          <?php for($i = 0; $i < count($histograms[$name]); $i++) { ?>
            <td><?php echo ($histograms[$name][$i]->count == 0 ? '0%' : sprintf("%.2f%%", $histograms[$name][$i]->density)); ?></td>
          <?php } ?>
        </tr>
      </table>
    <?php } ?>

    <?php if ($normalizedHistograms !== FALSE && isset($normalizedHistograms[$name])) { ?>
      <h5>Normalized histogram</h5>
      <table class="histogram">
        <tr>
          <td class="legend">range of values</td>
          <?php for($i = 0; $i < count($normalizedHistograms[$name]); $i++) { ?>
            <td><?= format_histogram_range($normalizedHistograms[$name][$i]->label); ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td class="legend">number of objects</td>
          <?php for ($i = 0; $i < count($normalizedHistograms[$name]); $i++) { ?>
            <td><?= number_format($normalizedHistograms[$name][$i]->count); ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td class="legend">percentage</td>
          <?php for ($i = 0; $i < count($normalizedHistograms[$name]); $i++) { ?>
            <td><?php echo ($normalizedHistograms[$name][$i]->count == 0 ? '0%' : sprintf("%.2f%%", $normalizedHistograms[$name][$i]->density)); ?></td>
          <?php } ?>
        </tr>
      </table>
    <?php } ?>
  </div>

  <?php if (file_exists('img/' . $type . $id . '/' . $type . $id . '-' . $proxies[$prefix]->_row . '.png')) { ?>
    <img src="img/<?= $type . $id ?>/<?= $type . $id ?>-<?= $proxies[$prefix]->_row ?>.png" height="300" />
  <?php } ?>

<?php } ?>

<?php } ?>

<p>message: <?= $message ?></p>

<footer>
  <p><a href="http://pkiraly.github.io/">What is this?</a> &ndash; about the Metadata Quality Assurance Framework project.</p>
</footer>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript">
$(document).ready(function() {
  $("#generic").tablesorter();
  $("#specific-taggedliterals").tablesorter();
  $("#specific-languages").tablesorter();
  $("#specific-literalsperlanguage").tablesorter();
});
$(function() {
  $('#myTabs a:first').tab('show');
  // $('#tablanguages').tab
});
</script>
<script type="text/javascript">
$("a.qa-show-details").click(function(event) {
  event.preventDefault();
  id = $(this).attr('class').replace('qa-show-details ', '#details-');
  $(id).toggle();
});
</script>

</body>
</html>
