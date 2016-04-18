<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
  <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Completeness</title>
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.1.0/styles/default.min.css" />
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway" type="text/css" />
<link rel="stylesheet" href="europeana-qa.css" />
</head>
<body>

<div class="page-header">
  <h1>Record investigation</h1>
  <h3><a href="./">Metadata Quality Assurance Framework</a></h3>
</div>

<div class="col-md-12">
  <p>The overview of the "completeness" metrics of a Europeana record. You can check if the record has the most important fields, which are important for the different functionalities at the Europeana database. The functionalities:</p>
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
  <p>Above those functionalities, we measure the mandatory element, and a collection of all the fields covered in these functionalities.</p>


  <p>Check a specific Europeana record by entering its ID.</p>
  <form>
    <input type="text" name="id" value="<?php print $id ?>" size="120" />
    <input type="submit" value="Check ID"/>
  </form>
</div>

<div class="col-md-5">
<h2>Completeness of elements & functionalities</h2>

<table id="fields">
  <thead>
    <tr>
<?php foreach ($table[0] as $key) { ?>
  <?php if (strpos($key, ':') === FALSE) { ?>
      <th><div class="functionality"><span><?php print $key; ?></span></div></th>
  <?php } ?>
<?php } ?>
    </tr>
  </thead>
  <tbody>
<?php foreach ($table as $key => $row) { ?>
  <?php if ($key == 0) continue; ?>
    <tr>
  <?php foreach ($row as $j => $cell) { ?>
    <?php if ($j == 0) { ?>
      <td class="field"><?php print $cell; ?></td>
    <?php } elseif ($cell == '') { ?>
      <td>&nbsp;</td>
    <?php } else { ?>
      <td class="<?php print $cell; ?>">&nbsp;</td>
    <?php } ?>
  <?php } ?>
    </tr>
<?php } ?>
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
<table id="statistics" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Functionality</th>
      <th>Score</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($graphs as $key => $object) { ?>
    <tr>
      <td><?php print $object['label']; ?></td>
      <td><?php if (strpos($key, ':') === FALSE) {$key = strtoupper($key);}; print $analysis->results->{$key}; ?></td>
    </tr>
<?php } ?>
  </tbody>
</table>

<h2>Term frequencies</h2>
<p>abbreviations</p>
<ul type="square">
  <li>tf = term frequency within the field</td>
  <li>df = document frequency - who many document has this term in this field?</li>
  <li>tf-idf = term frequency - inverse document frequency. A score measuring the term's importance. See <a href="https://en.wikipedia.org/wiki/Tf%E2%80%93idf" target="_blank">tf–idf</a> in Wikipedia.</li>
</ul>
<table id="statistics" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Field</th>
      <th>Terms</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($analysis->termsCollection as $field => $terms) { ?>
    <tr>
      <td><?= $field ?></td>
      <td>
        <ul type="square">
          <?php foreach ($terms as $term) { ?>
            <li><?php printf("%s (tf=%d, df=%d, tf-idf=%f)", $term->term, $term->tf, $term->df, $term->tfIdf); ?></li>
          <?php } ?>
        </ul>
      </td>
    </tr>
<?php } ?>
  </tbody>
</table>

</div>

<div class="col-md-7">
<h2>Available representations</h2>
<p></p>
<ul type="square">
  <li>Canonical identifier: <a href="http://data.europeana.eu/item/<?php print $id; ?>" target="_blank">http://data.europeana.eu/item/<?php print $id; ?></a></li>
  <li><a href="http://europeana.eu/portal/record/<?php print $id ?>.html" target="_blank">Europeana portal</a></li>
  <li><a href="http://www.europeana.eu/api/v2/record/<?php print $id ?>.json?wskey=api2demo" target="_blank">REST API: full record</a></li>
  <li><a href="http://www.europeana.eu/api/v2/search.json?query=europeana_id:%22/<?php print $id ?>%22&wskey=api2demo" target="_blank">REST API: search record</a></li>
  <li><a href="http://oai.europeana.eu/oaicat/OAIHandler?verb=GetRecord&metadataPrefix=edm&identifier=http://data.europeana.eu/item/<?php print $id; ?>" target="_blank">OAI-PMH server</a></li>
</ul>

<h2>Analyzed metadata fields</h2>
<table id="statistics" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Field</th>
      <th>Values</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($structure as $field => $values) { ?>
    <tr<?php if (strpos($field, '#') === 0) { ?> class="remainder"<?php } ?>>
      <?php if (strpos($field, '#') === 0) { ?>
        <td><?php echo substr($field, 1); ?></td>
      <?php } else { ?>
        <td><?= $field ?></td>
      <?php } ?>
      <td>
        <?php if (count($values) == 1) { ?>
          <?= $values[0] ?>
        <?php } else { ?>
          <ul type="square">
            <?php foreach ($values as $value) { ?>
              <li><?= $value ?></li>
            <?php } ?>
          </ul>
        <?php } ?>
      </td>
    </tr>
<?php } ?>
  </tbody>
</table>
<p>Note: The grey color denotes fields which are part of the Proxy or Aggregation, but was not checked to analyze.</p>

<h2>Metadata structure as represented in the OAI-PMH service</h2>
<pre id="code"><code class="json"><?php print json_encode($metadata, JSON_PRETTY_PRINT); ?></code></pre>
</div>

<div class="col-md-12">
<footer>
  <p><a href="http://pkiraly.github.io/">What is this?</a> &ndash; about the Metadata Quality Assurance Framework project.</p>
</footer>
</div>

<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.1.0/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
</body>
</html>
