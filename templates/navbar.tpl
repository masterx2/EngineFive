<nav class="navbar navbar-custom">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">
                <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
            </a>
        </div>
        {if isset($auth.accounts)}
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Account <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
					{foreach $auth.accounts as $id => $account}
						<li {if $account.active}class="active"{/if}><a href="#">{$account.name}</a></li>
					{/foreach}
                </ul>
            </li>
        </ul>
        {/if}
    </div>
</nav>