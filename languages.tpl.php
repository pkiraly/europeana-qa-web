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
  <h1>Language frequency</h1>
  <h3><a href="./">Metadata Quality Assurance Framework</a></h3>
</div>

<form>
  <input type="hidden" name="collectionId" value="<?= $collectionId ?>" />

  <label for="field">Select field: </label>
  <select name="field" onchange="this.form.submit();">
<?php foreach ($languages as $fieldName => $languageCounts) { ?>
    <option value="<?= $fieldName ?>" <?php if ($fieldName == $field) { ?>selected="selected"<?php } ?>><?= $fields[$fieldName] ?></option>
<?php } ?>
  </select>

  <input type="checkbox" name="exclusions[]" value="0" id="excludeZeros" <?php if ($excludeZeros) { ?>checked="checked"<?php } ?> onchange="this.form.submit();" />
  <label for="excludeZeros">Exclude records without specified language</label>
  <input type="checkbox" name="exclusions[]" value="1" id="showNoInstances" <?php if ($showNoInstances) { ?>checked="checked"<?php } ?> onchange="this.form.submit();" />
  <label for="showNoInstances">Show records without field</label>
</form>

<p>This chart shows the specified languages, and the number of records they are occured in.
<ul type="square">
  <li>With 'Exclude records without specified language' option we can hide the fields without language specified.</li>
  <li>With 'Show records without field' option we can display the completeness of the field.</li>
</ul>
</p>

<div id="heatmap" class="chart"></div>

<script src="//d3js.org/d3.v3.min.js"></script>
<script>
var margin = {top: 40, right: 10, bottom: 10, left: 10},
    width = 960 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;

var color = d3.scale.category20c();

var treemap = d3.layout.treemap()
    .size([width, height])
    .sticky(true)
    .value(function(d) { return d.size; });

var heatmap = d3.select("#heatmap")
    .style("position", "relative")
    .style("width", (width + margin.left + margin.right) + "px")
    .style("height", (height + margin.top + margin.bottom) + "px")
    .style("left", margin.left + "px")
    .style("top", margin.top + "px");

d3.json('<?= $treeMapUrl ?>', function(error, root) {
  if (error) throw error;

  var node = heatmap.datum(root).selectAll(".node")
      .data(treemap.nodes)
    .enter().append("div")
      .attr("class", "node")
      .call(position)
      .style("background", function(d) { return d.children ? color(d.name) : null; })
      .text(function(d) { return d.children ? null : d.name; });

  d3.selectAll("input").on("change", function change() {
    var value = this.value === "count"
        ? function() { return 1; }
        : function(d) { return d.size; };

    node
        .data(treemap.value(value).nodes)
      .transition()
        .duration(1500)
        .call(position);
  });
});

function position() {
  this.style("left", function(d) { return d.x + "px"; })
      .style("top", function(d) { return d.y + "px"; })
      .style("width", function(d) { return Math.max(0, d.dx - 1) + "px"; })
      .style("height", function(d) { return Math.max(0, d.dy - 1) + "px"; });
}
</script>

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

<script type="text/javascript">
  var labelSource = [];
</script>
<!--
<script type="text/javascript" src="chart.js.php?filename=<?php echo urlencode('language-filter.php?field=' . $field . '&excludeZeros=' . (int)$excludeZeros); ?>&label=language&type=<?= $type ?>&target=language-chart&property=count"></script>
-->
<link rel="stylesheet" href="chart.css" />

</body>
</html>
