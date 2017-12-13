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

<table id="fields" class="table table-condensed table-striped tablesorter">
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
