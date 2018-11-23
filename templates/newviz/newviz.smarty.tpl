{include file="../common/html-header.smarty.tpl"}
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
        <label><input type="radio" name="type" value="c"{if ($type == 'c')} checked="checked"{/if}>select a dataset</label>
        <label><input type="radio" name="type" value="d"{if ($type == 'd')} checked="checked"{/if}>select a data provider</label>
      </div>
      <div class="col-lg-8">
        <label for="fragment">filter the list:</label>
        <input type="text" name="fragment" value="{$fragment}" onkeyup="filterIds();"><br>
        {strip}
        <select name="id" id="cid" {if ($type != 'c')} style="display:none"{/if}>
          {foreach $datasets as $cid => $name}
            <option value="{$cid}"{if ($type == 'c' && $id == $cid)} selected="selected" title="{$name}"{/if}>{$name}</option>
          {/foreach}
        </select>
        {/strip}
        {strip}
        <select name="id" id="did" {if ($type != 'c')} style="display:none"{/if}>
          {foreach $dataproviders as $did => $name}
            <option value="{$did}"{if ($type == 'd' && $id == $did)} selected="selected" title="{$name}"{/if}>{$name}</option>
          {/foreach}
        </select>
        {/strip}
        <input type="hidden" name="version" value="{$version}"/>
        <input type="hidden" name="development" value="{$development}"/>
        <input type="submit" class="btn btn-dark btn-sm" aria-hidden="true" value="Display"><br/>
      </div>
    </div>

    <div class="row" id="intersections">
      {assign var=total value=count($intersections)}
      {if $total > 6}
        {assign var=unit_size value=ceil($total / 3)}
      {else}
        {assign var=unit_size value="0"}
        <div class="col-lg-4">&nbsp;</div>
        <div class="col-lg-8">
      {/if}
      {strip}
        {foreach $intersections as $i => $item}
          {if $unit_size > 0 && $i % $unit_size == 0}
            {if $i != 0}</div>{/if}
            <div class="col-lg-4">
          {/if}
          <label>
            <input type="radio" name="intersection" value="{$item->file}"
                  {if $item->file == $intersection} checked="checked"{/if}/>
            {$item->name} ({$item->count|number_format:0:'.':' '})
          </label><br/>
        {/foreach}
        </div>
      {/strip}
    </div>
  </form>


  {if ($type == 'd')}
    <h4>{$collectionId}</h4>
  {/if}

  <div class="row">
    <div class="col-lg-8">
      <a href="{$portalUrl}" target="_blank">see it on Europeana portal</a>.
      versions:
      {foreach $configuration['version'] as $configured_version}
        {if ($version == $configured_version)}
          {$configured_version}
        {else}
          <a href="?type={$type}&id={$id}&version={$configured_version}&development={$development}">{$configured_version}</a>
        {/if}
      {/foreach}
    </div>
    <div class="col-lg-4 text-right">
      {if $development}
        <a href="?type={$type}&id={$id}&version={$version}&development=0">back to normal version</a>
      {else}
        <a href="?type={$type}&id={$id}&version={$version}&development=1">development version</a>
      {/if}
    </div>
  </div>

  <ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#cardinality-score">Frequency</a></li>
    <li><a href="#multilingual-score">Multilinguality</a></li>
    {if ($development)}
      <li><a href="#record-patterns">Record patterns</a></li>
      <li><a href="#uniqueness">Uniqueness</a></li>
    {/if}
  </ul>

  <div class="tab-content">
    <div id="cardinality-score" class="tab-pane active">
      <div class="row">
        <div class="col-sm-3 col-md-3 col-lg-3">
          <h2>Field Frequency</h2>
          <p>Dataset: {$entityCounts->proxy_rdf_about} records</p>
          <ul id="entities" class="nav">
            <li class="nav-item">
              <a class="nav-link" href="#cardinality-score-providedcho" datatype="ProvidedCHO">ProvidedCHO ({$entityCounts->proxy_rdf_about})</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#cardinality-score-agent" datatype="Agent">Agent ({$entityCounts->agent_rdf_about})</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#cardinality-score-timespan" datatype="Timespan">Timespan ({$entityCounts->timespan_rdf_about})</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#cardinality-score-concept" datatype="Concept">Concept ({$entityCounts->concept_rdf_about})</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#cardinality-score-place" datatype="Place">Place ({$entityCounts->place_rdf_about})</a>
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
            Each field can be expanded to reveal detailed statistics about the number of its occurrences as well
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
        <p>Dataset: {$entityCounts->proxy_rdf_about} records</p>
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
{if ($development)}
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
{/if}
  </div>
  <footer>
    {include file="../common/footer.smarty.tpl"}
  </footer>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script src="//d3js.org/d3.v3.min.js"></script>

<script type="text/javascript">
var loadedEntity = null;
var type = '{$type}';
var id = '{$id}';
var version = '{$version}';
var development = {(int)$development};
var count = {$count};
var collectionId = '{str_replace("'", "\\'", $collectionId)}';
var intersection = {if is_null($intersection)}null{else}'{$intersection}'{/if};

{literal}
$(document).ready(function () {
  var loaded = {
    '#cardinality-score': false,
    '#cardinality-score-providedcho': false,
    '#cardinality-score-agent': false,
    '#cardinality-score-concept': false,
    '#cardinality-score-place': false,
    '#cardinality-score-timespan': false,
    '#multilingual-score': false,
    '#multilingual-score-general': false,
    '#multilingual-score-languages': false,
    '#record-patterns': false,
    '#uniqueness': false,
  };
  // $("#generic").tablesorter();
  // $("#specific-taggedliterals").tablesorter();
  // $("#specific-languages").tablesorter();
  // $("#specific-literalsperlanguage").tablesorter();
  var tabId = document.location.hash;
  if (isMultilingualityPanel(tabId)) {
    loadMultilinguality();
    $('.nav-tabs a[href="#multilingual-score"]').tab('show');
  } else if (tabId == '#record-patterns') {
    loadRecordPatterns();
    $('.nav-tabs a[href="#record-patterns"]').tab('show');
  } else if (tabId == '#uniqueness') {
    loadUniqueness();
    $('.nav-tabs a[href="#uniqueness"]').tab('show');
  } else if (tabId == '#cardinality-score-providedcho') {
    loadedEntity = 'ProvidedCHO';
    loadEntityCardinality(loadedEntity);
    $('.nav-tabs a[href="' + tabId + '"]').tab('show');
  } else if (tabId == '#cardinality-score-providedcho') {
    loadedEntity = 'ProvidedCHO';
    loadEntityCardinality(loadedEntity);
    $('.nav-tabs a[href="' + tabId + '"]').tab('show');
  } else if (tabId == '#cardinality-score-providedcho') {
    loadedEntity = 'ProvidedCHO';
    loadEntityCardinality(loadedEntity);
    $('.nav-tabs a[href="' + tabId + '"]').tab('show');
  } else if (tabId == '#cardinality-score-agent') {
    loadedEntity = 'Agent';
    loadEntityCardinality(loadedEntity);
    toggleActivation(loadedEntity);
    $('.nav-tabs a[href="' + tabId + '"]').tab('show');
  } else if (tabId == '#cardinality-score-concept') {
    loadedEntity = 'Concept';
    loadEntityCardinality(loadedEntity);
    toggleActivation(loadedEntity);
    $('.nav-tabs a[href="' + tabId + '"]').tab('show');
  } else if (tabId == '#cardinality-score-place') {
    loadedEntity = 'Place';
    loadEntityCardinality(loadedEntity);
    toggleActivation(loadedEntity);
    $('.nav-tabs a[href="' + tabId + '"]').tab('show');
  } else if (tabId == '#cardinality-score-timespan') {
    loadedEntity = 'Timespan';
    loadEntityCardinality(loadedEntity);
    toggleActivation(loadedEntity);
    $('.nav-tabs a[href="' + tabId + '"]').tab('show');
  } else {
    loadEntityCardinality('ProvidedCHO');
    loadedEntity = 'ProvidedCHO';
    toggleActivation(loadedEntity);
    $('.nav-tabs a[href="' + tabId + '"]').tab('show');
  }

  $(".nav-tabs a").click(function() {
    $(this).tab('show');
    var tabId = this.href.substr(this.href.indexOf('#'));
    if (loaded[tabId] === false) {
      if (isMultilingualityPanel(tabId))
        loadMultilinguality();
      else if (tabId == '#record-patterns')
        loadRecordPatterns();
      else if (tabId == '#uniqueness')
        loadUniqueness();
      else
        loadEntityCardinality('ProvidedCHO');
    }
  });

  showType(type);
  $("input[name='type']").on('change', function(){
    showType($(this).val());
  })

  $("form#collection-selector select[name='id']").on('change', function(){
    var selectedId = $(this).val();
    updateIntercestionSelector(selectedId);
  })
});


$(function () {
  // $('#entities a.nav-link').tab('show');
  // $('#tablanguages').tab
  $('#entities a.nav-link').click(function (event) {
    event.preventDefault();
    var entity = $(this).attr('datatype');
    var href = $(this).attr('href');
    window.location.hash = href;
    toggleActivation(entity);
    loadEntityCardinality(entity);
  });
});

function filterIds() {
  var fragment = $('input[name=fragment]').val();
  var type = $('#collection-selector input[name=type]:checked').val();
  console.log(type);
  var query = {'fragment': fragment, 'type': type, 'version': version};
  $.get("newviz/dataset-filter-ajax.php", query)
   .done(function(data) {
     var selectorId = (type == 'c') ? 'cid' : 'did';
     console.log('#' + selectorId);
     // $('#' + selectorId + ' option').remove();
     $('#' + selectorId).empty();

     var first = "";
     $.each(data.content, function() {
       if (first == "")
         first = this.value;
       $('#' + selectorId).append(
         '<option value="' + this.value + '">' + this.name + '</option>'
       );
     });
     console.log($('#' + selectorId).html());
     updateIntercestionSelector(first);
   });
}

function updateIntercestionSelector(selectedId) {
  var selectedType = $("input[name='type']:checked").val();
  var query = {'type': selectedType, 'id': selectedId, 'version': version};
  $.get("newviz/intersections-ajax.php", query)
    .done(function(data) {
      if (data.length > 0) {
        $('#intersections').html('');
        var radios = [];

        var total = data.length;
        var unit_size = (total > 6) ? Math.ceil(total / 3) : 0;
        if (unit_size == 0)
          radios.push('<div class="col-lg-4">&nbsp;</div><div class="col-lg-8">');

        for (i in data) {
          var item = data[i];
          if (unit_size > 0 && i % unit_size == 0) {
            if (i != 0)
              radios.push('</div>');
            radios.push('<div class="col-lg-4">');
          }

          var radioEl = "<label>";
          radioEl += '<input type="radio" name="intersection" value="' + item.file + '"/>';
          radioEl += item.name + ' (' + item.count + ')';
          radioEl += '</label><br/>';
          radios.push(radioEl);
        }
        radios.push('</div>');
        $('#intersections').html(radios.join(''));
      } else {
        $('#intersections').html('');
      }
    });
}

function toggleActivation(entity) {
  if (loadedEntity != null) {
    $("a[datatype=" + loadedEntity + "]").parent().removeClass('active');
  }
  $("a[datatype=" + entity + "]").parent().addClass('active');
  loadedEntity = entity;
}

function isMultilingualityPanel(tabId) {
  return (tabId == '#multilingual-score'
       || tabId == '#multilingual-score-general'
       || tabId == '#multilingual-score-languages');
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
  var query = {
    'id': id, 'type': type, 'intersection': intersection,
    'entity': entity, 'version': version, 'development': development
  };
  $.get("newviz/multilinguality-ajax.php", query)
   .done(function(data) {
      $('#multilinguality-content').html(data);
      $(".nav-tabs a").click(function(e) {
        e.preventDefault();
        window.location.hash = $(this).attr('href');
        $(this).tab('show');
      });
      var tabId = window.location.hash;
      var selector = '#multilingual-details-tab a[href = "' + tabId + '"]';
      $(selector).tab('show');
   });
}

function loadRecordPatterns() {
  var query = {
    'id': id, 'type': type, 'intersection': intersection,
    'count': count, 'version': version, 'development': development
  };
  console.log("query: ");
  console.log(query);
  $.get("newviz/record-patterns-ajax.php", query)
   .done(function(data) {
     $('#record-patterns-content').html(data);
     $(".nav-tabs a").click(function() {
       $(this).tab('show');
     });
   });
}

function loadUniqueness() {
  var query = {
    'id': id, 'type': type, 'intersection': intersection,
    'count': count, 'version': version, 'development': development
  };
  $.get("newviz/uniqueness-ajax.php", query)
  .done(function(data) {
    $('#uniqueness-content').html(data);
    $(".nav-tabs a").click(function() {
      $(this).tab('show');
    });
  });
}

function loadEntityCardinality(entity) {
  var query = {
    'id': id, 'type': type, 'intersection': intersection,
    'entity': entity, 'version': version, 'development': development
  };
  $.get("newviz/cardinality-ajax.php", query)
    .done(function(data) {
      $('#cardinality-content').html(data);

      $('a.qa-show-details').click(function (event) {
        event.preventDefault();
        var tabId = $(this).attr('class').replace('qa-show-details ', '#details-');
        $(tabId).toggle();
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
        $(this).css('color', '#337ab7');
      });
      $("[data-toggle='histogram-popover']").on('show.bs.popover', function(){
        processHistogramPopoverContent($(this));
      });
      $('[data-toggle="histogram-popover"]').popover({html: true});
      $('[data-toggle="tooltip"]').tooltip();

      $("a.most-frequent-values").click(function(event) {
        event.preventDefault();
        var field = $(this).attr('class').replace('most-frequent-values ', '');
        showMostFrequentValues(field);
      });

    });
}

function showMostFrequentValues(field) {
  var url = getMostFrequentValuesUrl(field);
  $.get(url)
    .done(function(data) {
      var text = [];
      for (i in data.facets[0].fields) {
        var facet = data.facets[0].fields[i];
        text.push(facet.label + ' <em>(' + facet.count + ')</em>');
      }
      var targetId = '#most-frequent-values-' + field;
      $(targetId).html(text.join(', '));
    });
}

function getMostFrequentValuesUrl(field) {
  var url = 'https://www.europeana.eu/api/v2/search.json?wskey=api2demo&rows=0&profile=facets'
          + '&facet=' + field + '&f.' + field + '.facet.limit=100'
          + '&query=' + getMostFrequentValuesQuery();
  return url;
}

function getMostFrequentValuesQuery() {
  var key = (type == 'd') ? 'DATA_PROVIDER' : 'europeana_collectionName';
  var value = '%22' + collectionId + '%22'
  return key + ':' + value;
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

    var query = {'q': q, 'fq': fq, 'rows': 10, 'version': version};
    $.get('newviz/solr-ajax.php', query)
    .done(function(data){
      var portalUrl = 'https://www.europeana.eu/portal/en/record';
      var items = new Array();
      for (i in data.ids) {
        var recordId = data.ids[i];
        var links = new Array();
        links.push('<a target="_blank" href="' + portalUrl + recordId + '.json"'
          + ' title="record id: ' + recordId + '" class="external">data</a>');
        links.push('<a target="_blank" href="' + portalUrl + recordId + '.html"'
          + ' title="record id: ' + recordId + '" class="external">portal</a>');
        links.push('<a href="record.php?id=' + recordId + '&version=' + version + '"'
          + ' title="record id: ' + recordId + '">QA</a>');
        var item = 'visit record (' + links.join(', ') + ')';
        items.push('<li>' + item + '</li>');
      }
      var content = '<ul>' + items.join('') + '</ul>';
      $('#' + targetId).html(content);
    });
  }
}

$("a.qa-show-details").click(function (event) {
  event.preventDefault();
  var tabId = $(this).attr('class').replace('qa-show-details ', '#details-');
  $(tabId).toggle();
});
{/literal}
</script>
</body>
</html>
