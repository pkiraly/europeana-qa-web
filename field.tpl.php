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

<div class="page-header">
  <h1>Field frequency of '<?= $fields[$field]; ?>' per <?php if($type == 'd' || $type == 'data-providers'){ ?>data providers<?php } else { ?>datasets<?php } ?></h1>
  <h3><a href="./">Metadata Quality Assurance Framework</a></h3>
</div>

<form>
  <label for="field">Select field: </label>
  <select name="field" onchange="this.form.submit();">
<?php foreach ($fields as $fieldName => $label) { ?>
    <option value="<?= $fieldName ?>" <?php if ($fieldName == $field) { ?>selected="selected"<?php } ?>><?= $label ?></option>
<?php } ?>
  </select>

  <label for="type">groupped by </label>
  <select name="type" onchange="this.form.submit();">
<?php foreach ($types as $name => $label) { ?>
    <option value="<?= $name ?>" <?php if ($name == $type) { ?>selected="selected"<?php } ?>><?= $label ?></option>
<?php } ?>
  </select>

  exclude frequencies
  <input type="checkbox" name="exclusions[]" value="0" id="excludeZeros" <?php if ($excludeZeros) { ?>checked="checked"<?php } ?> onchange="this.form.submit();" />
  <label for="excludeZeros">0%</label>

  <input type="checkbox" name="exclusions[]" value="2" id="excludeRest" <?php if ($excludeRest) { ?>checked="checked"<?php } ?> onchange="this.form.submit();" />
  <label for="excludeRest">between 0% and 100%</label>

  <input type="checkbox" name="exclusions[]" value="1" id="excludeOnes" <?php if ($excludeOnes) { ?>checked="checked"<?php } ?> onchange="this.form.submit();" />
  <label for="excludeOnes">100%</label>
</form>

<p>This chart shows the frequency of the analyzed fields in all records. 100% means that the field is available in every records, 0 means that this field is never available. The numbers are rounded to 2 decimals.</p>

<div id="chart"></div>

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
<script type="text/javascript" src="chart.js.php?freq=<?= $fieldSummaryFile ?>&label=collectionId&type=<?= $type ?>&excludeZeros=<?= (int)$excludeZeros ?>&excludeOnes=<?= (int)$excludeOnes ?>&excludeRest=<?= $excludeRest ?>"></script>
<link rel="stylesheet" href="chart.css" />

</body>
</html>
