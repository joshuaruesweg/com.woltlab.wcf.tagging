{hascontent}
	<ul class="tagCloud">
		{content}
			{foreach from=$tags item=tag}
				<li style="display: inline"><a href="{link controller='TaggedObjects' object=$tag}{/link}" style="font-size: {@$tag->getSize()}%;">{$tag->name}</a></li>
			{/foreach}
		{/content}
	</ul>
{/hascontent}