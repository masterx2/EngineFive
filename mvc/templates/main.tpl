{extends 'index.tpl'}
{block 'title'}Post feed{/block}
{block 'content'}
<div class="col-md-10">
    {if isset($projects)}
    <div class="row projects">
        {set $records = $projects[0]}
        {foreach $records as $record}
        <div class="col-md-4">
        <div class="project">
                <div class="project-header">
                    <div class="title"><a href="http://{$.server['HTTP_HOST']}/post/view/{$record.id|urlencode}">{$record.title}</a></div>
                    <div class="section"><a href="http://{$.server['HTTP_HOST']}/blog/section/{$record.section|urlencode}">{$record.section}</a></div>
                </div>
                <div class="project-content">
                    <div class="preambula">{$record.preambula|truncate:200:"..."}</div>
                    {* <div class="body">{$record.body}</div> *}
                </div>
            </div>
        </div>
        {/foreach}
    </div>
    {/if}
    <div class="row">
    {if isset($data)}
        {set $records = $data[0]}
        {foreach $records as $record}
            <div class="post">
                <div class="post-header">
                    <div class="title"><a href="http://{$.server['HTTP_HOST']}/post/view/{$record.id|urlencode}">{$record.title}</a></div>
                    <div class="date">{$record.created}</div>
                    <div class="section"><a href="http://{$.server['HTTP_HOST']}/blog/section/{$record.section|urlencode}">{$record.section}</a></div>
                    <div class="author"><a href="http://{$.server['HTTP_HOST']}/blog/author/{$record.author|urlencode}"><small>by</small> {$record.author}</a></div>
                </div>
                <div class="post-content">
                    <div class="preambula">{$record.preambula}</div>
                    {* <div class="body">{$record.body}</div> *}
                </div>
                <div class="post-footer">
                    <div class="tags">
                        {if $record.tags}
                            {foreach $record.tags as $tag}
                                <span class="tag"><a href="http://{$.server['HTTP_HOST']}/blog/tags/{$tag|urlencode}">{$tag}</a></span>
                            {/foreach}
                        {else}
                            no tags
                        {/if}
                    </div>
                    <div class="comments"><a href="http://{$.server['HTTP_HOST']}/post/view/{$record.id}#comments"><small>Comments</small> {$record.comments|count}</a></div>
                </div>
            </div>
        {/foreach}
    {else}
        <h1>No records</h1>
    {/if}
    </div>
</div>
<div class="col-md-2">
    <h4 class="block-title">Tags</h4>
    {if isset($tags)}
        <ul class="tag-review">
        {foreach $tags as $tag}
        <li data-count="{$tag.count}"><a href="http://{$.server['HTTP_HOST']}/blog/tags/{$tag._id|urlencode}">{$tag._id}</a></li>
        {/foreach}
        </ul>
    {else}
        no data
    {/if}
</div>
{/block}