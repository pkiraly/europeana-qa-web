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
    {foreach $data->generic_prefixes as $prefix}
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
    {foreach $data->fields as $metric => $label}
      {assign var=proxies value=$data->assocStat['generic'][$metric]}
      <tr>
        <td class="metric">{$label}</td>
        {foreach $data->generic_prefixes as $prefix => $label1}
          <td class="first">{$proxies[$prefix]->mean|conditional_format:FALSE:FALSE:1}</td>
          <td>{$proxies[$prefix]->{'std.dev'}|conditional_format:FALSE:FALSE:1}</td>
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
  <li class="active"><a href="#all-fields">Field-level details for the table above</a></li>
  <li><a href="#individual-fields">Distribution of language tags per field</a></li>
</ul>
<div class="tab-content">
  <div id="all-fields" class="tab-pane active">
    <p>
      <i class="fa fa-info-circle"></i>
      Language tags introduced by the providers or fetched by dereferencing URIs to controlled vocabularies
      provided in the original metadata can be found in the Provider Proxy.</p>

    <p>Language tags added to metadata automatically through multilingual automatic enrichment by Europeana
      are accounted for in the Europeana Proxy.</p>

    <p>The table shows the <em>mean</em> of the number of language tags and literals tagged with a language
      per record, in the selected set.</p>

    <p><em>n/a</em> means that the particular field is not available in any record
      in the collection.</p>
    <table id="all-fields-table" class="table table-condensed table-striped tablesorter">
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
          {foreach $metrics as $metric => $objects}
            {foreach $objects as $object_name => $object}
              <td class="numeric" title="mean: {$object->mean}|standard deviation: {if isset($object->{'std.dev'})}{$object->{'std.dev'}}{else}0{/if}|min: {$object->min} ({$object->recMin})|max: {$object->max} ({$object->recMax})|range: $object->range|median: {if isset($object->median)}{$object->median}{else}0{/if}">
              {if ($object->mean == 'NaN')}
                <span style="color:#999">n/a</span>
              {else}
                {$object->mean|conditional_format:FALSE:TRUE:3}
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
  <div id="individual-fields" class="tab-pane">
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
        <input type="checkbox" name="exclusions[]" value="1" id="showNoInstances" />
        <label for="showNoInstances">Exlude records without field</label>
        -->
      </form>

      <p>
        <i class="fa fa-info-circle"></i>
        This graphic shows you the number and diversity of language tags for a given field and
        the number of literals that have no language tag. This visualisation allows the exclusion
        of fields where the field is present but without a language.
      </p>

      <div id="heatmap"></div>
      <script id="language-distribution-data" type="application/json">{$data->languageDistribution|json_encode}</script>
      <script type="text/javascript">
        var collectionId = '{$data->collectionId}';
        var version = '{$data->version}';
      </script>
      <script type="text/javascript" src="/europeana-qa/js/multilinguality.treemap.js">
    </div>
  </div>
</div>
