<form class="form-horizontal" method="post">
{foreach $schema as $field => $params}
    {if isset($params['control_type'])}
        {if $params['control_type'] == 'hidden'}
            <input type="hidden" name="{$field}" id="{$field}" {if isset($data[$field])}value="{$data[$field]}"{/if}>
        {else}
            <div class="form-group">
                <label for="{$field}" class="col-sm-2 control-label">{$field|ucfirst}</label>
                <div class="col-sm-8">
                    {switch $params['control_type']}
                    {case 'input'}
                        <input type="input" class="form-control input-sm" name="{$field}" id="{$field}" {if isset($data[$field])}value="{$data[$field]}"{/if}>
                    {case 'textarea'}
                        <textarea name="{$field}" id="{$field}" class="form-control" rows="3">{if isset($data[$field])}{$data[$field]}{/if}</textarea>
                    {/switch}
                </div>
            </div>
        {/if}
    {/if}
{/foreach}
    <div class="col-sm-8 col-sm-offset-2">
        <input class="btn btn-default pull-right" type="submit" value="Submit">
    </div>
</form>