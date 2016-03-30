<?php $filename = $_GET['freq']; ?>
<?php $label = $_GET['label']; ?>
<?php $type = $_GET['type']; ?>
<?php $excludeZeros = isset($_GET['excludeZeros']) ? (int) $_GET['excludeZeros'] : 0; ?>
<?php $excludeOnes = isset($_GET['excludeOnes']) ? (int) $_GET['excludeOnes'] : 0; ?>
<?php $excludeRest = isset($_GET['excludeRest']) ? (int) $_GET['excludeRest'] : 0; ?>

var w = 700,
    h = 700,
    left_width = 220,
    excludeZeros = <?= $excludeZeros ?>,
    excludeOnes = <?= $excludeOnes ?>,
    excludeRest = <?= $excludeRest ?>
;

var labelSource = '<?php if (isset($label) && $label != '') { echo $label; } else { echo 'field'; } ?>';
if (labelSource == 'collectionId') {
  w -= 2*220;
  left_width += 2*220;
}

var svg = d3.select("#chart")
	.append("svg")
	.attr("width", left_width + w)
	.attr("height", h);

d3.json("<?= $filename; ?>", function(data) {
	var max_n = 0;
        if (excludeZeros == 1) {
          filtered = [];
          for (d in data)
            if (data[d].frequency != 0)
              filtered.push(data[d]);
          data = filtered;
        }
        if (excludeOnes == 1) {
          filtered = [];
          for (d in data)
            if (data[d].frequency != 1)
              filtered.push(data[d]);
          data = filtered;
        }
        if (excludeRest == 1) {
          console.log('exclude rest');
          filtered = [];
          for (d in data)
            if (!(data[d].frequency > 0 && data[d].frequency < 1))
              filtered.push(data[d]);
          data = filtered;
        }
	h = data.length * 20;
	svg.attr('height', h);
	for (var d in data) {
		max_n = Math.max(data[d].frequency, max_n);
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
		.attr("width", function(d, i) {return dx * d.frequency})
		.attr("height", dy);

	// percent
	var percents = svg.selectAll("text.score")
		.data(data).enter()
		.append("text")
		.attr("x", function(d) {return Math.max(left_width + 40, left_width + (w * d.frequency));})
		.attr("y", function(d, i){ return (dy * i) + 10; } )
		.attr("dx", -5)
		.attr("dy", ".36em")
		.attr("font-size", "12px")
		.attr("text-anchor", "end")
		.attr('class', function(d) {
			var classNames = 'score';
			if (parseInt(d.frequency * 100) == 0) {
				classNames += " small";
			}
			return classNames;
		})
		.text(function(d){return parseFloat(d.frequency * 100).toFixed(2) + "%";});

	// labels
	var text = svg.selectAll("text.label")
		.data(data).enter()
		.append("text")
		.attr("class", function(d, i) {return "field-name"})
		.attr("x", 5)
		.attr("y", function(d, i) {return dy*i + 15;})
		.attr("width", left_width)
		.text(function(d) {
                  // console.log(d);
                  label = d[labelSource];
                  if (labelSource == 'field') {
                    label = d[labelSource].replace("proxy_", "Proxy / ").replace("aggregation_", "Aggregation / ").replace("_", ":");
                  }
                  return label;
                  // return '<a href="field.php?field=' + d.field + '">' + label + '</a>';
                })
		.attr("float", "right")
                .on('click', function(d) {
                  if (labelSource == 'field') {
                    url = 'field.php?field=' + d[labelSource] + '&type=' + '<?php 
                      if (!isset($type) || $type == 'c' || $type == 'datasets') {
                        echo 'datasets';
                      } else {
                        echo 'data-providers';
                      } ?>';
                  } else {
                    url = 'dataset.php?id=' + d.id + '&name=' + d[labelSource] + '&type=' + d.type;
                  }
                  document.location = url;
                })
                .attr('cursor', 'pointer')
		// .attr("font-size", "12px")
				;
});
