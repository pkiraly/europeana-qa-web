{include file="../common/html-header.smarty.tpl"}
<body>

<div class="container">

<div class="page-header">
  <h1>Record investigation</h1>
  <h3><a href="newviz.php">Metadata Quality Assurance Framework for Europeana</a></h3>
</div>

<div class="col-md-12">
  <p>
    The overview of the "completeness" metrics of a Europeana record. You can check if the record
    has the most important fields, which are important for the different functionalities at the
    Europeana database.
  </p>

  <p>
    Warning: this pages uses live data from the Europeana API, which maybe slightly different from
    the status of the same record at the time of calculation.
  </p>

  <p>Check a specific Europeana record by entering its ID.</p>
  <form>
    <input type="text" name="id" value="{$id}" size="120" />
    <input type="submit" value="Check ID"/>
  </form>

  <div class="row">
    <div class="col-md-6">
      <h4>Available representations</h4>
      <ul type="square">
        <li>Canonical identifier: <a href="http://data.europeana.eu/item{$id}" target="_blank">http://data.europeana.eu/item{$id}</a></li>
        <li><a href="http://europeana.eu/portal/record{$id}.html" target="_blank">Europeana portal</a></li>
        <li><a href="http://www.europeana.eu/api/v2/record{$id}.json?wskey=hgQQMdjcG" target="_blank">REST API: full record in JSON</a></li>
        <li><a href="http://www.europeana.eu/api/v2/record{$id}.jsonld?wskey=hgQQMdjcG" target="_blank">REST API: full record in JSON-LD</a></li>
        <li><a href="http://www.europeana.eu/api/v2/search.json?query=europeana_id:%22{$id}%22&wskey=hgQQMdjcG" target="_blank">REST API: search record</a></li>
        <li><a href="http://oai.europeana.eu/oaicat/OAIHandler?verb=GetRecord&metadataPrefix=edm&identifier=http://data.europeana.eu/item{$id}" target="_blank">OAI-PMH server</a></li>
      </ul>
    </div>
    <div class="col-md-6">
      <h4>This record belongs to</h4>
      <ul type="square">
        <li>dataset:
          <a href="newviz.php?type=c&id={$metrics->identifiers['dataset']}&version={$version}&development=1">{$dataset}</a>
        </li>
        <li>data provider:
          <a href="newviz.php?type=d&id={$metrics->identifiers['dataProvider']}&version={$version}&development=1">{$dataProvider}</a>
        </li>
        <li>provider:
          <a href="newviz.php?type=p&id={$metrics->identifiers['provider']}&version={$version}&development=1">{$provider}</a>
        </li>
        <li>country:
          <a href="newviz.php?type=cn&id={$metrics->identifiers['country']}&version={$version}&development=1">{$country}</a>
        </li>
        <li>language:
          <a href="newviz.php?type=l&id={$metrics->identifiers['language']}&version={$version}&development=1">{$language}</a>
        </li>
      </ul>
    </div>
  </div>
</div>


<div class="col-md-12">
<h2>Completeness of elements & functionalities</h2>

  {if !empty($problems)}
    <pre>{$problems|print_r}</pre>
  {/if}

  <div class="row">
    <div class="col-md-6">
      <table id="fields">
        <thead>
        <tr>
          {foreach $heatmap->header as $key}
            {if strpos($key, ':') === FALSE}
              <th><div class="functionality"><span>{$key}</span></div></th>
            {/if}
          {/foreach}
        </tr>
        </thead>
        <tbody>
        {foreach $heatmap->rows as $key => $row}
          {if $key != 0}
            <tr>
              {foreach $row as $j => $cell}
                {if $j == 0}
                  <td class="field">{$cell}</td>
                {elseif $cell == ''}
                  <td>&nbsp;</td>
                {else}
                  <td class="{$cell}">&nbsp;</td>
                {/if}
              {/foreach}
            </tr>
          {/if}
        {/foreach}
        </tbody>
      </table>
      <table id="legend" class="table">
        <thead>
        <tr>
          <th></th>
          <th>Explanation of colors</th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td class="green">&nbsp;</td>
          <td>existing element</td>
        </tr>
        <tr>
          <td class="yellow">&nbsp;</td>
          <td>missing element</td>
        </tr>
        <tr>
          <td class="gray">&nbsp;</td>
          <td>missing element, with alternatives</td>
        </tr>
        <tr>
          <td class="red">&nbsp;</td>
          <td>missing mandatory element</td>
        </tr>
        </tbody>
      </table>

    </div>

    <div class="col-md-6">
      <h3>{$graphs['total']['label']}</h3>
      <p><strong>{$metrics->subdimensions['total']}</strong></p>

      <h3>Subdimensions</h3>
      <table id="statistics" class="table table-bordered table-striped">
        <thead>
        <tr>
          <th>Functionality</th>
          <th>Score</th>
        </tr>
        </thead>
        <tbody>
        {foreach $graphs as $key => $object}
          {if $key != 'total' && isset($metrics->subdimensions[$key])}
            <tr>
              <td>{$object['label']}{if isset($object['description'])}<br/><em>{$object['description']}</em>{/if}</td>
              <td>{$metrics->subdimensions[$key]}</td>
            </tr>
          {/if}
        {/foreach}
        </tbody>
      </table>

      <h3>Multilingual saturation</h3>
      <table id="record-multilinguality" class="table table-bordered table-striped">
        <thead>
        <tr>
          <th>metric</th>
          <th>Provider proxy</th>
          <th>Europeana proxy</th>
          <th>Whole object</th>
        </tr>
        </thead>
        <tbody>
        {foreach $multilinguality_labels as $metric => $label}
          <tr>
            <td>{$label}</td>
            <td>{$metrics->multilinguality->global[$metric]->ProviderProxy}</td>
            <td>{$metrics->multilinguality->global[$metric]->EuropeanaProxy}</td>
            <td>{$metrics->multilinguality->global[$metric]->Object}</td>
          </tr>
        {/foreach}
        </tbody>
      </table>
      <p>Languages in the object: {$metrics->languages->global|join:", "}</p>

      <h3>Problem catalog</h3>
      <p>Warning: this is work in progress!</p>
      <table id="statistics" class="table table-bordered table-striped">
        <thead>
        <tr>
          <th>Problem</th>
          <th>Score</th>
        </tr>
        </thead>
        <tbody>
        {foreach $metrics->problemCatalog as $key => $value}
          <tr>
            <td>{$key}</td>
            <td>{$value}</td>
          </tr>
        {/foreach}
        </tbody>
      </table>
    </div>
  </div>

  <h2>Statistics</h2>

  {if isset($analysis) && isset($analysis->termsCollection)}
    <h3>Term frequencies</h3>
    <p>abbreviations</p>
    <ul type="square">
      <li>tf = term frequency within the field</li>
      <li>df = document frequency - who many document has this term in this field?</li>
      <li>tf-idf = term frequency - inverse document frequency. A score measuring the term's importance.
        See <a href="https://en.wikipedia.org/wiki/Tf%E2%80%93idf" target="_blank">tf–idf</a> in Wikipedia.</li>
    </ul>
    <table id="statistics" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Field</th>
          <th>Terms</th>
        </tr>
      </thead>
      <tbody>
        {foreach $analysis->termsCollection as $field => $terms}
          <tr>
            <td>{$field}</td>
            <td>
              <ul type="square">
                {foreach $terms as $term}
                  <li>{$term->term} (tf={$term->tf}, df={$term->df}, tf-idf={$term->tfIdf})</li>
                {/foreach}
              </ul>
            </td>
          </tr>
        {/foreach}
      </tbody>
    </table>
  {/if}
</div>

<div class="col-md-12">

<h3>Analyzed metadata fields</h3>
<table id="statistics" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Field</th>
      <th>Values</th>
      <th>Cardinality</th>
      <th>Languages</th>
      <th>Multilingual<br/>saturation</th>
    </tr>
  </thead>
  <tbody>
    {foreach $metrics->cardinality['provider'] as $field => $value}
      {assign var="lowerField" value=strtolower($field)}
      {assign var="hasEuropeanaProxyValue" value="0"}
      {assign var="hasValue" value="0"}
      {if preg_match('/^Proxy\//', $field)}
        {assign var="europeanaProxyName" value=str_replace('Proxy/', 'EuropeanaProxy/', $field)}
        {if $value != 0}
          {if isset($structure[$field])}
            {assign var="hasValue" value="1"}
            {assign var="sourceValue" value=$structure[$field]}
          {/if}
          {if isset($structure[$europeanaProxyName])}
            {assign var="hasEuropeanaProxyValue" value="1"}
            {assign var="europeanaProxyValue" value=$structure[$europeanaProxyName]}
          {/if}
        {/if}
      {else}
        {assign var="europeanaProxyName" value=""}
        {if isset($structure[$field])}
          {assign var="hasValue" value="1"}
          {assign var="sourceValue" value=$structure[$field]}
        {/if}
      {/if}
      <tr{if $value == 0} class="remainder"{/if}>
        <td class="field-name">{$field} ({$hasValue})</td>
        <td class="field-value">
          {if $value != 0}
            {if isset($structure[$field]) || isset($structure[$europeanaProxyName])}
              {if count($sourceValue) == 1}
                {$sourceValue[0]}
              {else}
                <ul type="square">
                  {foreach $sourceValue as $fieldValue}
                    <li>{$fieldValue}</li>
                  {/foreach}
                </ul>
              {/if}
            {/if}
          {/if}
        </td>
        <td class="cardinality">{$value}</td>
        <td class="languages">
          lowerField: {$lowerField}
          {if $value == 1}
            {if isset($metrics->languages->fields[$lowerField])}
              {$metrics->languages->fields[$lowerField]|join:", "}
            {else}
              none
            {/if}
          {/if}
        </td>
        <td class="multilinguality">
          {assign var="multilingualityField" value=strtolower(str_replace('Proxy/', '', $field))}
          {if $value != 0 &&
              isset($metrics->multilinguality->fields['provider'][$multilingualityField])}
            {assign var="multilinguality"
                    value=$metrics->multilinguality->fields['provider'][$multilingualityField]}
            <table class="multilinguality">
              <tbody>
                <tr>
                  <td class="m-label">tagged literals</td>
                  <td class="m-value">{$multilinguality['taggedLiterals']}</td>
                </tr>
                <tr>
                  <td class="m-label">number of languages</td>
                  <td class="m-value">{$multilinguality['languages']}</td>
                </tr>
                <tr>
                  <td class="m-label">literals per language</td>
                  <td class="m-value">{$multilinguality['literalsPerLanguage']}</td>
                </tr>
              </tbody>
            </table>
          {/if}
        </td>
      </tr>
      {if $hasEuropeanaProxyValue == 1}
        <tr>
          <td class="field-name">{$europeanaProxyName}</td>
          <td class="field-value">
            {if count($europeanaProxyValue) == 1}
              {$europeanaProxyValue[0]}
            {else}
              <ul type="square">
                {foreach $europeanaProxyValue as $fieldValue}
                  <li>{$fieldValue}</li>
                {/foreach}
              </ul>
            {/if}
          </td>
          <td class="cardinality">{count($europeanaProxyValue)}</td>
          <td class="languages"></td>
          <td class="multilinguality">
            {assign var="multilingualityField" value=strtolower(str_replace('Proxy/', '', $field))}
            {if $value != 0 && isset($metrics->multilinguality->fields['europeana'][$multilingualityField])}
              {assign var="multilinguality" value=$metrics->multilinguality->fields['europeana'][$multilingualityField]}
              <table class="multilinguality">
                <tbody>
                <tr>
                  <td class="m-label">tagged literals</td>
                  <td class="m-value">{$multilinguality['taggedLiterals']}</td>
                </tr>
                <tr>
                  <td class="m-label">number of languages</td>
                  <td class="m-value">{$multilinguality['languages']}</td>
                </tr>
                <tr>
                  <td class="m-label">literals per language</td>
                  <td class="m-value">{$multilinguality['literalsPerLanguage']}</td>
                </tr>
                </tbody>
              </table>
            {/if}
          </td>
        </tr>
      {/if}
    {/foreach}
  </tbody>
</table>

<h2>Metadata structure as represented in JSON</h2>
<pre id="code"><code class="json">{json_encode($metadata, JSON_PRETTY_PRINT)}</code></pre>

</div>

<div class="col-md-12">
  <footer>
    {include file="../common/footer.smarty.tpl"}
  </footer>
</div>

</div>

<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.1.0/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>

</body>
</html>
