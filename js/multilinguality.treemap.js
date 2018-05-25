$('#language-distribution-selector').on('change', function () {
  displayLanguageTreemap();
});
$('#excludeZeros').on('change', function () {
  displayLanguageTreemap();
});
$('#showNoInstances').on('change', function () {
  displayLanguageTreemap();
});

var margin = {top: 10, right: 10, bottom: 10, left: 10},
  width  = 850 - margin.left - margin.right,
  height = 500 - margin.top  - margin.bottom;

var color = d3.scale.category20c();

var node;

// var tooltipDiv = d3.select("body").append("div")
var tooltipDiv = d3.select("#tooltip")
  .style("padding-top", margin.top + "px")
  // .attr("class", "tooltip")
  // .style("opacity", 0)
  ;

displayLanguageTreemap('aggregated');

function getTreeMapUrl() {
  var field = $('#language-distribution-selector').val();
  var excludeZeros = $('#excludeZeros').is(':checked') ? 1 : 0;
  var showNoInstances = 0; //$('#showNoInstances').is(':checked') ? 0 : 1;

  var treeMapUrl = 'plainjson2tree.php?field=' + field
    + '&excludeZeros=' + excludeZeros //  . (int)$excludeZeros
    + '&showNoInstances=' + showNoInstances // . (int)$showNoInstances
    + '&collectionId=' + collectionId
    + '&version=' + version;
  return treeMapUrl;
}

function displayLanguageTreemap() {
  var treemap = d3.layout.treemap()
    .size([width, height])
    .sticky(true)
    .value(function(d) { return d.size; });

  var heatmap = d3.select("#heatmap")
    .style("position", "relative")
    .style("width", (width + margin.left + margin.right) + "px")
    .style("height", (height + margin.top + margin.bottom) + "px")
    .style("float", "left")
    .style("left", margin.left + "px")
    .style("top", margin.top + "px");

  d3.json(getTreeMapUrl(), function(error, root) {
    if (error) throw error;

    d3.selectAll('#heatmap .node').remove();

    node = heatmap
      .datum(root)
      .selectAll(".node")
      .data(treemap.nodes)
      .enter().append("div")
      .attr("class", "node")
      .call(position)
      .style("color", '#ddd')
      .style('cursor', 'pointer')
      .style("background", function(d) {
        // return d.children ? color(d.name) : null;
        return d.children ? '#3182bd' : null; //
      })
      .text(function(d) {
        if (d.children) {
          return null;
        } else {
          if (d.name == 'no language') {
            text = 'literal without language tag';
          } else if (d.name == 'resource') {
            text = 'resource value (URI)';
          } else {
            text = d.name;
          }
          return text;
        }
      })
      .on("click", function(d) {
        tooltipDiv.transition()
             .duration(200)
             .style("opacity", 1);
        tooltipDiv.html(label2(d, root.name))
             // .style("left", (d3.event.pageX) + "px")
             // .style("top", (d3.event.pageY - 28) + "px");
      })
/*
      .on("mouseout", function(d) {
        div.transition()
           .duration(500)
           .style("opacity", 0);
      })
*/
    ;
  });
}

function position() {
  this.style("left", function(d) { return d.x + "px"; })
    .style("top", function(d) { return d.y + "px"; })
    .style("width", function(d) { return Math.max(0, d.dx - 1) + "px"; })
    .style("height", function(d) { return Math.max(0, d.dy - 1) + "px"; });
}

function label(d) {
  var text = '';
  if (d.name == 'no language') {
    text = 'literal without language tag';
  } else if (d.name == 'resource') {
    text = 'resource value (URI)';
  } else if (d.name == 'no field instance') {
    text = d.name;
  } else {
    text = 'language code: ' + d.name;
  }
  if (d.size !== undefined) {
    var count = d.size.toString().replace(/./g, function(c, i, a) {
      return i && c !== "." && ((a.length - i) % 3 === 0) ? ',' + c : c;
    });
    text += "\n" + 'number of field instances: ' + count;
  }
  return text;
}

function label2(d, fieldName) {
  var language = d.name;
  var text = ''; //fieldName + "<br>\n";
  var isARealLanguageCode = false;
  if (language == 'no language') {
    text += 'literal without language tag';
  } else if (language == 'resource') {
    text += 'resource value (URI)';
  } else if (language == 'no field instance') {
    text += language;
  } else {
    text += '<strong>language code</strong>: ' + d.name;
    isARealLanguageCode = true;
  }

  if (d.size !== undefined) {
    var count = formatNumber(d.size);
    text += "<br>\n" + '<strong>number of field instances</strong>: ' + count;
    if (isARealLanguageCode) {
      text += "<br>\n" + '<strong>number of records</strong>: '
        + '<span id="count-' + fieldName.toLowerCase() + '"></span>';
      languageFieldRecordCount(collectionId, fieldName.toLowerCase(), language)
    }
  }

  if (fieldName == 'aggregated' && isARealLanguageCode) {
    text += "<br>\n<strong>available in fields</strong>: ";
    var items = new Array();
    for (i in fieldsByLanguage[d.name]) {
      field = fieldsByLanguage[d.name][i];
      var params = [collectionId, field.toLowerCase(), language];
      item = formatField(field)
           + ' <a href="#" class="language-field-examples"'
           + " onclick=\"languageFieldExamples(event,'" + params.join("','") + "')\""
           + '>examples <i class="fa fa-angle-down" aria-hidden="true"></i></a>'
           + '<span id="ex-' + field.toLowerCase() + '"></span>'
      ;
      items.push('<li>' + item + '</li>');
    }
    text += '<ul>' + items.join('') + '</ul>';
  }
  return text;
}

function formatField(field) {
  field = field
            .replace(
              /(proxy|aggregation|agent|concept|place|timespan)_/,
              "$1/"
            )
            .replace(/_/, ':');
  return field;
}

function buildQuery(field, language, collectionId) {
  var langField = (field == 'aggregated') ? 'languages_ss' : 'lang_' + field + '_ss';
  var q = langField + ':"' + language + '"';

  var typeField = collectionId.substring(0, 1) == 'c'
    ? 'collection_i'
    : 'provider_i';
  collectionId = collectionId.substring(1);
  var fq = typeField + ':' + collectionId;

  var query = {'q': q, 'fq': fq};
  return query;
}

function languageFieldRecordCount(collectionId, field, language) {
  // event.preventDefault();
  var query = buildQuery(field, language, collectionId);
  query.rows = 0;
  $.get("newviz/solr-ajax.php", query)
   .done(function(data) {
     $('#count-' + field).html(formatNumber(data.total));
   });
}

function formatNumber(inputNumber) {
  return inputNumber.toString()
              .replace(
                /./g,
                function(c, i, a) {
                  return i && c !== "." && ((a.length - i) % 3 === 0) ? ',' + c : c;
                }
              );
}

function languageFieldExamples(event, collectionId, field, language) {
  event.preventDefault();
  var query = buildQuery(field, language, collectionId);
  query.rows = 10;

  $.get("newviz/solr-ajax.php", query)
   .done(function(data) {
     var portalUrl = 'https://www.europeana.eu/portal/en/record';
     var items = new Array();
     for (i in data.ids) {
       id = data.ids[i];
       dataLink = '<a target="_blank" href="' + portalUrl + id + '.json" class="external">data</a>';
       portalLink = '<a target="_blank" href="' + portalUrl + id + '.html" class="external">portal</a>';
       item = 'visit record (' + dataLink + ', ' + portalLink + ')';
       items.push('<li>' + item + '</li>');
     }
     var content = '<ul>' + items.join('') + '</ul>';
     $('#ex-' + field).html(content);
   });
}