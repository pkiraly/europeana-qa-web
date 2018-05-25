<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
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
    <link rel="stylesheet" href="style/newviz.css?a=<?php rand(); ?>" type="text/css"/>
    <!-- choose a theme file -->
    <link rel="stylesheet" href="jquery/theme.default.min.css">
    <!-- load jQuery and tablesorter scripts -->
    <script type="text/javascript" src="jquery/jquery-1.9.1.min.js"></script>
</head>
<body>

<div class="container">
  <div class="text-right">
    <a href=".">Go to the stable version</a>
  </div>
  <div class="page-header">
    <h3>Metadata Quality Assurance Framework for Europeana</h3>
  </div>

  <form id="collection-selector">
    <div class="row">
      <div class="col-lg-4">
        <label><input type="radio" name="type" value="c"<?php if ($type == 'c') { ?> checked="checked"<?php } ?>>select a dataset</label>
        <label><input type="radio" name="type" value="d"<?php if ($type == 'd') { ?> checked="checked"<?php } ?>>select a data provider</label>
      </div>
      <div class="col-lg-8">
        <label for="fragment">filter the list:</label>
        <input type="text" name="fragment" value="<?= $fragment ?>" onkeyup="filterIds();"><br>
        <select name="id" id="cid" <?php if ($type != 'c') { ?> style="display:none"<?php } ?>>
          <?php foreach ($datasets as $cid => $name) { ?>
            <option value="<?= $cid ?>"<?php if ($type == 'c' && $id == $cid) { ?> selected="selected" title="<?= $name ?>"<?php } ?>><?= $name ?></option>
          <?php } ?>
        </select>
        <select name="id" id="did" <?php if ($type != 'c') { ?> style="display:none"<?php } ?>>
          <?php foreach ($dataproviders as $did => $name) { ?>
            <option value="<?= $did ?>"<?php if ($type == 'd' && $id == $did) { ?> selected="selected" title="<?= $name ?>"<?php } ?>><?= $name ?></option>
          <?php } ?>
        </select>
        <input type="submit" class="btn btn-dark btn-sm" aria-hidden="true" value="Display"><br/>
      </div>
    </div>
  </form>

  <?php if ($type == 'd') { ?>
    <h4><?= $collectionId ?></h4>
  <?php } ?>
  <p>
    <a href="<?= getPortalUrl($type, $collectionId) ?>" target="_blank">see it on Europeana portal</a>.
    versions: <?php foreach ($configuration['version'] as $configured_version) { ?>
      <?php if ($version == $configured_version) { ?>
        <?= $configured_version ?>
      <?php } else { ?>
        <a href="?type=<?= $type ?>&id=<?= $id ?>&version=<?= $configured_version ?>"><?= $configured_version ?></a>
      <?php } ?>
    <?php } ?>
  </p>

  <ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#cardinality-score">Frequency</a></li>
    <li><a href="#multilingual-score">Multilinguality</a></li>
<?php if ($development) { ?>
    <li><a href="#record-patterns">Record patterns</a></li>
    <li><a href="#uniqueness">Uniqueness</a></li>
<?php } ?>
  </ul>
  <div class="tab-content">
    <div id="cardinality-score" class="tab-pane active">
      <div class="row">
        <div class="col-sm-3 col-md-3 col-lg-3">
          <h2>Field Frequency</h2>
          <p>Dataset: <?= $entityCounts->proxy_rdf_about ?> records <?= $n ?></p>
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
        <div class="col-sm-9 col-md-9 col-lg-9">
          <ul id="mandatory-note">
            <li><i class="fa fa-check mandatory-icon" aria-hidden="true"></i> = Mandatory property</li>
            <li><i class="fa fa-arrow-right mandatory-icon" aria-hidden="true"> Blue</i>
              = at least one of the blue properties should be present (and can be used alongside each other)</li>
            <li><i class="fa fa-circle-o mandatory-icon" aria-hidden="true"> Red</i>
              = at least one of the red properties should be present (and can be used alongside each other)</li>
            <li><i class="fa fa-gear mandatory-icon" aria-hidden="true"> Green</i>
              = at least one of the green properties should be present (and can be used alongside each other)</li>
            <li><i class="fa fa-plus mandatory-icon" aria-hidden="true"></i>
              = recommended property</li>
          </ul>
          <p>
            <i class="fa fa-info-circle"></i>
            The progress bars show you the number of records containing a given EDM field for a given dataset.
            Each field can be expanded to reveal detailed statistics about the number of its instances as well
            as the distribution of its occurrences in a given dataset. You can browse and compare the statistics
            of each EDM field with another belonging to the same EDM class.
          </p>
          <div id="cardinality-content"></div>
        </div>
      </div>
    </div>
    <div id="multilingual-score" class="tab-pane fade">
      <div class="row">
        <h2>Multilinguality metrics</h2>
        <p>Dataset: <?= $entityCounts->proxy_rdf_about ?> records</p>
        <p>
          <i class="fa fa-info-circle"></i>
          The multilingual metrics quantify multilingual information in metadata. For now, we calculate the
          number and diversity of language tags (e.g. @en, @fr) that are used to indicate the language of
          metadata values. Europeana encourages the use of language tags by providers to

          <ul>
            <li>improve search and browsing functionalities across languages</li>
            <li>to identify the language of metadata and show the preferred language version of
              metadata to users</li>
          </ul>
        </p>
        <div class="col-sm-12 col-md-12 col-lg-12" id="multilinguality-content"></div>
      </div>
    </div>
<?php if ($development) { ?>
    <div id="record-patterns" class="tab-pane fade">
      <div class="row">
        <h2>Record patterns</h2>
        <div class="col-sm-12 col-md-12 col-lg-12" id="record-patterns-content"></div>
      </div>
    </div>
    <div id="uniqueness" class="tab-pane fade">
      <div class="row">
        <h2>Uniqueness</h2>
        <p>
          <i class="fa fa-info-circle"></i>
          It measures the uniqueness of field values in the most important descriptive fields
          (currently: dc:title, dcterms:alternative and dc:description). If a field value is unique
          accross all Europeana records, then its specificity or information content is high. On the other hand
          if the value is repeated in several hundreds or thousands of records, then we can not easily
          make distinction between these records, so the information value is low. For example: if
          several thousand photograph has the title "Photograph" (solely) we can not find those ones, which
          depict a specific object. We created 6 categories: unique values, and five ranges denoted
          by ranges. The actual ranges are different for each fields, because the different fields
          have different distribution (title occurs almost all records). The ranges became smaller and
          smaller towards the top categories, which means, that the difference between a unique value
          and a duplicated value are bigger than between duplicated and triplicated etc.
        </p>
        <div class="col-sm-12 col-md-12 col-lg-12" id="uniqueness-content"></div>
      </div>
    </div>
<?php } ?>
  </div>
  <footer>
    <p>
      <a href="http://pkiraly.github.io/">What is this?</a> &ndash; about the Metadata Quality Assurance Framework project.
     </p>
  </footer>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script src="//d3js.org/d3.v3.min.js"></script>

<script type="text/javascript">
var loadedEntity = null;
$(document).ready(function () {
  var loaded = {
    '#cardinality-score': false,
    '#multilingual-score': false,
    '#record-patterns': false,
    '#uniqueness': false,
  };
  // $("#generic").tablesorter();
  // $("#specific-taggedliterals").tablesorter();
  // $("#specific-languages").tablesorter();
  // $("#specific-literalsperlanguage").tablesorter();
  var id = document.location.hash;
  if (isMultilingualityPanel(id)) {
    loadMultilinguality();
    $('.nav-tabs a[href="#multilingual-score"]').tab('show');
  } else if (id == '#record-patterns') {
    loadRecordPatterns();
    $('.nav-tabs a[href="#record-patterns"]').tab('show');
  } else if (id == '#uniqueness') {
    loadUniqueness();
    $('.nav-tabs a[href="#uniqueness"]').tab('show');
  } else {
    loadEntityCardinality('ProvidedCHO');
    loadedEntity = 'ProvidedCHO';
    $('.nav-tabs a[href="' + id + '"]').tab('show');
  }

  $(".nav-tabs a").click(function() {
    $(this).tab('show');
    var id = this.href.substr(this.href.indexOf('#'));
    if (loaded[id] === false) {
      if (isMultilingualityPanel(id))
        loadMultilinguality();
      else if (id == '#record-patterns')
        loadRecordPatterns();
      else if (id == '#uniqueness')
        loadUniqueness();
      else
        loadEntityCardinality('ProvidedCHO');
    }
  });

  showType('<?= $type ?>');
  $("input[name='type']").on('change', function () {
    showType($(this).val());
  })
});

$(function () {
  // $('#entities a.nav-link').tab('show');
  // $('#tablanguages').tab
  $('#entities a.nav-link').click(function (event) {
    event.preventDefault();
    var entity = $(this).attr('datatype');
    toggleActivation(entity);
    loadEntityCardinality(entity);
  });
});

function filterIds() {
  var fragment = $('input[name=fragment]').val();
  var type = $('#collection-selector input[name=type]:checked').val();
  var query = {'fragment': fragment, 'type': type};
  $.get("newviz/dataset-filter-ajax.php", query)
  .done(function(data) {
    var selectorId = (data.type == 'c') ? 'cid' : 'did';

    $('#' + selectorId + ' option').remove();
    $.each(data.content, function() {
      $('#' + selectorId).append('<option value="' + this.value + '">' + this.name + '</option>');
    })
  });
}

function toggleActivation(entity) {
  if (loadedEntity != null) {
    $("a[datatype=" + loadedEntity + "]").parent().removeClass('active');
  }
  $("a[datatype=" + entity + "]").parent().addClass('active');
  loadedEntity = entity;
}

function isMultilingualityPanel(id) {
  return (id == '#multilingual-score' || id == '#all-fields' || id == '#individual-fields');
}

function showType(type) {
  var toShowId = 'cid', toHideId = 'did';
  if (type == 'd') {
    toShowId = 'did', toHideId = 'cid';
  }
  var toShow = $('#' + toShowId);
  toShow.show();
  toShow.prop('disabled', false);

  var toHide = $('#' + toHideId);
  toHide.hide();
  toHide.prop('disabled', 'disabled');
}

function loadMultilinguality() {
  var entity = 'ProvidedCHO';
  var query = {'id': '<?= $id ?>', 'type': '<?= $type ?>', 'entity': entity,
    'version': '<?= $version ?>', 'development': <?= (int)$development ?>
  };
  $.get("newviz/multilinguality-ajax.php", query)
   .done(function(data) {
      $('#multilinguality-content').html(data.html);
      $(".nav-tabs a").click(function() {
        $(this).tab('show');
      });
   });
}

function loadRecordPatterns() {
  var query = {'id': '<?= $id ?>', 'type': '<?= $type ?>', 'count': <?= $n ?>,
    'version': '<?= $version ?>', 'development': <?= (int)$development ?>
  };
  $.get("newviz/record-patterns-ajax.php", query)
   .done(function(data) {
     $('#record-patterns-content').html(data.html);
     $(".nav-tabs a").click(function() {
       $(this).tab('show');
     });
   });
}

function loadUniqueness() {
  var query = {'id': '<?= $id ?>', 'type': '<?= $type ?>', 'count': <?= $n ?>,
    'version': '<?= $version ?>', 'development': <?= (int)$development ?>
  };
  $.get("newviz/uniqueness-ajax.php", query)
  .done(function(data) {
    $('#uniqueness-content').html(data.html);
    $(".nav-tabs a").click(function() {
      $(this).tab('show');
    });
  });
}

function loadEntityCardinality(entity) {
  var development = <?= (int)$development ?>;
  var query = {'id': '<?= $id ?>', 'type': '<?= $type ?>', 'entity': entity,
    'version': '<?= $version ?>', 'development': <?= (int)$development ?>
  };
  $.get("newviz/cardinality-ajax.php", query)
    .done(function(data) {
      $('#cardinality-content').html(data);

      $('a.qa-show-details').click(function (event) {
        event.preventDefault();
        id = $(this).attr('class').replace('qa-show-details ', '#details-');
        $(id).toggle();
        faClass = $("i", this).attr('class') == 'fa fa-angle-down'
          ? 'fa fa-angle-up' : 'fa fa-angle-down';
        $("i", this).attr('class', faClass);
        // $(this).text($(this).text() == 'Show details' ? 'Hide details' : 'Show details');
      });
      $('select[name=comparision-selector]').on('change', function(){
        var thisField = this.id.replace('-comparision-selector', '');
        var otherField = this.value;
        var el = $('#' + otherField + '-histogram');
        var html = "";
        if (typeof el.html() != "undefined") {
          html = el.clone().wrap('<div>').parent().html();
          $('#' + thisField + '-comparision-container').html(html);
          $("[data-toggle='histogram-popover']").on('show.bs.popover', function(){
            processHistogramPopoverContent($(this));
          });
          $('[data-toggle="histogram-popover"]').popover({html: true});
        }
      });

      $("[data-toggle='histogram-popover']").each(function() {
        $(this).css('cursor', 'pointer');
        $(this).css('color', '#23527c');
      });
      $("[data-toggle='histogram-popover']").on('show.bs.popover', function(){
        processHistogramPopoverContent($(this));
      });
      $('[data-toggle="histogram-popover"]').popover({html: true});
    });
}

function processHistogramPopoverContent(element) {
  var content = element.attr('data-content');
  if (content.substring(0, 1) != '@') {
    content = content.replace(/^.*data-content="([^"]+)".*$/, "$1")
  }
  if (content.substring(0, 1) == '@') {
    var parts = content.substring(1).split('|');

    var field = parts[0];
    var q = field + ':' + parts[1];
    var range = parts[2];
    var fq = parts[3];
    var targetId = 'histogram-range-' + field + '-' + range;
    var html = '<span id="' + targetId + '" data-content="' + content + '"></span>';
    element.attr('data-content', html);

    var query = {'q': q, 'fq': fq, 'rows': 10};
    $.get('newviz/solr-ajax.php', query)
    .done(function(data){
      var portalUrl = 'https://www.europeana.eu/portal/en/record';
      var items = new Array();
      for (i in data.ids) {
        var recordId = data.ids[i];
        var item = '<a target="_blank" href="' + portalUrl + recordId + '.json"'
          + ' title="record id: ' + recordId + '">visit record</a>';
        items.push('<li>' + item + '</li>');
      }
      var content = '<ul>' + items.join('') + '</ul>';
      $('#' + targetId).html(content);
    });
  }
}

$("a.qa-show-details").click(function (event) {
  event.preventDefault();
  id = $(this).attr('class').replace('qa-show-details ', '#details-');
  $(id).toggle();
});
</script>
</body>
</html>
