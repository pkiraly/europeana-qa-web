<table id="generic" class="table table-condensed table-striped tablesorter table-header-rotated">
  <thead>
  <tr>
    <th></th>
    <th colspan="5">Provider Proxy<br/>(original metadata)</th>
    <th colspan="5">Europeana Proxy<br/>(Europeana enrichments)</th>
    <th colspan="5">Full object</th>
  </tr>
  <tr>
    <th>Metric</th>
    {foreach $data->genericPrefixes as $prefix}
      <th class="first rotate"><div><span>mean</span></div></th>
      <th class="rotate" title="standard deviation"><div><span>st.&nbsp;dev.</span></div></th>
      <th class="rotate details"><div><span>min</span></div></th>
      <th class="rotate details"><div><span>max</span></div></th>
      <th class="rotate details"><div><span>median</span></div></th>
    {/foreach}
  </tr>
  </thead>
  {strip}
  <tbody>
    {foreach $data->genericMetrics as $metric => $label}
      {assign var=proxies value=$data->assocStat['generic'][$metric]}
      <tr>
        <td class="metric">{$label}</td>
        {foreach $data->genericPrefixes as $prefix => $label1}
          <td class="first">{$proxies[$prefix]->mean|conditional_format:FALSE:FALSE:1}</td>
          <td>{if isset($proxies[$prefix]->{'std.dev'})}{$proxies[$prefix]->{'std.dev'}|conditional_format:FALSE:FALSE:1}{else}0{/if}</td>
          <td class="details">{$proxies[$prefix]->min|conditional_format:FALSE:FALSE:1}</td>
          <td class="details">{$proxies[$prefix]->max|conditional_format:FALSE:FALSE:1}</td>
          <td class="details">{$proxies[$prefix]->median|conditional_format:FALSE:FALSE:1}</td>
        {/foreach}
      </tr>
    {/foreach}
  </tbody>
  {/strip}
</table>
<ul class="nav nav-tabs" id="multilingual-details-tab">
  <li class="active"><a href="#multilingual-score-general">Field-level details for the table above</a></li>
  <li><a href="#multilingual-score-languages">Distribution of language tags per field</a></li>
</ul>
<div class="tab-content">
  <div id="multilingual-score-general" class="tab-pane active">
    <p>
      <i class="fa fa-info-circle"></i>
      Language tags introduced by the providers or fetched by dereferencing URIs to controlled vocabularies
      provided in the original metadata can be found in the Provider Proxy.</p>

    <p>Language tags added to metadata automatically through multilingual automatic enrichment by Europeana
      are accounted for in the Europeana Proxy.</p>

    <p>The table shows the <em>mean</em> of the number of language tags and literals tagged with a language
      per record, in the selected set.</p>

    <p><em>n/a</em> means that the particular field is not present in any record
      in the collection.</p>

    <table id="multilingual-score-general-table" class="table table-condensed table-striped tablesorter">
      <thead>
      <tr class="primary">
        <th rowspan="2">field</th>
        <th colspan="2" class="double">Number of tagged literals</th>
        <th colspan="2" class="double">Number of distinct language tags</th>
        <th colspan="2" class="double">Number of tagged literals per language tag</th>
      </tr>
      <tr class="secondary">
        <th>Provider</th>
        <th>Europeana</th>
        <th>Provider</th>
        <th>Europeana</th>
        <th>Provider</th>
        <th>Europeana</th>
      </tr>
      </thead>
      <tbody>
      {strip}
      {foreach $data->assocStat['specific'] as $field => $metrics}
        <tr>
          <td>{$field|fieldlabel}</td>
          {foreach $data->specificMetrics as $metric => $metricLabel}
            {foreach $data->specificPrefixes as $prefix => $prefixLabel}
              <td class="numeric">
                {if (!isset($metrics[$metric][$prefix]) || $metrics[$metric][$prefix]->mean == 'NaN')}
                  <span style="color:#999">n/a</span>
                {else}
                  {assign var=object value=$metrics[$metric][$prefix]}
                  <span class="pop" data-toggle="popover" title="details" data-content="min: {$object->min|conditional_format:FALSE:FALSE:3} ({if isset($object->recMin)}{$object->recMin}{/if})|max: {$object->max|conditional_format:FALSE:FALSE:3} ({if isset($object->recMax)}{$object->recMax}{/if})|mean: {$object->mean|conditional_format:FALSE:FALSE:3}|standard deviation: {if isset($object->{'std.dev'})}{$object->{'std.dev'}|conditional_format:FALSE:FALSE:3}{else}0{/if}|median: {if isset($object->median)}{$object->median|conditional_format:FALSE:FALSE:3}{else}0{/if}">
                    <span data-toggle="tooltip" title="Get details">{$object->mean|conditional_format:FALSE:TRUE:3}</span>
                  </span>
                {/if}
              </td>
            {/foreach}
          {/foreach}
        </tr>
      {/foreach}
      </tbody>
      {/strip}
    </table>
  </div>
  <div id="multilingual-score-languages" class="tab-pane">
    <div class="row">
      <form id="language-heatmap">
        {strip}
        <select id="language-distribution-selector">
          {foreach $data->languageDistribution as $field => $metrics}
            <option value="{$field}">{$field|fieldlabel}</option>
          {/foreach}
        </select>
        {/strip}
        <input type="checkbox" name="exclusions[]" value="0" id="excludeZeros" />
        <label for="excludeZeros">Exclude records with fields without language tag</label>

        <!--
        <input type="checkbox" name="exclusions[]" value="1" id="showNoOccurences" />
        <label for="showNoOccurences">Exlude records without field</label>
        -->
      </form>

      <p><i class="fa fa-info-circle"></i>
        This graphic shows you the number and diversity of language tags for a given field and
        the number of literals that have no language tag. This visualisation allows the exclusion
        of fields where the field is present but without a language.
      </p>

      <p>
        Warnings:
        <ul>
          <li>Loading of statistics and examples may take a while in some cases.</li>
          <li>When a field is not used in the dataset, it is still possible to select it, but the display will then show nothing</li>
        </ul>
      </p>

      <div>
        <div id="heatmap"></div>
        <div id="tooltip"></div>
      </div>

      <script id="fields-by-language-data" type="application/json">{$data->fieldsByLanguageList|json_encode}</script>
      <script id="language-distribution-data" type="application/json">{$data->languageDistribution|json_encode}</script>
      <script id="language-fields-data" type="application/json">{$data->allFieldsList|json_encode}</script>
      <script type="text/javascript">
        var fieldsByLanguage = JSON.parse($('#fields-by-language-data').html());
        var languageFields = JSON.parse($('#language-fields-data').html());

        var collectionId = '{$data->collectionId}';
        var version = '{$data->version}';
        {literal}
        $(document).ready(function(){
          var fieldsByLanguage = JSON.parse($('#fields-by-language-data').html());

          $("[data-toggle='popover']").each(function() {
            $(this).css('cursor', 'pointer');
          });
          $("[data-toggle='popover']").on('show.bs.popover', function(){
            var content = $(this).attr('data-content');
            if (!content.match(/\n/)) {
              content = content
                .replace(/\|/g, "<br>\n")
                .replace(
                  /\((.*?)\)/g,
                  "(visit record: "
                  + "<a target=\"_blank\" href=\"https://www.europeana.eu/portal/en/record$1.json\" class=\"external\">data</a>"
                  + ", <a target=\"_blank\" href=\"https://www.europeana.eu/portal/en/record$1.html\" class=\"external\">portal</a>"
                  + ", <a href=\"record.php?id=$1&version=" + version + "\">details</a>"
                  + ")"
                );
              $(this).attr('data-content', content);
            }
          });
          $('[data-toggle="popover"]').popover({html: true});
          $('[data-toggle="tooltip"]').tooltip();
        });
        {/literal}
      </script>
      <script type="text/javascript" src="/europeana-qa/js/multilinguality.treemap.js">
    </div>
  </div>
</div>
