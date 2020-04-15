var timeline_w = 500;
var timeline_h = 200;
var timeline_barPadding = 2;

function startInteractiveTimeline(targetId, tableClass) {

  var svg = d3.select('div#' + targetId)
              .append("svg")
              .attr("width", timeline_w)
              .attr("height", timeline_h);

  var dataset = [];
  $('table.' + tableClass + ' td.property').on('click', function(e) {
    $('table.' + tableClass + ' td.highlighted').each(function(e) {
      $(this).removeClass('highlighted');
    });

    var oTarget = $('div#' + targetId);
    oTarget.css('position', 'absolute');
    oTarget.show();
    dataset = [];

    var x = null,
        y = null,
        i = 0;
    $(this).siblings('td').each(function() {
      if ($(this).hasClass('num')) {
        if ($(this).attr('data') !== typeof undefined) {
          var value = Number($(this).attr('data'));  // or $(this).html()
          dataset.push({
            'version': versions[i],
            'date': d3.timeParse("v%Y-%m")(versions[i]),
            'value': value
          });
          i++;
          if (x == null) {
            x = $(this).position().left + 15;
            y = $(this).offset().top
          }
        }
        $(this).addClass('highlighted');
      }
    });
    console.log(dataset);
    oTarget.animate({"top": (y - 510) + 'px', "left": x + 'px'}, "fast");
    oTarget.on('click', function() {
      oTarget.hide(1000);
      $('table.' + tableClass + ' td.highlighted').each(function(e) {
        $(this).removeClass('highlighted');
      });
    })

    $('div#' + targetId + " svg").children().each(function(e) {$(this).remove()})

    // drawBarchart(svg, dataset);
    drawLinechart(svg, dataset);

  });
}

function drawBarchart(svg, dataset) {
  var max = d3.max(dataset, d => d.value);
  var min = d3.min(dataset, d => d.value);
  var range = max - min;
  var minmaxPadding = (range != 0) ? range / 4 : 1.0;

  var yScale = d3.scale.linear()
    // .domain([min - minmaxPadding, max + minmaxPadding])
    // .domain([min, max])
    .domain([0, max])
    .range([0, timeline_h])
  ;

  svg.selectAll("rect")
     .data(dataset)
     .enter()
     .append("rect")
     .attr("x", function(d, i) {
       return i * (timeline_w / dataset.length);
     })
     .attr("y", function(d) {
       return timeline_h - yScale(d.value);
     })
     .attr("width", timeline_w / dataset.length - timeline_barPadding)
     .attr("height", function(d) {
       return yScale(d.value);
     })
     .attr('fill', '#666')
  ;

  svg.selectAll("text")
     .data(dataset)
     .enter()
     .append("text")
     .text(function(d) {
       return Math.ceil(d.value * 1000) / 1000;
     })
     .attr("x", function(d, i) {
       return i * (timeline_w / dataset.length) + (timeline_w / dataset.length - timeline_barPadding) / 2;
     })
     .attr("y", function(d) {
       return timeline_h - yScale(d.value) + 14;
     })
     .attr("font-family", "sans-serif")
     .attr("font-size", "11px")
     .attr("fill", "white")
     .attr("text-anchor", "middle")
  ;

}

function drawLinechart(svg, dataset) {
  var x = d3.scaleTime()
            .domain(d3.extent(dataset, function(d) { return d.date; }))
            .range([0, timeline_w]);

  svg.append("g")
     .attr("transform", "translate(20," + (timeline_h - 90) + ")")
     .call(d3.axisBottom(x));

  // Add Y axis
  var miny = d3.min(dataset, function(d) { return d.value; })
  var maxy = d3.max(dataset, function(d) { return d.value; })
  var y = d3.scaleLinear()
            .domain([miny / 2, maxy * 1.1])
            .range([timeline_h, 0]);

  /*
  svg.append("g")
     .call(d3.axisLeft(y));
   */

  // Add the line
  svg.append("path")
     .datum(dataset)
     .attr("fill", "none")
     .attr("stroke", "steelblue")
     .attr("stroke-width", 1.5)
     .attr("d", d3.line()
       .x(function(d) { return x(d.date) + 20 })
       .y(function(d) { return y(d.value) })
     )
  ;

  svg.append("g")
     .selectAll("text")
     .data(dataset)
     .enter()
     .append("text")
     .text(function(d) {
       return Math.round(d.value * 1000) / 1000;
     })
     .attr("x", function(d, i) {
       return x(d.date) + 20;
     })
     .attr("y", function(d) {
       return y(d.value) + 15;
     })
     .attr("font-family", "sans-serif")
     .attr("font-size", "11px")
     .attr("fill", "maroon")
     .attr("text-anchor", "middle")
  ;

  svg.selectAll("myCircles")
     .data(dataset)
     .enter()
     .append("circle")
     .attr("fill", "maroon")
     .attr("stroke", "none")
     .attr("cx", function(d) {return x(d.date) + 20;})
     .attr("cy", function(d) {return y(d.value);})
     .attr("r", 3)
  ;
}