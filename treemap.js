function formUpdated(element) {
  collectionId = element.collectionId.value;
  field = element.field.value;
  targetId = element.targetId.value;
  excludeZeros = element.excludeZeros.checked;
  showNoInstances = element.showNoInstances.checked;
  updateData(collectionId, field, targetId, excludeZeros, showNoInstances);
  return false;
}

function updateData(collectionId, field, targetId) {
  updateData(collectionId, field, targetId, false, false);
}

function updateData(collectionId, field, targetId, excludeZeros, showNoInstances) {

  $('#tree-form input[name=field]').val(field);
  
  excludeZeros = (typeof excludeZeros !== 'undefined') ? excludeZeros : false;
  showNoInstances = (typeof showNoInstances !== 'undefined') ? showNoInstances : false;

  var url = 'plainjson2tree.php?collectionId=' + collectionId
          + '&field=' + field
          + '&excludeZeros=' + (excludeZeros ? 1 : 0)
          + '&showNoInstances=' + (showNoInstances ? 1 : 0);
  
  var margin = {top: 10, right: 10, bottom: 10, left: 10},
      width = 960, // - margin.left - margin.right,
      height = 500; // - margin.top - margin.bottom;

  var color = d3.scale.category20c();

  var treemap = d3.layout.treemap()
    .size([width, height])
    .sticky(true)
    .value(function(d) { return d.size; });

  d3.select(targetId).selectAll("*").remove();

  var div = d3.select(targetId)
    .style("position", "relative")
    // .style("width", width + "px")
    // .style("height", height + "px")
    .style("width", (width + margin.left + margin.right) + "px")
    .style("height", (height + margin.top + margin.bottom) + "px")
    .style("left", margin.left + "px")
    .style("top", margin.top + "px");

  d3.json(url, function(error, root) {
    if (error) throw error;

    var node = d3.select(targetId).datum(root).selectAll(".node")
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

  return false;
}

function position() {
  this.style("left", function(d) { return d.x + "px"; })
      .style("top", function(d) { return d.y + "px"; })
      .style("width", function(d) { return Math.max(0, d.dx - 1) + "px"; })
      .style("height", function(d) { return Math.max(0, d.dy - 1) + "px"; });
}
