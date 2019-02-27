<h3 class="entity-name">{$entity}</h3>
{if $development}
  <p>
    <em>number of records</em>:
    {if $entity != 'ProvidedCHO'}linked from{/if} original metadata:
    {$statistics->entityCount['provider']|number_format:0:'.':','},
    {if $entity != 'ProvidedCHO'}linked from{/if} Europeana enrichment:
    {$statistics->entityCount['europeana']|number_format:0:'.':','}
  </p>
{else}
  <p>number of records: {$statistics->entityCount|number_format:0:'.':','}</p>
{/if}
<p>version: {$version}</p>

<div class="row">
  <div class="col-lg-5"><h4>field</h4></div>
  <div class="col-lg-3"><h4>original metadata</h4></div>
  <div class="col-lg-4"><h4>Europeana enrichment</h4></div>
</div>

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
    <div class="col-lg-3">
      <div class="chart-half">
        <div style="width: {$field->width->provider}px;">{$field->nonZeros->provider|number_format:0:'.':','}</div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="chart-half">
        <div style="width: {$field->width->europeana}px;">{$field->nonZeros->europeana|number_format:0:'.':','}</div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
      <div class="qa-details field-details" id="details-{$field->key}">
        <div class="row">
          <div class="col-md-6 col-lg-6">
            <h4>original metadata</h4>
          </div>
          <div class="col-md-6 col-lg-6">
            <h4>Europeana enrichment</h4>
          </div>
        </div>

        {if $field->hasFrequency}
          <h4>Number of records where the fields are present</h4>
          <div class="row">
            <div class="col-md-6 col-lg-6">
              {$field->freqHtml->provider}
            </div>
            <div class="col-md-6 col-lg-6">
              {$field->freqHtml->europeana}
            </div>
          </div>
        {/if}

        {if $field->hasCardinality}
          <h4>Statistics for the number of field occurrences</h4>
          <div class="row">
            <div class="col-md-6 col-lg-6">
              {$field->cardinalityHtml->provider}
            </div>
            <div class="col-md-6 col-lg-6">
              {$field->cardinalityHtml->europeana}
            </div>
          </div>
        {/if}

        {if $field->hasHistograms}
          <h4>Number of field occurrences in individual records</h4>


          <div class="row">
            <h5>original metadata</h5>
            {$field->histogramHtml->provider}
          </div>
          <div class="row">
            <h5>Europeana enrichment</h5>
            {$field->histogramHtml->europeana}
          </div>
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
            <p><em><strong>Warning!</strong> Due to the way values are stored in Europeana's search index, the values here can be split in individual words and the feature may not work for some fields.</em></p>
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