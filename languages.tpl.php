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
  <h1>Language frequency</h1>
  <h3><a href="./">Metadata Quality Assurance Framework</a></h3>
</div>

<form>
  <label for="field">Select field: </label>
  <select name="field" onchange="this.form.submit();">
<?php foreach ($languages as $fieldName => $languageCounts) { ?>
    <option value="<?= $fieldName ?>" <?php if ($fieldName == $field) { ?>selected="selected"<?php } ?>><?= $fields[$fieldName] ?></option>
<?php } ?>
  </select>

  <input type="checkbox" name="exclusions[]" value="0" id="excludeZeros" <?php if ($excludeZeros) { ?>checked="checked"<?php } ?> onchange="this.form.submit();" />
  <label for="excludeZeros">Exclude records without specified language</label>
</form>

<p>This chart shows the specified languages, and the number of records they are occured in.</p>

<div id="language-chart" class="chart"></div>

<table>
  <?php if ($field == 'all') { ?>
    <?php foreach ($languages as $fieldName => $languageCounts) { ?>
      <tr>
        <td valign="top"><?= $fields[$fieldName] ?></td>
        <td>
          <ol>
            <?php foreach ($languageCounts as $language => $count) { ?>
              <li><strong><?= $language ?></strong>: <?= $count ?></li>
            <?php } ?>
          </ol>
        </td>
      </tr>
    <?php } ?>
  <?php } else { ?>
    <tr>
      <td valign="top"><?= $fields[$field] ?></td>
      <td>
        <ol>
          <?php foreach ($languages->$field as $language => $count) { ?>
            <li><strong><?= $language ?></strong>: <?= $count ?></li>
          <?php } ?>
        </ol>
      </td>
    </tr>
  <?php } ?>
</table>

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
<script type="text/javascript" src="chart.js.php?filename=<?php echo urlencode('language-filter.php?field=' . $field . '&excludeZeros=' . (int)$excludeZeros); ?>&label=language&type=<?= $type ?>&target=language-chart&property=count"></script>
<link rel="stylesheet" href="chart.css" />

</body>
</html>
