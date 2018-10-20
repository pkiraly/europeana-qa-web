<h3 class="entity-name">{$entity}</h3>
<p>number of records: {$statistics->entityCount|number_format:0:'.':','}</p>
<p>version: {$version}</p>

{foreach $fields as $field}
  <div class="row">
    <div class="col-lg-5 newviz">
      {strip}
      <h3{if ($field->isMandatory)} class="mandatory-{$field->mandatory}"{/if}>
        {if ($field->isMandatory)}<i class="fa fa-{$field->mandatoryIcon} mandatory-icon" aria-hidden="true"></i>{/if}
        {$field->label}
         &nbsp;<a class="qa-show-details {$field->key}" href="#"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
      </h3>
      {/strip}
    </div>
    <div class="col-lg-6">
      <div class="chart">
        <div style="width: {$field->width}px;">{$field->nonZeros|number_format:0:'.':','}</div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-1 col-md-1 col-lg-1"></div>
    <div class="col-sm-11 col-md-11 col-lg-11">
      <div class="qa-details field-details" id="details-{$field->key}">
        {if $field->hasFrequency}
          {$field->freqHtml}
        {/if}

        {if $field->hasCardinality}
          {$field->cardinalityHtml}
        {/if}

        {if $field->hasHistograms}
          {$field->histogramHtml}
        {/if}

        {if $field->hasMinMaxRecords}
          <ul>
            <li><a href="record.php?id={$field->recMax}&version={$version}">Best Record</a></li>
            <li><a href="record.php?id={$field->recMin}&version={$version}">Worst Record</a></li>
          </ul>
        {/if}

        {if ($development)}
          <div class="most-frequent-values">
            <a href="#" class="most-frequent-values {$field->key}">Show the most frequent values</a>
            <div id="most-frequent-values-{$field->key}"></div>
          </div>
        {/if}

        {strip}
        Frequency compared to <select name="comparision-selector" id="{$field->key}-comparision-selector">
        <option>--select a field--</option>
        {foreach $fields as $otherField}
          {if $otherField->key != $field->key}
            <option value="{$otherField->key}">{$otherField->label}</option>
          {/if}
        {/foreach}
        </select>
        {/strip}
        <div id="{$field->key}-comparision-container"></div>
      </div>
    </div>
  </div>
{/foreach}