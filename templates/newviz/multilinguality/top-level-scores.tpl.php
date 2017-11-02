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
    <th></th>
    <th>Field</th>
    <?php foreach ($data->generic_prefixes as $prefix) { ?>
      <th class="first rotate"><div><span>Min</span></div></th>
      <th class="rotate"><div><span>Max</span></div></th>
      <th class="rotate"><div><span>Range</span></div></th>
      <th class="rotate"><div><span>Median</span></div></th>
      <th class="rotate"><div><span>Mean</span></div></th>
      <th class="rotate"><div><span>St.&nbsp;dev.</span></div></th>
    <?php } ?>
  </tr>
  </thead>
  <tbody>
  <?php
  $counter = 1;
  foreach ($data->assocStat['generic'] as $metric => $proxies) {
    ?>
    <tr>
      <td><?= $counter++ ?></td>
      <td><a href="#<?= $proxies['provider']->_row ?>"><?= $data->fields[$metric] ?></a></td>
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
