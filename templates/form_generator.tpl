<form class="form-horizontal" method="post">
{foreach $schema as $field => $params}
    {if isset($params.control_type) && array_search($scenario, $params.scenario) > -1}
        {if $params.control_type == 'hidden'}
            <input type="hidden" name="{$field}" id="{$field}" {if isset($data[$field])}value="{$data[$field]}"{/if}>
        {else}
            <div class="form-group">
                {if isset($params.label)}
                    <label for="{$field}">{$params.label}</label>
                {/if}
                {switch $params.control_type}
                {case 'input'}
                    <input type="input" class="form-control input-sm" name="{$field}" id="{$field}" {if isset($data[$field])}value="{$data[$field]}"{/if} placeholder="{$field|ucfirst}">
                {case 'password'}
                    <input type="password" class="form-control input-sm" name="{$field}" id="{$field}" {if isset($data[$field])}value="{$data[$field]}"{/if} placeholder="{$field|ucfirst}">
                {case 'textarea'}
                    <textarea name="{$field}" id="{$field}" class="form-control" rows="3">{if isset($data[$field])}{$data[$field]}{/if}</textarea>
                {/switch}
            </div>
        {/if}
    {/if}
{/foreach}
    <div class="text-center">
        <input class="btn btn-default" type="submit" value="{$scenario|ucfirst}">
    </div>
</form>