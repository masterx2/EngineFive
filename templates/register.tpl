{extends 'index.tpl'}
{block 'content'}
    {include 'navbar.tpl'}
    <div class="container center">
        <div class="row">
            <div class="col-md-offset-4 col-md-4 col-xs-offset-2 col-xs-8">
                <h3 class="text-center form-header"><span class="label label-info">Registration</span></h3>
                {$form}
            </div>
        </div>
    </div>
{/block}