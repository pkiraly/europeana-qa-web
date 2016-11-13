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
<?php foreach ($fields as $fieldName => $label) { ?>
    <option value="<?= $fieldName ?>" <?php if ($fieldName == $field) { ?>selected="selected"<?php } ?>><?= $label ?></option>
<?php } ?>
  </select>

  <label for="type">Type: </label>
  <select name="type" onchange="this.form.submit();">
<?php foreach ($types as $typeName => $label) { ?>
    <option value="<?= $typeName ?>" <?php if ($typeName == $type) { ?>selected="selected"<?php } ?>><?= $label ?></option>
<?php } ?>
  </select>
</form>

<p>This chart shows the specified languages, and the number of records they are occured in.</p>

<?php print_r($problems); ?>

<div id="chart" class="chart"></div>

<script src="//d3js.org/d3.v3.min.js"></script>
<script type="text/javascript">
  var margin = { top: 50, right: 0, bottom: 100, left: 30 },
      width = 1000 - margin.left - margin.right,
      height = 1080 - margin.top - margin.bottom,
      gridSizeX = gridSizeY = 14,
      max = 0,
      // red palette
      // colors = ['#fff5f0', '#fee0d2', '#fcbba1', '#fc9272', '#fb6a4a', '#ef3b2c', '#cb181d', '#a50f15', '#67000d'],
      // blue palette
      colors = ['#fff7fb','#ece7f2','#d0d1e6','#a6bddb','#74a9cf','#3690c0','#0570b0','#045a8d','#023858'],
      datasets = ['<?= $summaryFile ?>']
      statistic = '<?= $statistic ?>';

  var rowSize = Math.floor(width / gridSizeX) - 1;
  console.log('rowSize: ' + rowSize);

  var svg = d3.select("#chart").append("svg")
          .attr("width", width + margin.left + margin.right)
          .attr("height", height + margin.top + margin.bottom)
          .append("g")
          .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

  d3.json(datasets[0], function(data) {
    max = data.max
    var i = 0;
    for (collection in data.values) {
      draw(collection, [data.values[collection].value], data.values[collection].name, i++);
    }
    height = Math.ceil(i / rowSize) * gridSizeY;
  });

  function draw(collection, value, name, n) {

    var cards = svg.selectAll(".hour")
        .data(value)
        .enter()
        .append("rect")
        .attr("x", function(d, j) {
          x = (n % rowSize) * gridSizeX;
          return x;
        })
        .attr("y", function(d, j) {
          y = Math.floor(n / rowSize) * gridSizeY;
          return y;
        })
        .attr("width", gridSizeX)
        .attr("height", gridSizeY)
        .style("fill", function(d) {
          perc = (max == 0 || d == 0) ? 0 : Math.floor((d / max) * colors.length);
          if (perc == colors.length)
            perc --;
          return colors[perc];
        })
        .on("click", function() {
          document.location = 'dataset.php?id=' + collection.substr(1) + '&type=d#' + statistic; 
          d3.event.stopPropagation();
        })
        .append("title")
        .text(function(d, j) {
          text = name + "\n"
               + "score: " + d
          return text;
        })
      ;
    }

  function formatField(field) {
    text = field.replace(/^(proxy|aggregation|agent|place|timespan|concept)_/, "$1/").replace('_', ':')
    text = text.substr(0, 1).toUpperCase() + text.substr(1)
    return text
  }

  function click(d) {
    console.log(d);
  }
</script>

<footer>
  <p><a href="http://pkiraly.github.io/">What is this?</a> &ndash; about the Metadata Quality Assurance Framework project.</p>
</footer>
</div>

<script type="text/javascript">
  var labelSource = [];
</script>
<link rel="stylesheet" href="chart.css" />

</body>
</html>
