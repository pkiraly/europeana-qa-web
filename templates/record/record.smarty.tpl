<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Record view</title>
  <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.1.0/styles/default.min.css"/>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
        integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway" type="text/css"/>
  <script src="https://use.fontawesome.com/feff23b961.js"></script>
  <link rel="stylesheet" href="europeana-qa.css?a={$rand}" type="text/css"/>
  <link rel="stylesheet" href="chart.css?a={$rand}" type="text/css"/>
  <link rel="stylesheet" href="style/newviz.css?a={$rand}" type="text/css"/>
  <!-- choose a theme file -->
  <link rel="stylesheet" href="jquery/theme.default.min.css">
  <link rel="icon" type="image/png" href="https://europeana-style-production.cdnedge.bluemix.net/v0.4.29/images/favicons/favicon-32x32.png?ver=alpha" sizes="32x32">
  <!-- load jQuery and tablesorter scripts -->
  <script type="text/javascript" src="jquery/jquery-1.9.1.min.js"></script>
</head>
<body>

<div class="container">

<div class="page-header">
  <h1>Record investigation</h1>
  <h3><a href=".">Metadata Quality Assurance Framework for Europeana</a></h3>
</div>

<div class="col-md-12">
  <p>
    The overview of the "completeness" metrics of a Europeana record. You can check if the record
    has the most important fields, which are important for the different functionalities at the
    Europeana database. The functionalities:
  </p>
  <ul type="square">
    <li>Descriptiveness &ndash; how much information has the metadata to describe what the object is about</li>
    <li>Searchability &ndash; the fields most heavily used in searches</li>
    <li>Contextualization &ndash; the bases for finding connected entities (persons, places, times, etc.) in the record</li>
    <li>Identification &ndash; for unambiguously identifying the object</li>
    <li>Browsing &ndash; for the browsing features at the portal</li>
    <li>Viewing &ndash; for the displaying at the portal</li>
    <li>Re-usability &ndash; for reusing the metadata records in other systems</li>
    <li>Multilinguality &ndash; for multilingual aspects, to be understandable for all European citizen</li>
  </ul>
  <p>
    Above those functionalities, we measure the mandatory element, and a collection of all the fields
    covered in these functionalities.
  </p>

  <p>Check a specific Europeana record by entering its ID.</p>
  <form>
    <input type="text" name="id" value="{$id}" size="120" />
    <input type="submit" value="Check ID"/>
  </form>

  <h2>Available representations</h2>
  <ul type="square">
    <li>Canonical identifier: <a href="http://data.europeana.eu/item/{$id}" target="_blank">http://data.europeana.eu/item/{$id}</a></li>
    <li><a href="http://europeana.eu/portal/record/{$id}.html" target="_blank">Europeana portal</a></li>
    <li><a href="http://www.europeana.eu/api/v2/record/{$id}.json?wskey=api2demo" target="_blank">REST API: full record in JSON</a></li>
    <li><a href="http://www.europeana.eu/api/v2/record/{$id}.jsonld?wskey=api2demo" target="_blank">REST API: full record in JSON-LD</a></li>
    <li><a href="http://www.europeana.eu/api/v2/search.json?query=europeana_id:%22/{$id}%22&wskey=api2demo" target="_blank">REST API: search record</a></li>
    <li><a href="http://oai.europeana.eu/oaicat/OAIHandler?verb=GetRecord&metadataPrefix=edm&identifier=http://data.europeana.eu/item/{$id}" target="_blank">OAI-PMH server</a></li>
  </ul>
</div>

<div class="col-md-12">
<h2>Completeness of elements & functionalities</h2>

  {if !empty($problems)}
    <pre>{$problems|print_r}</pre>
  {/if}

<table id="fields">
  <thead>
    <tr>
{foreach $table[0] as $key}
  {if strpos($key, ':') === FALSE}
      <th><div class="functionality"><span>{$key}</span></div></th>
  {/if}
{/foreach}
    </tr>
  </thead>
  <tbody>
{foreach $table as $key => $row}
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

<h2>Statistics</h2>
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
      {if isset($metrics->subdimensions[$key])}
        <tr>
          <td>{$object['label']}</td>
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
      <th>Whole EDM object</th>
    </tr>
    </thead>
    <tbody>
    {foreach $metrics->multilinguality->global as $metric => $value}
      <tr>
        <td>{$metric}</td>
        <td>{$value->providerproxy}</td>
        <td>{$value->europeanaproxy}</td>
        <td>{$value->object}</td>
      </tr>
    {/foreach}
    </tbody>
  </table>
  <p>Languages in the object: {$metrics->languages->global|join:", "}</p>

  <h3>Problem catalog</h3>
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
      <th>Exists?</th>
      <th>Cardinality</th>
      <th>Languages</th>
      <th>Multilingual<br/>saturation</th>
    </tr>
  </thead>
  <tbody>
    {foreach $metrics->existence as $field => $value}
      {assign var="lowerField" value=strtolower($field)}
      <tr{if $value == 0} class="remainder"{/if}>
        <td>{$field}</td>
        <td class="field-value">
          {if $value != 0 && isset($structure[$field])}
            {if count($structure[$field]) == 1}
              {$structure[$field][0]}
            {else}
              <ul type="square">
                {foreach $structure[$field] as $fieldValue}
                  <li>{$fieldValue}</li>
                {/foreach}
              </ul>
            {/if}
          {/if}
        </td>
        <td>{if $value == 1}true{else}false{/if}</td>
        <td>{if $value == 1}{$metrics->cardinality[$field]}{/if}</td>
        <td>
          {if $value == 1 && isset($metrics->languages->fields[$lowerField])}
            {$metrics->languages->fields[$lowerField]|join:", "}
          {/if}
        </td>
        <td>
          {assign var="multilingualityField" value=strtolower(str_replace('Proxy/', '', $field))}
          {if $value != 0 && isset($metrics->multilinguality->fields['provider'][$multilingualityField])}
            {assign var="multilinguality" value=$metrics->multilinguality->fields['provider'][$multilingualityField]}
            <table class="multilinguality">
              <tbody>
                <tr>
                  <td>tagged literals</td>
                  <td>{$multilinguality['taggedliterals']}</td>
                </tr>
                <tr>
                  <td>number of languages</td>
                  <td>{$multilinguality['languages']}</td>
                </tr>
                <tr>
                  <td>literals per language</td>
                  <td>{$multilinguality['literalsperlanguage']}</td>
                </tr>
              </tbody>
            </table>
          {/if}
        </td>
      </tr>
    {/foreach}
  </tbody>
</table>

<h2>Metadata structure as represented in JSON</h2>
<pre id="code"><code class="json">{json_encode($metadata, JSON_PRETTY_PRINT)}</code></pre>

</div>

<div class="col-md-12">
  <footer>
    <p><a href="http://pkiraly.github.io/">What is this?</a> &ndash; about the Metadata Quality Assurance Framework project.</p>
  </footer>
</div>

</div>

<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.1.0/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>

</body>
</html>
