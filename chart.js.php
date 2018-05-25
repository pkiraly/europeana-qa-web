<?php $filename = $_GET['filename']; ?>
<?php $label = $_GET['label']; ?>
<?php $type = $_GET['type']; ?>
<?php $targetID = $_GET['target']; ?>
<?php $property = $_GET['property']; ?>
<?php $id = $targetID . '-' . $property; ?>
<?php $property = $_GET['property']; ?>
<?php $excludeZeros = isset($_GET['excludeZeros']) ? (int) $_GET['excludeZeros'] : 0; ?>
<?php $excludeOnes = isset($_GET['excludeOnes']) ? (int) $_GET['excludeOnes'] : 0; ?>
<?php $excludeRest = isset($_GET['excludeRest']) ? (int) $_GET['excludeRest'] : 0; ?>

var w = 700,
    h = 700,
    left_width = 220;

labelSource['<?= $id ?>'] = '<?php if (isset($label) && $label != '') { echo $label; } else { echo 'field'; } ?>';
if (labelSource['<?= $id ?>'] == 'collectionId') {
  w -= 2*220;
  left_width += 2*220;
}

d3.json("<?= $filename; ?>", function(data) {

  var svg = d3.select('#<?= $targetID ?>')
    .append("svg")
    .attr("width", left_width + w)
    .attr("height", h);

  var property = '<?= $property ?>';
  var excludeZeros = <?= $excludeZeros ?>;
  var excludeOnes = <?= $excludeOnes ?>;
  var excludeRest = <?= $excludeRest ?>;

  var max_n = 0;
  if (excludeZeros == 1) {
    filtered = [];
    for (d in data)
      if (data[d][property] != 0)
        filtered.push(data[d]);
      data = filtered;
  }
  if (excludeOnes == 1) {
    filtered = [];
      for (d in data)
        if (data[d][property] != 1)
          filtered.push(data[d]);
    data = filtered;
  }
  if (excludeRest == 1) {
    filtered = [];
    for (d in data)
      if (!(data[d][property] > 0 && data[d][property] < 1))
        filtered.push(data[d]);
    data = filtered;
  }

  h = data.length * 20;
  svg.attr('height', h);

  for (var d in data) {
    max_n = Math.max(data[d][property], max_n);
  }

  var dx = w / max_n;
  var dy = h / data.length;

  // bars
  var bars = svg.selectAll(".bar")
    .data(data).enter()
    .append("rect")
    .attr("class", function(d, i) {return "bar plums";})
    .attr("x", function(d, i) {return left_width;})
    .attr("y", function(d, i) {return dy * i;})
    .attr("width", function(d, i) {return dx * d[property]})
    .attr("height", dy)
  ;

  // percent
  var percents = svg.selectAll("text.score")
    .data(data).enter()
    .append("text")
    .attr("x", function(d) {
      var offset = (w * (d[property] / max_n));
      var labelSize = getLabelSize(d[property], property);
      if (offset < labelSize)
        offset += labelSize;
      return left_width + offset;
    })
    .attr("y", function(d, i){ return (dy * i) + 10; } )
    .attr("dx", -5)
    .attr("dy", ".36em")
    .attr("font-size", "12px")
    .attr("text-anchor", "end")
    .attr('class', function(d) {
      var classNames = 'score';
      var offset = (w * (d[property] / max_n));
      var labelSize = getLabelSize(d[property], property);
      if (offset < labelSize)
        classNames += " small";
      return classNames;
    })
    .text(function(d){
      return formatNumber(d[property], property);
    })
  ;

  // labels
  var text = svg.selectAll("text.label")
    .data(data).enter()
    .append("text")
    .attr("class", function(d, i) {return "field-name"})
    .attr("x", 5)
    .attr("y", function(d, i) {return dy*i + 15;})
    .attr("width", left_width)
    .text(function(d) {
      label = d[labelSource['<?= $id ?>']];
      if (labelSource['<?= $id ?>'] == 'field') {
        label = d[labelSource['<?= $id ?>']]
          .replace("proxy_", "Proxy / ")
          .replace("aggregation_", "Aggregation / ")
          .replace("place_", "Place / ")
          .replace("providedcho_", "ProvidedCHO / ")
          .replace("agent_", "Agent / ")
          .replace("timespan_", "Timespan / ")
          .replace("concept_", "Concept / ")
          .replace("_", ":");
      }
      return label;
      // return '<a href="field.php?field=' + d.field + '">' + label + '</a>';
    })
    .attr("float", "right")
    .on('click', function(d) {
      if (labelSource['<?= $id ?>'] == 'field') {
        url = 'field.php?field=' + d[labelSource['<?= $id ?>']] + '&type=' + '<?php 
          if (!isset($type) || $type == 'c' || $type == 'datasets') {
            echo 'datasets';
          } else {
            echo 'data-providers';
          } ?>';
      } else {
        url = 'dataset.php?id=' + d.id + '&name=' + d[labelSource['<?= $id ?>']] + '&type=' + d.type;
      }
      document.location = url;
    })
    .attr('cursor', 'pointer')
    // .attr("font-size", "12px")
  ;
});

function formatNumber(value, property) {
  var text = "" + value;
  if (property == 'frequency')
    text = parseFloat(value * 100).toFixed(2) + "%";
  else if (value > 1000) {
    text = ("" + value).replace(/./g, function(c, i, a) {
      return i && c !== "." && ((a.length - i) % 3 === 0) ? ' ' + c : c;
    });
  }
  return text;
}

function getLabelSize(value, property) {
  var label = formatNumber(value, property);
  var labelSize = (label.length + (label.indexOf('%') === -1 ? 1 : 2)) * 6;
  return labelSize;
}