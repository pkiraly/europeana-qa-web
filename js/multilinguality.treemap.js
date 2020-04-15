$('#language-distribution-selector').on('change', function () {
  displayLanguageTreemap();
  $('#tooltip').html("");
});

$('#excludeZeros').on('change', function () {
  displayLanguageTreemap();
});

$('#showNoOccurences').on('change', function () {
  displayLanguageTreemap();
});

var margin = {top: 10, right: 10, bottom: 10, left: 10},
  width  = 850 - margin.left - margin.right,
  height = 500 - margin.top  - margin.bottom;

// var color = d3.scale.category20c();
var color = scaleOrdinal(d3.schemeCategory10);

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
  var showNoOccurences = 0; //$('#showNoOccurences').is(':checked') ? 0 : 1;

  var treeMapUrl = 'plainjson2tree.php?field=' + field
    + '&excludeZeros=' + excludeZeros //  . (int)$excludeZeros
    + '&showNoOccurences=' + showNoOccurences // . (int)$showNoOccurences
    + '&collectionId=' + collectionId
    + '&intersection=' + intersection
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

    if (!hasChildren(root)) {
      var warning = '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="color: maroon"></i>'
                  + ' In this dataset ' + formatField(root.name) + ' is not used';
      $('#tooltip').html(warning);
    }

    d3.selectAll('#heatmap .node').remove();

    node = heatmap
      .datum(root)
      .selectAll(".node")
      .data(treemap.nodes)
      .enter().append("div")
      .attr("class", "node")
      .call(position)
      // .style("color", '#fff')
      // .style("font-weight", 'bold')
      .style('cursor', 'pointer')
      .style("background", function(d) {
        // return d.children ? color(d.name) : null;
        return d.children ? '#3182bd' : null; //
      })
      .text(function(d) {
        if (d.children) {
          return null;
        } else {
          var text = '';
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
    text += '<strong>language code</strong>: ' + language;
    isARealLanguageCode = true;
  }

  if (d.size !== undefined) {
    var count = formatNumber(d.size);
    text += "<br>\n" + '<strong>number of field instances</strong>: ' + count;
    if (isARealLanguageCode || (language == 'no language')) { // && fieldName != 'aggregated'
      text += "<br>\n" + '<strong>number of records</strong>: '
           +  '<span id="count-' + fieldName.toLowerCase() + '"></span>';
      languageFieldRecordCount(collectionId, fieldName.toLowerCase(), language)
    }
  }

  if (isARealLanguageCode || language == 'no language') {
    if (fieldName == 'aggregated' && language != 'no language') {
      text += "<br>\n<strong>present in fields</strong>: ";
      var items = new Array();
      for (var i in fieldsByLanguage[d.name]) {
        var field = fieldsByLanguage[d.name][i];
        var params = [collectionId, field, language];
        var item = formatField(field)
             + ' <a href="#" class="language-field-examples"'
             + " onclick=\"languageFieldExamples(event,'" + params.join("','") + "')\""
             + '>examples <i class="fa fa-angle-down" aria-hidden="true"></i></a>'
             + '<span id="ex-' + field.toLowerCase() + '"></span>'
        ;
        items.push('<li>' + item + '</li>');
      }
      text += '<ul>' + items.join('') + '</ul>';
    } else {
      var params = [collectionId, fieldName, language];
      text += '<br><a href="#" class="language-field-examples"'
           +  " onclick=\"languageFieldExamples(event,'" + params.join("','") + "')\""
           +  '>examples <i class="fa fa-angle-down" aria-hidden="true"></i></a>'
           +  '<span id="ex-' + fieldName.toLowerCase() + '"></span>'
    }
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
  if (language == 'no language') {
    if (field == 'aggregated') {
      var fields = [];
      for (var i in languageFields) {
        fields.push('lang_' + languageFields[i].toLowerCase() + '__0_i:[1 TO *]');
      }
      var q = fields.join(' OR ');
    } else {
      var q = 'lang_' + field + '__0_i:[1 TO *]';
    }
  } else {
    var langField = (field == 'aggregated') ? 'languages_ss' : 'lang_' + field + '_ss';
    var q = langField + ':"' + language + '"';
  }

  var typeField = '';
  var queryParts = [];
  if (version >= 'v2018-08') {
    queryParts = parseTypesAndIds();
  } else {
    var typeAbbreviation = collectionId.substring(0, 1);
    typeField = (typeAbbreviation == 'c')
      ? 'collection_i'
      : 'provider_i';
  }
  collectionId = collectionId.substring(1);
  if (queryParts.length > 0) {
    var fq = queryParts.join('%20AND%20');
  } else {
    var fq = typeField + ':' + collectionId;
  }
  var query = {'q': q, 'fq': fq, 'version': version};
  return query;
}

function parseTypesAndIds() {
  var queryParts = [];
  if (type1 == 'a') {
    queryParts.push('*:*');
  } else {
    queryParts.push(resolveTypeAbbreviation(type1) + ':' + id1);
    if (type2 != '')
      queryParts.push(resolveTypeAbbreviation(type2) + ':' + id2);
    if (type3 != '')
      queryParts.push(resolveTypeAbbreviation(type3) + ':' + id3);
  }
  return queryParts;
}

function parseIntersection(inputString) {
  var queryParts = [];
  var parts = inputString.split('-');
  if (parts.length == 2) {
    var part = resolveTypeAbbreviation(parts[0]) + ':' + parts[1];
    queryParts.push(part);
  } else {
    var first = parts[0];
    for (var i = 0; i<first.length - 1; i++) {
      var typeAbbreviation = first.substr(i, 1);
      var typeField = resolveTypeAbbreviation(typeAbbreviation);
      var part = typeField + ':' + parts[i + 1];
    }
  }
  return queryParts;
}

function resolveTypeAbbreviation(typeAbbreviation) {
  var typeField = '';
  switch (typeAbbreviation) {
    case 'c': typeField = 'dataset_i'; break;
    case 'd': typeField = 'dataProvider_i'; break;
    case 'p': typeField = 'provider_i'; break;
    case 'cn': typeField = 'country_i'; break;
    case 'l': typeField = 'language_i'; break;
  }
  return typeField;
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
     for (var i in data.ids) {
       var id = data.ids[i];
       var links = new Array();
       links.push(getRecordLink(id, 'data'));
       links.push('<a target="_blank" href="' + portalUrl + id + '.html" class="external">portal</a>');
       links.push('<a href="record.php?id=' + id + '&version=' + version + '"' + ' title="record id: ' + id + '">details</a>');
       var item = 'visit record (' + links.join(', ') + ')';
       items.push('<li>' + item + '</li>');
     }
     var content = '<ul>' + items.join('') + '</ul>';
     $('#ex-' + field.toLowerCase()).html(content);
   });
}

function hasChildren(root) {
  var hasChildren = false;
  for (var i in root.children) {
    var child = root.children[i];
    if (typeof child.size != "undefined"
      || (typeof child.children != "undefined" && child.children.length > 0)) {
      hasChildren = true;
      break;
    }
  }
  return hasChildren;
}