<table id="generic" class="table table-condensed table-striped tablesorter table-header-rotated">
  <thead>
  <tr>
    <th></th>
    <th></th>
    <th colspan="6">Provider Proxy</th>
    <th colspan="6">Europeana Proxy</th>
    <th colspan="6">Full object</th>
  </tr>
  <tr>
    <th>Level</th>
    <th>Metric</th>
    <?php foreach ($data->generic_prefixes as $prefix) { ?>
      <th class="first rotate"><div><span>min</span></div></th>
      <th class="rotate"><div><span>max</span></div></th>
      <th class="rotate"><div><span>range</span></div></th>
      <th class="rotate"><div><span>median</span></div></th>
      <th class="rotate"><div><span>mean</span></div></th>
      <th class="rotate"><div><span>st.&nbsp;dev.</span></div></th>
    <?php } ?>
  </tr>
  </thead>
  <tbody>
  <?php
  $counter = 0;
  foreach ($data->assocStat['generic'] as $metric => $proxies) {
    $counter++
    ?>
    <tr>
      <?php if ($counter == 1) { ?>
        <td class="level">property</td>
      <?php } else if ($counter == 2) { ?>
        <td class="level" rowspan="3">record/object</td>
      <?php } else { ?>
      <?php } ?>
      <td class="metric"><a href="#<?= $proxies['provider']->_row ?>"><?= $data->fields[$metric] ?></a></td>
      <?php foreach ($data->generic_prefixes as $prefix => $label) { ?>
        <td class="first"><?= conditional_format($proxies[$prefix]->min); ?></td>
        <td><?= conditional_format($proxies[$prefix]->max); ?></td>
        <td><?= conditional_format($proxies[$prefix]->range); ?></td>
        <td><?= conditional_format($proxies[$prefix]->median); ?></td>
        <td><?= conditional_format($proxies[$prefix]->mean); ?></td>
        <td><?= conditional_format($proxies[$prefix]->{'std.dev'}); ?></td>
      <?php } ?>
    </tr>
  <?php } ?>
  </tbody>
</table>

<ul class="nav nav-tabs" id="multilingual-details-tab">
    <li class="active"><a href="#individual-fields">Language distribution</a></li>
    <li><a href="#all-fields">All fields</a></li>
</ul>
<div class="tab-content">
  <div id="individual-fields" class="tab-pane active">
    <div class="row">
      <form id="language-heatmap">
        <select id="language-distribution-selector">
          <?php foreach ($data->languageDistribution as $field => $metrics) { ?>
            <option value="<?= $field ?>"><?= fieldLabel($field) ?></option>
          <?php } ?>
        </select>
        <input type="checkbox" name="exclusions[]" value="0" id="excludeZeros" />
        <label for="excludeZeros">Exclude records with fields without language tag</label>

        <input type="checkbox" name="exclusions[]" value="1" id="showNoInstances" />
        <label for="showNoInstances">Exlude records without field</label>
      </form>

      <p>
        <i class="fa fa-info-circle"></i>
        This graphic shows you the diversity of language tags for a given field and their
        distribution across a dataset. This visualization allows the exclusion of records
        where the respective field is missing and/or records with the field is present but
        without a language.
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
        $('#showNoInstances').on('change', function () {
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
          var showNoInstances = $('#showNoInstances').is(':checked') ? 0 : 1;

          var treeMapUrl = 'plainjson2tree.php?field=' + field
                         + '&excludeZeros=' + excludeZeros //  . (int)$excludeZeros
                         + '&showNoInstances=' + showNoInstances // . (int)$showNoInstances
                         + '&collectionId=<?= $data->collectionId ?>';
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
            text = d.name;
          } else {
            text = 'language code: ' + d.name;
          }
          if (d.size !== undefined) {
            var count = d.size.toString().replace(/./g, function(c, i, a) {
              return i && c !== "." && ((a.length - i) % 3 === 0) ? ',' + c : c;
            });
            text += "\n" + 'count: ' + count;
          }
          return text;
        }
      </script>
    </div>
  </div>
  <div id="all-fields" class="tab-pane">
    <table id="all-fields-table" class="table table-condensed table-striped tablesorter">
      <thead>
      <tr>
        <th rowspan="2">field</th>
        <th colspan="2">Number of tagged literals</th>
        <th colspan="2">Number of distinct language tags</th>
        <th colspan="2">Number of tagged literals per language</th>
      </tr>
      <tr>
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
          <td><?= $field ?></td>

          <?php foreach ($metrics as $metric => $objects) { ?>
            <?php foreach ($objects as $object_name => $object) { ?>
              <td title="<?= $object->mean; ?>"><?= conditional_format($object->mean) ?></td>
            <?php } ?>
          <?php } ?>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
</div>
