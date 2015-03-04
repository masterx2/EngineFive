{extends 'index.tpl'}
{block 'title'}Post View{/block}
{block 'content'}
<div class="col-md-12">
{if isset($record)}
    <div class="row">
        <div class="post">
            <div class="post-header">
                <div class="title"><a href="http://{$.server['HTTP_HOST']}/post/view/{$record.id}">{$record.title}</a></div>
                <div class="date">{$record.created}</div>
                <div class="section"><a href="http://{$.server['HTTP_HOST']}/blog/section/{$record.section}">{$record.section}</a></div>
                <div class="author"><a href="http://{$.server['HTTP_HOST']}/blog/author/{$record.author}"><small>by</small> {$record.author}</a></div>
            </div>
            <div class="post-content">
                <div class="preambula">{$record.preambula}</div>
                <div class="body">{$record.body}</div>
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
            </div>
        </div>
    </div>
    <div class="row" id="comments">
        {$comment_form}
        {$comments}
    </div>
{else}
    <h1>Wrong Id</h1>
{/if}
</div>
{/block}