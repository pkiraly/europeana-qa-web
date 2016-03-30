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
</head>
<body>

<div class="container">

<div class="page-header">
  <h1>Investigation of <?php if($type == 'c'){ ?>dataset<?php } else { ?>data provider<?php } ?><br/><?= $collectionId ?></h1>
  <h3><a href="./">Metadata Quality Assurance Framework</a></h3>
</div>

<!-- div class="col-md-12" -->

<?php if ($freqFileExists) { ?>
<h2>Field frequency</h2>

<p>This chart shows the frequency of the analyzed fields in the current record set. 100% means that the field is available in every records, 0 means that this field is never available. The numbers are rounded to 2 deci
mals.</p>

<div id="chart"></div>
<?php } ?>

<?php foreach ($graphs as $name => $function) { ?>
  <h2><?= $function['label'] ?></h2>

  <h3>Fields covered</h3>
  <p><?php print join($function['fields'], ", "); ?></p>

  <h3>Basic statistics</h3>
  <table>
  <?php foreach (get_object_vars($assocStat[$name]) as $key => $value) { ?>
    <?php if ($key == 'recMin') { ?>
      <tr><td>A record with minimal score</td><td><a href="record.php?id=<?= $value ?>"><?= $value ?></a></td>
    <?php } else if ($key == 'recMax') { ?>
      <tr><td>A record with maximal score</td><td><a href="record.php?id=<?= $value ?>"><?= $value ?></a></td>
    <?php } else { ?>
      <tr><td><?= $key ?></td><td><?= $value ?></td>
    <?php } ?>
  <?php } ?>
  </table>

  <h3>Graphs</h3>
  <img src="img/<?= $type . $id ?>/<?= $type . $id ?>-<?= $name ?>.png" />
<?php } ?>

<footer>
  <p><a href="http://pkiraly.github.io/">What is this?</a> &ndash; about the Metadata Quality Assurance Framework project.</p>
</footer>
</div>
</div>

<script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<?php if ($freqFileExists) { ?>
<script type="text/javascript" src="http://d3js.org/d3.v2.js"></script>
<script type="text/javascript" src="chart.js.php?freq=<?= $freqFile ?>&type=<?= $type ?>"></script>
<link rel="stylesheet" href="chart.css" />
<?php } ?>
</body>
</html>
