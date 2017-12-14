<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $collectionId ?> | <?= $title ?></title>
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.1.0/styles/default.min.css"/>
    <!--
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
    -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
          integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway" type="text/css"/>
    <script src="https://use.fontawesome.com/feff23b961.js"></script>
    <link rel="stylesheet" href="europeana-qa.css?a=<?php rand(); ?>" type="text/css"/>
    <link rel="stylesheet" href="chart.css?a=<?php rand(); ?>" type="text/css"/>
    <!-- choose a theme file -->
    <link rel="stylesheet" href="jquery/theme.default.min.css">
    <!-- load jQuery and tablesorter scripts -->
    <script type="text/javascript" src="jquery/jquery-1.9.1.min.js"></script>
<style type="text/css">
    .chart {
        width: 300px;
        max-width: 300px;
        background-color: #ccc;
        border-bottom: 1px solid maroon;
    }
    .chart div {
        background-color: #428bca;
        color: white;
        text-align: right;
        padding-right: 2px;
        font-size: 80%;
    }
    .row div h3 {
        margin-top: 0;
        padding-top: 0;
    }
    th.first, td.first {
        border-left: 1px solid #cccccc;
    }
    /*
    th.statistic {
        -webkit-transform:rotate(-90deg);
        transform-origin: left bottom 0;
    }
    */
    .table-header-rotated {
        border-collapse: collapse;
    }
    .table-header-rotated td {
        width: 30px;
    }
    .table-header-rotated th {
        padding: 5px 10px;

    }
    .table-header-rotated td {
        text-align: center;
        padding: 10px 5px;
        border: 1px solid #ccc;
    }
    .table-header-rotated th.rotate {
        height: 50px;
        white-space: nowrap;
    }
    .table-header-rotated th.rotate > div {
        -webkit-transform: translate(25px, 51px) rotate(315deg);
        transform: translate(25px, 51px) rotate(315deg);
        width: 30px;
    }
    .table-header-rotated th.rotate > div > span {
        /* border-bottom: 1px solid #ccc; */
        padding: 5px 10px;
        left: 10px;
        top: -55px;
        position: relative;
    }
    .table-header-rotated th.row-header {
        padding: 0 10px;
        border-bottom: 1px solid #ccc;
    }
    .field-details {
        padding-bottom: 15px;
    }
    table#generic td.level {
        text-align: left;
    }
    table#generic td.metric {
        width: 250px;
        text-align: left;
        min-width: 270px;
    }

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
      <h1>Field frequency</h1>
      <h2><?= $collectionId ?></h2>
      <h3><a href="/europeana-qa/">Metadata Quality Assurance Framework</a></h3>
    </div>
    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a href="#cardinality-score">Cardinality</a></li>
        <li><a href="#multilingual-score">Multilinguality</a></li>
    </ul>
    <div class="tab-content">
        <div id="cardinality-score" class="tab-pane active">
            <div class="row">
                <h2>Field Cardinality</h2>
                <div class="col-sm-3 col-md-3 col-lg-3">
                    <p>Dataset: <?= $entityCounts->proxy_rdf_about ?> records</p>
                    <ul id="entities" class="nav">
                        <li class="nav-item active">
                          <a class="nav-link active" href="#" datatype="ProvidedCHO">ProvidedCHO (<?= $entityCounts->proxy_rdf_about ?>)</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#" datatype="Agent">Agent (<?= $entityCounts->agent_rdf_about ?>)</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#" datatype="Timespan">Timespan (<?= $entityCounts->timespan_rdf_about ?>)</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#" datatype="Concept">Concept (<?= $entityCounts->concept_rdf_about ?>)</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#" datatype="Place">Place (<?= $entityCounts->place_rdf_about ?>)</a>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-9 col-md-9 col-lg-9" id="cardinality-content">
                    
                </div>
            </div>
        </div>
        <div id="multilingual-score" class="tab-pane fade">
            <div class="row">
                <h2>Multilingual score</h2>
                <div class="col-sm-12 col-md-12 col-lg-12" id="multilinguality-content"></div>
            </div>
        </div>
    </div>

    <footer>
        <p><a href="http://pkiraly.github.io/">What is this?</a> &ndash; about the Metadata Quality Assurance Framework
            project.</p>
    </footer>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script src="//d3js.org/d3.v3.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        var loaded = {'#cardinality-score': true, '#multilingual-score': false};
        // $("#generic").tablesorter();
        // $("#specific-taggedliterals").tablesorter();
        // $("#specific-languages").tablesorter();
        // $("#specific-literalsperlanguage").tablesorter();
        var id = document.location.hash;
        if (id == '#multilingual-score'
            || id == '#all-fields'
            || id == '#individual-fields') {
          loadMultilinguality();
          $('.nav-tabs a[href="#multilingual-score"]').tab('show');
        }
        else {
          loadEntityCardinality('ProvidedCHO');
          $('.nav-tabs a[href="' + id + '"]').tab('show');
        }

        $(".nav-tabs a").click(function() {
          $(this).tab('show');
          var id = this.href.substr(this.href.indexOf('#'));
          console.log('id: ' + id);
          if (loaded[id] === false)
            if (id == '#multilingual-score')
              loadMultilinguality();
            else
              loadEntityCardinality('ProvidedCHO');
        });
    });
    $(function () {
        // $('#entities a.nav-link').tab('show');
        // $('#tablanguages').tab
        $('#entities a.nav-link').click(function (event) {
            event.preventDefault();
            var entity = $(this).attr('datatype');
            loadEntityCardinality(entity);
        });
    });
    function loadMultilinguality() { //entity
      console.log('loadMultilinguality');
      entity = 'ProvidedCHO';
      var query = {'id': '<?= $id ?>', 'type': '<?= $type ?>', 'entity': entity};
      $.get("newviz/multilinguality-ajax.php", query)
        .done(function(data) {
          $('#multilinguality-content').html(data.html);
          $(".nav-tabs a").click(function() {
            $(this).tab('show');
          });
        });
    }

    function loadEntityCardinality(entity) {
      var query = {'id': '<?= $id ?>', 'type': '<?= $type ?>', 'entity': entity};
      $.get("newviz/cardinality-ajax.php", query)
        .done(function(data) {
          var n = <?= $n ?>;
          var count = data.statistics.entityCount.toLocaleString('en-US');
          var text = '<h3 class="entity-name">' + data.entity + '</h3>'
                   + '<p>number of entities: ' + count + '</p>';

          var key;
          for (field in data['fields']) {
            var key = field.toLowerCase();
            if (typeof data.statistics.frequencyTable[key] === 'undefined')
              continue;

            var mandatory = !(typeof data.mandatory[key] === 'undefined');
            var mandatoryIcon = '';
            if (mandatory) {
              if (data.mandatory[key] == 'black')
                mandatoryIcon = 'check';
              else if (data.mandatory[key] == 'blue')
                mandatoryIcon = 'arrow-right';
              else if (data.mandatory[key] == 'red')
                mandatoryIcon = 'circle-o';
              else if (data.mandatory[key] == 'green')
                mandatoryIcon = 'gear';
              else if (data.mandatory[key] == 'plus')
                mandatoryIcon = 'plus';
            }


            text += '<div class="row">';
            text += '<div class="col-lg-5 newviz"><h3'
                 + (mandatory ? ' class="mandatory-' + data.mandatory[key] + '"' : '')
                 + '>' 
                 + (mandatory ? '<i class="fa fa-' + mandatoryIcon + ' mandatory-icon" aria-hidden="true"></i>' : '')
                 + data.fields[field]
            text += ' <a class="qa-show-details ' + key + '" href="#">'
                 +  '<i class="fa fa-plus-square" aria-hidden="true"></i></a>'
                 + '</h3></div>';
            if (data.statistics.frequencyTable[key]) {
              var freq = data.statistics.frequencyTable[key];
              var zeros = (!isNaN(freq.values["0"])) ? zeros = freq.values["0"] : n;
              var nonZeros = (!isNaN(freq.values["1"])) ? freq.values["1"] : n - zeros;
              var percent = nonZeros / n;
              var width = parseInt(300 * percent);
              text += '<div class="col-lg-6">';
              text += '<div class="chart"><div style="width: ' + width + 'px;">'
                   + nonZeros.toLocaleString('en-US')
                   + '</div></div>';
              text += '</div>';
            }
            // text += '<div class="col-lg-3">';
            // text += '</div>';
            text += '</div>';

            text += '<div class="row">';
            text += '<div class="col-sm-1 col-md-1 col-lg-1"></div>';
            text += '<div class="col-sm-11 col-md-11 col-lg-11">';

            text += '<div class="qa-details field-details" id="details-' + key + '">';

            // cardinality bar
            if (data.statistics.cardinality[key]) {
              /*
              var cardinality = data.statistics.cardinality[key];
              var cardinalityPercent = cardinality.sum / data.statistics.cardinalityMax;
              var cardinalityWidth = parseInt(300 * cardinalityPercent);
              text += '<h3>Cardinality</h3>';
              text += '<div class="chart"><div style="width: ' + cardinalityWidth + 'px;">'
                   + cardinality.sum.toLocaleString('en-US')
                   + '</div></div>';
              text += '<p>This bar is proportional to the maximum cardinality value of this entity, which is of '
                   + data.fields[data.statistics.cardinalityMaxField] + '</p>';
              */
            }
            // frequency table
            if (data.statistics.frequencyTable[key]) {
              text += data.statistics.frequencyTable[key].html;
            }
            // cardinality statistics
            if (data.statistics.cardinality[key]) {
              text += data.statistics.cardinality[key].html;
            }
            // cardinality histogram
            if (data.statistics.histograms[key]) {
              text += data.statistics.histograms[key].html;
            }
            if (data.statistics.images[key]['frequency'].exists) {
              text += data.statistics.images[key]['frequency'].html;
            }
            // cardinality images
            if (data.statistics.images[key]['cardinality'].exists) {
              // text += data.statistics.images[key]['cardinality'].html;
            }
            if (data.statistics.minMaxRecords[key]) {
              text += '<ul>'
                   + '<li><a href="record.php?id=' + data.statistics.minMaxRecords[key].recMax + '">Best Record</a></li>'
                   + '<li><a href="record.php?id=' + data.statistics.minMaxRecords[key].recMin + '">Worst Record</a></li>'
                   + '</ul>'
            }
            text += 'Cardinality compared to <select name="comparision-selector" id="' + field.toLowerCase() + '-comparision-selector">';
            text += '<option>--select a field--</option>';
            for (otherField in data.fields) {
                if (otherField != field)
                    text += '<option value="' + otherField.toLowerCase() + '">' + data.fields[otherField] + '</option>';
            }
            text += '</select>';
            text += '<div id="' + field.toLowerCase() + '-comparision-container"></div>';
            // text += '<li>' + data['fields'][key] + ' (' + key + ')</li>';
            text += '</div>'; // details
            text += '</div>'; // cell
            text += '</div>'; // row
          }
          // text += '</ul>';

          text += '<ul id="mandatory-note">'
                + '<li><i class="fa fa-check mandatory-icon" aria-hidden="true"></i> = Mandatory property</li>'
                + '<li><i class="fa fa-arrow-right mandatory-icon" aria-hidden="true"> Blue</i> = at least one of the blue properties should be present (and can be used alongside each other)</li>'
                + '<li><i class="fa fa-circle-o mandatory-icon" aria-hidden="true"> Red</i> = at least one of the red properties should be present (and can be used alongside each other)</li>'
                + '<li><i class="fa fa-gear mandatory-icon" aria-hidden="true"> Green</i> = at least one of the green properties should be present (and can be used alongside each other)</li>'
                + '<li><i class="fa fa-plus mandatory-icon" aria-hidden="true"></i> = recommended property</li>'
                + '</ul>';

          $('#cardinality-content').html(text);
          $('a.qa-show-details').click(function (event) {
            event.preventDefault();
            id = $(this).attr('class').replace('qa-show-details ', '#details-');
            $(id).toggle();
            faClass = $("i", this).attr('class') == 'fa fa-plus-square' ? 'fa fa-minus-square' : 'fa fa-plus-square';
            $("i", this).attr('class', faClass);
            // $(this).text($(this).text() == 'Show details' ? 'Hide details' : 'Show details');
          });
          $('select[name=comparision-selector]').on('change', function(){
              var thisField = this.id.replace('-comparision-selector', '');
              var otherField = this.value;
              var el = $('#' + otherField + '-histogram');
              var html = "";
              if (typeof el.html() != "undefined")
                  html = el.clone().wrap('<div>').parent().html();
              $('#' + thisField + '-comparision-container').html(html);
          });
        });
    }
</script>
<script type="text/javascript">
    $("a.qa-show-details").click(function (event) {
        event.preventDefault();
        id = $(this).attr('class').replace('qa-show-details ', '#details-');
        $(id).toggle();
    });
</script>

</body>
</html>
