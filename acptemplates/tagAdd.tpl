{include file='header' pageTitle='wcf.acp.tag.'|concat:$action}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.acp.tag.{$action}{/lang}</h1>
	</hgroup>
</header>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wcf.global.form.{$action}.success{/lang}</p>	
{/if}

<div class="contentNavigation">
	<nav>
		<ul>
			<li><a href="{link controller='TagList'}{/link}" title="{lang}wcf.acp.menu.link.tag.list{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/list.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.menu.link.tag.list{/lang}</span></a></li>
		</ul>
	</nav>
</div>

<form method="post" action="{if $action == 'add'}{link controller='TagAdd'}{/link}{else}{link controller='TagEdit' id=$tagObj->tagID}{/link}{/if}">
	<div class="container containerPadding marginTop shadow">
		<fieldset>
			<legend>{lang}wcf.global.form.data{/lang}</legend>
			
			<dl{if $errorField == 'name'} class="formError"{/if}>
				<dt><label for="name">{lang}wcf.global.name{/lang}</label></dt>
				<dd>
					<input type="text" id="name" name="name" value="{$name}" required="required" autofocus="autofocus" class="medium" />
					{if $errorField == 'name'}
						<small class="innerError">
							{if $errorType == 'empty'}
								{lang}wcf.global.form.error.empty{/lang}
							{elseif $errorType == 'duplicate'}
								{lang}wcf.acp.tag.error.name.duplicate{/lang}
							{/if}
						</small>
					{/if}
				</dd>
			</dl>
			
			{hascontent}
				<dl{if $errorField == 'languageID' || $action == 'edit'} class="{if $action == 'edit'}disabled{else}formError{/if}"{/if}>
					<dt><label for="languageID">{lang}wcf.acp.tag.language{/lang}</label></dt>
					<dd>
						<select id="languageID" name="languageID"{if $action == 'edit'} disabled="disabled"{/if}>
							{content}
								{foreach from=$availableLanguages item=language}
									<option value="{@$language->languageID}"{if $languageID == $language->languageID} selected="selected"{/if}>{$language->languageName} ({$language->languageCode})</option>
								{/foreach}
							{/content}
						</select>
						{if $errorField == 'languageID'}
							<small class="innerError">
								{if $errorType == 'notFound'}
									{lang}wcf.acp.tag.error.language.duplicate{/lang}
								{/if}
							</small>
						{/if}
					</dd>
				</dl>
			{/hascontent}
			
			{if !$tagObj|isset || $tagObj->synonymFor === null}
				<dl>
					<dt><label for="synonyms">{lang}wcf.acp.tag.synonyms{/lang}</label></dt>
					<dd id="synonymList" class="editableItemList"></dd>
					<dd>
						<input id="synonyms" type="text" value="" class="long" />
						{if $errorField == 'synonyms'}
							<small class="innerError">
								{if $errorType == 'duplicate'}
									{lang}wcf.acp.tag.error.synonym.duplicate{/lang}
								{/if}
							</small>
						{/if}
					</dd>
				</dl>
				
				<script type="text/javascript" src="{@$__wcf->getPath()}js/WCF.Tagging.js"></script>
				<script type="text/javascript">
					//<![CDATA[
					$(function() {
						var $tagList = new WCF.Tagging.TagList('#synonymList', '#synonyms');
						
						{if $synonyms|isset && $synonyms|count}
							$tagList.load([ {implode from=$synonyms item='synonym'}'{$synonym}'{/implode} ]);
						{/if}
					});
					//]]>
				</script>
			{elseif $tagObj|isset}
				<dl>
					<dt><label for="synonyms">{lang}wcf.acp.tag.synonyms{/lang}</label></dt>
					<dd>
						<a href="{link controller='TagEdit' id=$tagObj->synonymFor}{/link}">{lang}wcf.acp.tag.synonyms.isSynonym{/lang}</a>
					</dd>
				</dl>
			{/if}
		</fieldset>
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
	</div>
</form>

{include file='footer'}