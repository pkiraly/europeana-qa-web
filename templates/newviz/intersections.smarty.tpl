{assign var=intersectioCounter value=0}
{if ($intersections != null && isset($intersections->list))}
  {foreach $intersections->list as $type => $items}
    {assign var=intersectioCounter value=($intersectioCounter + 1)}
    <div class="row intersections-{$type}">
      <legend>{$intersectionLabels[$type]} ({$items->count}):</legend>
      {for $j=0 to 2}
        <div class="col-lg-4">
          {for $i=$j to $items->count-1 step 3}
            {assign var=item value=$items->items[$i]}
            <label>
              <input type="radio" name="intersection-{$intersectioCounter}" value="{$item->file}"
                   data-type="{$type}" data-id="{$item->id}" data-intersection="{$item->file}"
                   data-count="{$item->count|number_format:0:'.':' '}"
                  {if isset($intersection) && $item->file == $intersection} checked="checked"{/if}/>
              {$item->name} (<span class="count">{$item->count|number_format:0:'.':' '}</span>)
            </label><br/>
          {/for}
        </div>
      {/for}
    </div>
  {/foreach}
{/if}
