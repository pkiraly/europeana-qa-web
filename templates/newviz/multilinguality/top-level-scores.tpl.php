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
    <?php foreach ($data->generic_prefixes as $prefix) { ?>
      <th class="first rotate"><div><span>mean</span></div></th>
      <th class="rotate" title="standard deviation"><div><span>st.&nbsp;dev.</span></div></th>
      <th class="rotate details"><div><span>min</span></div></th>
      <th class="rotate details"><div><span>max</span></div></th>
      <th class="rotate details"><div><span>median</span></div></th>
    <?php } ?>
  </tr>
  </thead>
  <tbody>
<?php
  $counter = 0;
  foreach ($data->fields as $metric => $label) {
    $counter++;
    $proxies = $data->assocStat['generic'][$metric]
?>
    <tr>
      <td class="metric"><?= $label ?></td>
      <?php foreach ($data->generic_prefixes as $prefix => $label1) { ?>
        <td class="first"><?= conditional_format($proxies[$prefix]->mean, FALSE, FALSE, 1); ?></td>
        <td><?= conditional_format($proxies[$prefix]->{'std.dev'}, FALSE, FALSE, 1); ?></td>
        <td class="details"><?= conditional_format($proxies[$prefix]->min, FALSE, FALSE, 1); ?></td>
        <td class="details"><?= conditional_format($proxies[$prefix]->max, FALSE, FALSE, 1); ?></td>
        <td class="details"><?= conditional_format($proxies[$prefix]->median, FALSE, FALSE, 1); ?></td>
      <?php } ?>
    </tr>
  <?php } ?>
  </tbody>
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

    <p><em>n/a</em> means that the particular field is not present in any record
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
      <?php foreach ($data->assocStat['specific'] as $field => $metrics) { ?>
        <tr>
          <td><?= str_replace('_', ':', $field); ?></td>
          <?php foreach ($metrics as $metric => $objects) { ?>
            <?php foreach ($objects as $object_name => $object) { ?>
              <td class="numeric" title="<?php
              printf("mean: %f\nstandard deviation: %f\nmin: %f (%s)\nmax: %f (%s)\nrange: %f\nmedian: %f",
                $object->mean,
                (isset($object->{'std.dev'}) ? $object->{'std.dev'} : 0),
                $object->min,
                $object->recMin,
                $object->max,
                $object->recMax,
                $object->range,
                (isset($object->median) ? $object->median : 0)
              ); ?>">
                <?php if ($object->mean == 'NaN') { ?>
                  <span style="color:#999">n/a</span>
                <?php } else {
                  echo conditional_format($object->mean, FALSE, TRUE, 4);
                } ?>
              </td>
            <?php } ?>
          <?php } ?>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
  <div id="individual-fields" class="tab-pane">
    <div class="row">
      <form id="language-heatmap">
        <select id="language-distribution-selector">
          <?php foreach ($data->languageDistribution as $field => $metrics) { ?>
            <option value="<?= $field ?>"><?= fieldLabel($field) ?></option>
          <?php } ?>
        </select>
        <input type="checkbox" name="exclusions[]" value="0" id="excludeZeros" />
        <label for="excludeZeros">Exclude records with fields without language tag</label>

        <!--
        <input type="checkbox" name="exclusions[]" value="1" id="showNoOccurences" />
        <label for="showNoOccurences">Exlude records without field</label>
        -->
      </form>

      <p>
        <i class="fa fa-info-circle"></i>
        This graphic shows you the number and diversity of language tags for a given field and
        the number of literals that have no language tag. This visualisation allows the exclusion
        of fields where the field is present but without a language.
      </p>

      <div id="heatmap"></div>
      <script id="language-distribution-data" type="application/json"><?php echo json_encode($data->languageDistribution); ?></script>
      <script type="text/javascript">
        $('#language-distribution-selector').on('change', function () {
          displayLanguageTreemap();
        });
        $('#excludeZeros').on('change', function () {
          displayLanguageTreemap();
        });
        $('#showNoOccurences').on('change', function () {
          displayLanguageTreemap();
        });

        var margin = {top: 40, right: 10, bottom: 10, left: 10},
          width  = 960 - margin.left - margin.right,
          height = 500 - margin.top  - margin.bottom;

        var color = d3.scale.category20c();

        var node;

        displayLanguageTreemap('aggregated');

        function getTreeMapUrl() {
          var field = $('#language-distribution-selector').val();
          var excludeZeros = $('#excludeZeros').is(':checked') ? 1 : 0;
          var showNoOccurences = 0; //$('#showNoOccurences').is(':checked') ? 0 : 1;

          var treeMapUrl = 'plainjson2tree.php?field=' + field
                         + '&excludeZeros=' + excludeZeros //  . (int)$excludeZeros
                         + '&showNoOccurences=' + showNoOccurences // . (int)$showNoOccurences
                         + '&collectionId=<?= $data->collectionId ?>'
                         + '&version=<?= $data->version ?>';
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
            .style("left", margin.left + "px")
            .style("top", margin.top + "px");

          d3.json(getTreeMapUrl(), function(error, root) {
            if (error) throw error;

            d3.selectAll('#heatmap .node').remove();

            node = heatmap
              .datum(root)
              .selectAll(".node")
              .data(treemap.nodes)
              .enter().append("div")
              .attr("class", "node")
              .attr("title", function(d) {
                return label(d);
              })
              .call(position)
              .style("background", function(d) {
                return d.children ? color(d.name) : null;
              })
              .text(function(d) {
                if (d.children) {
                  return null;
                } else {
                  if (d.name == 'no language') {
                    text = 'literal without language tag';
                  } else if (d.name == 'resource') {
                    text = 'resource value (URI)';
                  } else {
                    text = d.name;
                  }
                  return text;
                }
              });
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
            text = 'no field occurence'; // d.name;
          } else {
            text = 'language code: ' + d.name;
          }
          if (d.size !== undefined) {
            var count = d.size.toString().replace(/./g, function(c, i, a) {
              return i && c !== "." && ((a.length - i) % 3 === 0) ? ',' + c : c;
            });
            text += "\n" + 'number of field occurrences: ' + count;
          }
          return text;
        }
      </script>
    </div>
  </div>
</div>
