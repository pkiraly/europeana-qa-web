  {foreach $intersections->list as $type => $items}
    <div class="row intersections-{$type}">
      <legend>{$intersectionLabels[$type]} ({$items->count}):</legend>
      {for $j=0 to 2}
        <div class="col-lg-4">
          {for $i=$j to $items->count-1 step 3}
            {assign var=item value=$items->items[$i]}
            <label>
              <input type="radio" name="intersection" value="{$item->file}"
                    {if isset($intersection) && $item->file == $intersection} checked="checked"{/if}/>
            {$item->name} ({$item->count|number_format:0:'.':' '})
            </label><br/>
          {/for}
        </div>
      {/for}
    </div>
  {/foreach}
