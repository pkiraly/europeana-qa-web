<p>
  What fields make typical records? This table shows you the field groups found in records.
  The table is ordered by the number of records, so the more typical records at the top.
  Each line contains which fields are available in the record, and how many records has this pattern.
  The red line shows the limit between the patterns occur in more than 1% of the records, and the patterns which occur less.
</p>
<table id="field-patterns">
  <thead>
    <tr>
<?php foreach ($data->fields as $key => $field) { ?>
      <th title="<?= $field ?>"><?= $field ?></th>
<?php } ?>
      <th title="the number of fields in the record">#fields</th>
      <th title="the number of records it occures in">occurence</th>
      <th>percent</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($data->profiles as $row) { ?>
    <tr<?php if ($row['drawLine']) { ?> class="under-one"<?php } ?>>
    <?php foreach ($data->fields as $field) { ?>
      <td align="center"<?php if (in_array($field, $row['profileFields'])) { ?> class="filled"<?php } ?> title="<?= $field ?>">
      </td>
    <?php } ?>
      <td align="right"><?= $row['nr'] ?></td>
      <td align="right"><?= $row['occurence'] ?></td>
      <td align="right"><?= sprintf("%.02f%%", ($row['percent'] * 100)); ?></td>
    </tr>
<?php } ?>
  </tbody>
</table>
