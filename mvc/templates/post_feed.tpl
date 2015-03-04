{extends 'index.tpl'}
{block 'title'}Post feed{/block}
{block 'content'}
<div class="col-md-10">
{if isset($data)}
    {set $records = $data[0]}
    {foreach $records as $record}
        <div class="post">
            <div class="post-header">
                <div class="title"><a href="http://{$.server['HTTP_HOST']}/post/view/{$record.id}">{$record.title}</a></div>
                <div class="date">{$record.created}</div>
                <div class="section"><a href="http://{$.server['HTTP_HOST']}/blog/section/{$record.section}">{$record.section}</a></div>
                <div class="author"><a href="http://{$.server['HTTP_HOST']}/blog/author/{$record.author}"><small>by</small> {$record.author}</a></div>
            </div>
            <div class="post-content">
                <div class="preambula">{$record.preambula}</div>
                {* <div class="body">{$record.body}</div> *}
            </div>
            <div class="post-footer">
                <div class="tags">
                    {if $record.tags}
                        {foreach $record.tags as $tag}
                            <span class="tag"><a href="http://{$.server['HTTP_HOST']}/blog/tags/{$tag}">{$tag}</a></span>
                        {/foreach}
                    {else}
                        no tags
                    {/if}
                </div>
                <div class="comments"><a href="http://{$.server['HTTP_HOST']}/post/view/{$record.id}#comments"><small>Comments</small> {$record.comments|count}</a></div>
            </div>
        </div>
    {/foreach}

    {set $current_count = count($records)}
    {set $total_count = $data[1]}
    {set $current_page = $.get['page'] ?: 1}
    {if $total_count > $current_count}
    {set $pages = floor($total_count / 5) + 1}
    <nav>
        <ul class="pagination">
            {if $current_page-1 != 0}
            <li>
                <a href="?page={$current_page-1}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            {/if}
            {for $counter=1 to=$pages}
            <li {if $counter == $current_page}class="active"{/if}><a href="?page={$counter}">{$counter}</a></li>
            {/for}
            {if $current_page+1 < $pages}
            <li>
                <a href="?page={$current_page+1}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            {/if}
        </ul>
    </nav>
    {/if}

{else}
    <h1>No records</h1>
{/if}
</div>
<div class="col-md-2">
    <h4 class="block-title">Tags</h4>
    {if isset($tags)}
        <ul class="tag-review">
        {foreach $tags as $tag}
        <li data-count="{$tag.count}"><a href="http://{$.server['HTTP_HOST']}/blog/tags/{$tag._id}">{$tag._id}</a></li>
        {/foreach}
        </ul>
    {else}
        no data
    {/if}
</div>
{/block}