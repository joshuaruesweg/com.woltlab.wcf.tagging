<dl>
	<dt><label for="tagSearchInput{if $tagInputSuffix|isset}{@$tagInputSuffix}{/if}">{lang}wcf.tagging.tags{/lang}</label></dt>
	<dd id="tagList{if $tagInputSuffix|isset}{@$tagInputSuffix}{/if}"></dd>
	<dd>
		<input id="tagSearchInput{if $tagInputSuffix|isset}{@$tagInputSuffix}{/if}" type="text" value="" class="long" />
		<small>{lang}wcf.tagging.tags.description{/lang}</small>
	</dd>
</dl>
<script type="text/javascript" src="{@$__wcf->getPath()}js/WCF.Tagging.js"></script>
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		new WCF.Tagging.TagList('#tagList{if $tagInputSuffix|isset}{@$tagInputSuffix}{/if}', '#tagSearchInput{if $tagInputSuffix|isset}{@$tagInputSuffix}{/if}');
	});
	//]]>
</script>