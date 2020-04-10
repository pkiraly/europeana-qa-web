function startInteractiveTimeline() {
  var timeline_w = 500;
  var timeline_h = 100;
  var timeline_barPadding = 2;

  var svg = d3.select("div#svg-container")
  .append("svg")
  .attr("width", timeline_w)
  .attr("height", timeline_h);

  var dataset = [];
  $('table.timeline td.property').on('click', function(e) {
    dataset = [];

    $(this).siblings('td').each(function() {
      if ($(this).hasClass('num') && $(this).attr('data') !== typeof undefined) {
        var value = Number($(this).attr('data'));  // or $(this).html()
        dataset.push(value);
      }
    });

    $("#svg-container svg").children().each(function(e) {$(this).remove()})
    var max = d3.max(dataset);
    var min = d3.min(dataset);
    var range = max - min;
    var minmaxPadding = range / 4;

    var yScale = d3.scale.linear()
    .domain([min - minmaxPadding, max + minmaxPadding])
    .range([0, timeline_h]);

    svg.selectAll("rect")
    .data(dataset)
    .enter()
    .append("rect")
    .transition()
    .duration(1000)
    .attr("x", function(d, i) {
      return i * (timeline_w / dataset.length);
    })
    .attr("y", function(d) {
      return timeline_h - yScale(d);
    })
    .attr("width", timeline_w / dataset.length - timeline_barPadding)
    .attr("height", function(d) {
      return yScale(d);
    })
    .attr('fill', '#ccc')
    ;

    svg.selectAll("text")
    .data(dataset)
    .enter()
    .append("text")
    .text(function(d) {
      return Math.ceil(d * 1000) / 1000;
    })
    .attr("x", function(d, i) {
      return i * (timeline_w / dataset.length) + (timeline_w / dataset.length - timeline_barPadding) / 2;
    })
    .attr("y", function(d) {
      return timeline_h - yScale(d) + 14;
    })
    .attr("font-family", "sans-serif")
    .attr("font-size", "11px")
    .attr("fill", "white")
    .attr("text-anchor", "middle")
    ;

  });
}
