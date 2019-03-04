{include file="../common/html-header.smarty.tpl"}
<body>

<div class="container">
  <div class="text-right">
    <a href=".">Go to the stable version</a>
  </div>
  <div class="page-header">
    <h3>Metadata Quality Assurance Framework for Europeana</h3>
  </div>

    {if ($development)}
      <ul class="nav nav-tabs" id="formTab">
        <li{if $type == 'c'} class="active"{/if}><a href="#by-dataset-form">by dataset</a></li>
        <li{if $type == 'd'} class="active"{/if}><a href="#by-dataprovider-form">by data provider</a></li>
        <li{if $type == 'p'} class="active"{/if}><a href="#by-provider-form">by provider</a></li>
        <li{if $type == 'cn'} class="active"{/if}><a href="#by-country-form">by country</a></li>
        <li{if $type == 'l'} class="active"{/if}><a href="#by-language-form">by language</a></li>
        <li{if $type == 'a'} class="active"{/if}><a href="#whole-form">whole</a></li>
      </ul>

      <div class="tab-content form-selector">
        <div id="by-dataset-form" class="tab-pane{if $type == 'c'} active{/if}">
          <div class="row">
            <form id="collection-selector">

              <input type="hidden" name="type" value="c">
              <label for="fragment">filter the list:</label>
              <input type="text" name="fragment" value="{if $type == 'c'}{$fragment}{/if}" onkeyup="filterIds(this.form);"><br>
              {strip}
                <select name="id" id="cid">
                  {foreach $datasets as $cid => $name}
                    <option value="{$cid}"{if ($type == 'c' && $id == $cid)} selected="selected" title="{$name}"{/if}>{$name}</option>
                  {/foreach}
                </select>
              {/strip}
              <input type="hidden" name="version" value="{$version}"/>
              <input type="hidden" name="development" value="{$development}"/>
              <input type="submit" class="btn btn-dark btn-sm" aria-hidden="true" value="Display"><br/>
            </form>
          </div>
        </div>
        <div id="by-dataprovider-form" class="tab-pane{if $type == 'd'} active{/if}">
          <div class="row">
            <form id="collection-selector">

              <input type="hidden" name="type" value="d">
              <label for="fragment">filter the list:</label>
              <input type="text" name="fragment" value="{if $type == 'd'}{$fragment}{/if}" onkeyup="filterIds(this.form);"><br>
              {strip}
                <select name="id" id="did">
                  {foreach $dataproviders as $did => $name}
                    <option value="{$did}"{if ($type == 'd' && $id == $did)} selected="selected" title="{$name}"{/if}>{$name}</option>
                  {/foreach}
                </select>
              {/strip}
              <input type="hidden" name="version" value="{$version}"/>
              <input type="hidden" name="development" value="{$development}"/>
              <input type="submit" class="btn btn-dark btn-sm" aria-hidden="true" value="Display"><br/>
            </form>
          </div>
        </div>
        <div id="by-provider-form" class="tab-pane{if $type == 'p'} active{/if}">
          <div class="row">
            <form id="collection-selector">

              <input type="hidden" name="type" value="p">
              <label for="fragment">filter the list:</label>
              <input type="text" name="fragment" value="{if $type == 'p'}{$fragment}{/if}" onkeyup="filterIds(this.form);"><br>
              {strip}
                <select name="id" id="pid">
                  {foreach $providers as $pid => $name}
                    <option value="{$pid}"{if ($type == 'p' && $id == $pid)} selected="selected" title="{$name}"{/if}>{$name}</option>
                  {/foreach}
                </select>
              {/strip}
              <input type="hidden" name="version" value="{$version}"/>
              <input type="hidden" name="development" value="{$development}"/>
              <input type="submit" class="btn btn-dark btn-sm" aria-hidden="true" value="Display"><br/>
            </form>
          </div>
        </div>
        <div id="by-country-form" class="tab-pane{if $type == 'cn'} active{/if}">
          <div class="row">
            <form id="collection-selector">

              <input type="hidden" name="type" value="cn">
              {strip}
                <select name="id" id="cnid">
                  {foreach $countries as $cnid => $name}
                    <option value="{$cnid}"{if ($type == 'cn' && $id == $cnid)} selected="selected" title="{$name}"{/if}>{$name}</option>
                  {/foreach}
                </select>
              {/strip}
              <input type="hidden" name="version" value="{$version}"/>
              <input type="hidden" name="development" value="{$development}"/>
              <input type="submit" class="btn btn-dark btn-sm" aria-hidden="true" value="Display"><br/>
            </form>
          </div>
        </div>
        <div id="by-language-form" class="tab-pane{if $type == 'l'} active{/if}">
          <div class="row">
            <form id="collection-selector">

              <input type="hidden" name="type" value="l">
              {strip}
                <select name="id" id="cnid">
                  {foreach $languages as $lid => $name}
                    <option value="{$lid}"{if ($type == 'l' && $id == $lid)} selected="selected" title="{$name}"{/if}>{$name}</option>
                  {/foreach}
                </select>
              {/strip}
              <input type="hidden" name="version" value="{$version}"/>
              <input type="hidden" name="development" value="{$development}"/>
              <input type="submit" class="btn btn-dark btn-sm" aria-hidden="true" value="Display"><br/>
            </form>
          </div>
        </div>
        <div id="whole-form" class="tab-pane{if $type == 'a'} active{/if}">
          <div class="row">
            <form id="collection-selector">
              <input type="hidden" name="type" value="a">
              <input type="hidden" name="id" value="all">
              <input type="hidden" name="version" value="{$version}"/>
              <input type="hidden" name="development" value="{$development}"/>
              <input type="submit" class="btn btn-dark btn-sm" aria-hidden="true" value="Display"><br/>
            </form>
          </div>
        </div>
      </div>
      <div class="row" id="intersections-container">
        <form id="collection-selector">
          <input type="hidden" name="version" value="{$version}"/>
          <input type="hidden" name="development" value="{$development}"/>
          <input type="hidden" name="type" value="a">
          <input type="hidden" name="id" value="all">
          <input type="hidden" name="intersection" value="{$intersection}">
          <input type="hidden" name="fragment" value="{$fragment}">
          <input type="hidden" name="type2" value="{$type2}">
          <input type="hidden" name="id2" value="{$id2}">
          <input type="hidden" name="type3" value="{$type3}">
          <input type="hidden" name="id3" value="{$id3}">
          <div id="intersections">
            {assign var=intersectioCounter value=0}
            {foreach $intersections->list as $type => $items}
              {assign var=intersectioCounter value=($intersectioCounter + 1)}
              <div class="row intersections-{$type}">
                <legend>{$intersectionLabels[$type]} ({$items->count}):</legend>
                {for $j=0 to 2}
                  <div class="col-lg-4">
                    {for $i=$j to $items->count-1 step 3}
                      {strip}
                        {assign var=item value=$items->items[$i]}
                        <label>
                        {if !is_object($item)}{$item|json_encode}{/if}
                        <input type="radio" name="intersection-{$intersectioCounter}" value="{$item->file}" data-type="{$type}" data-id="{$item->id}" data-intersection="{$item->file}" data-count="{$item->count|number_format:0:'.':' '}"
                          {if ((isset($intersection) && $item->file == $intersection)
                            || ($type2 == $type && $id2 == $item->id)
                            || ($type3 == $type && $id3 == $item->id))} checked="checked"{/if}/>
                        {$item->name} (<span class="count">{$item->count|number_format:0:'.':' '}</span>)
                      </label><br/>{/strip}
                    {/for}
                  </div>
                {/for}
              </div>
            {/foreach}
          </div>
          <input type="button" class="btn pull-right" id="reset-intersections" value="clear selections">
        </form>
      </div>

    {else}
      <form id="collection-selector">
        <div class="row">
          <div class="col-lg-4">
            <label><input type="radio" name="type" value="c"{if ($type == 'c')} checked="checked"{/if}>select a dataset</label>
            <label><input type="radio" name="type" value="d"{if ($type == 'd')} checked="checked"{/if}>select a data provider</label>
          </div>
          <div class="col-lg-8">
            <label for="fragment">filter the list:</label>
            <input type="text" name="fragment" value="{$fragment}" onkeyup="filterIds(this.form);"><br>
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
    {/if}


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
          <p>Dataset:
            {if $development}
              {$entityCounts->provider_proxy_rdf_about}
            {else}
              {$entityCounts->proxy_rdf_about}
            {/if} records</p>
          <ul id="entities" class="nav">
            <li class="nav-item">
              <a class="nav-link" href="#cardinality-score-providedcho" datatype="ProvidedCHO">ProvidedCHO
                {if !$development}
                  ({$entityCounts->proxy_rdf_about})
                {/if}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#cardinality-score-agent" datatype="Agent">Agent
                {if !$development}
                  ({$entityCounts->agent_rdf_about})
                {/if}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#cardinality-score-timespan" datatype="Timespan">Timespan
                {if !$development}
                  ({$entityCounts->timespan_rdf_about})
                {/if}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#cardinality-score-concept" datatype="Concept">Concept
                {if !$development}
                  ({$entityCounts->concept_rdf_about})
                {/if}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#cardinality-score-place" datatype="Place">Place
                {if !$development}
                  ({$entityCounts->place_rdf_about})
                {/if}</a>
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
        <p>Dataset:
          {if $development && $version == 'v2018-08'}
            {$entityCounts->provider_proxy_rdf_about}
          {else}
            {$entityCounts->proxy_rdf_about}
          {/if} records</p>
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
var type2 = '{$type2}';
var id2 = '{$id2}';
var type3 = '{$type3}';
var id3 = '{$id3}';
var version = '{$version}';
var development = {(int) $development};
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
    console.log('else branch 4 ' + tabId);
    loadEntityCardinality('ProvidedCHO');
    loadedEntity = 'ProvidedCHO';
    toggleActivation(loadedEntity);
    $('.nav-tabs a[href="' + tabId + '"]').tab('show');
  }

  $(".nav-tabs a").click(function(event) {
    event.preventDefault();
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

  if (!development) {
    showType(type);
    $("input[name='type']").on('change', function () {
      showType($(this).val());
    })
  }

  $("form#collection-selector select[name='id']").on('change', function(){
    var selectedType = $(this).siblings('input[name=type]').val();
    var selectedId = $(this).val();
    updateIntercestionSelector(selectedType, selectedId);
  })

  watchIntersections();
  $('#reset-intersections').on('click', function () {
    resetIntersections();
  })
  if (type2 != '' && id2 != '') {
    updateIntercestionSelector(type, id, type2, id2, type3, id3, intersection);
  }
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

function watchIntersections() {
  if (development) {
    var types = ['c', 'd', 'p'];
    $('#intersections input[name^="intersection-"]').on('click', function () {
      var current = $(this);
      var intersection = current.val();
      var isCdp = intersection.match(/^cdp/) != null;
      var activeTab = $('.tab-pane.active').attr('id');
      var activeType = $('#' + activeTab + ' input[name=type]').val();
      var activeId = $('#' + activeTab + ' select[name=id]').val();
      var oForm = current.closest('form');
      if (!isCdp) {
        var type2 = current.attr('data-type');
        var id2 = current.attr('data-id');
        $('input[name=type2]', oForm).val(type2);
        $('input[name=id2]', oForm).val(id2);

        var targetType;
        for (i in types) {
          if (types[i] != activeType && types[i] != type2) {
            targetType = types[i];
            break;
          }
        }
        updateIntercestionSelector(activeType, activeId, type2, id2, targetType, null, intersection)
      } else {
        // console.log('intersection: ' + intersection + ', activeTab: ' + activeTab + ', activeType: ' + activeType + ', activeId: ' + activeId);
        $('input[name=intersection]', oForm).val(intersection);
        $('input[name=type]', oForm).val(activeType);
        $('input[name=id]', oForm).val(activeId);
        $('input[name=type3]', oForm).val(current.attr('data-type'));
        $('input[name=id3]', oForm).val(current.attr('data-id'));
        $('input[name=fragment]', oForm).val($('#' + activeTab + ' input[name=fragment]').val());
        oForm.submit();
      }
      console.log('watchIntersections::hey');
    })
  }
}

function filterIds(oForm) {
  var selectorId = (type == 'c') ? 'cid' : 'did';

  if (development) {
    var type = $('input[name=type]', $(oForm)).val();
    var fragment = $('input[name=fragment]', $(oForm)).val();
  } else {
    var type = $('#collection-selector input[name=type]:checked').val();
    var fragment = $('input[name=fragment]').val();
  }

  console.log(type);
  var selectorIndex = {c: 'cid', d: 'did', p: 'pid'};
  var query = {'fragment': fragment, 'type': type, 'version': version};
  $.get("newviz/dataset-filter-ajax.php", query)
   .done(function(data) {
     var selectorId = selectorIndex[type]; //(type == 'c') ? 'cid' : 'did';
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
     updateIntercestionSelector(type, first);
   });
}

function resetIntersections() {
  $('input[name^="intersection-"]').each(function() {
    resetIntersection($(this));
  });
}

function resetIntersection(current) {
  current.removeAttr('checked');
  current.removeAttr('disabled');
  current.parent().removeClass('disabled');
  current.parent().removeClass('selectable');
  current.siblings('span.count').html(current.attr('data-count'));
  current.val(current.attr('data-value'));
}

function updateIntercestionSelector(selectedType, selectedId, type2, id2, targetType, targetId, intersection) {
  var query = {'type': selectedType, 'id': selectedId, 'version': version, 'development': development};
  if (development) {
    query.format = 'html';
  }

  if (typeof(type2) != 'undefined' && typeof(intersection) != 'undefined') {
    query.type2 = type2;
    query.id2 = id2;
    query.targetType = targetType;
    query.intersection = intersection;
    query.format = 'json';
  }

  console.log('query:');
  console.log(query);
  $.get("newviz/intersections-ajax.php", query)
    .done(function(data) {
      var triggerWatcher = true;
      if (development) {
        if (query.format == 'html') {
          $('#intersections').html(data);
        } else {
          triggerWatcher = false;
          var items = data.list[query.targetType].items;
          var nf = Intl.NumberFormat();
          $('input[name^="intersection-"][data-type=' + query.targetType + ']').each(function() {
            var current = $(this);
            resetIntersection(current);
            if (current.attr('data-type') == query.targetType) {
              var found = false;
              for (i in items) {
                var item = items[i];
                if (current.attr('data-id') == item.id) {
                  found = true;
                  current.attr('data-value', current.val());
                  current.val(item.file);
                  var count = nf.format(item.count).replace(',', ' ') + ' out of ' + current.attr('data-count');
                  current.siblings('span.count').html(count);
                  current.parent().addClass('selectable');
                  if (targetId != null && current.attr('data-id') == targetId) {
                    current.prop('checked', true);
                  }
                  break;
                }
              }
              if (!found) {
                current.attr('disabled', 'disabled');
                current.parent().addClass('disabled')
              }
            }
          });
        }
      } else {
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
      }
      if (triggerWatcher) {
        watchIntersections();
      }
      // } else {
      //   $('#intersections').html('');
      // }
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
          + ' title="record id: ' + recordId + '">details</a>');
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
