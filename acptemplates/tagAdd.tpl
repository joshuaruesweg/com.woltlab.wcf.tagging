{include file='header'}

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

<form method="post" action="{if $action == 'add'}{link controller='TagAdd'}{/link}{else}{link controller='TagEdit'}{/link}{/if}">
	<div class="container containerPadding marginTop shadow">
		<fieldset>
			<legend>{lang}wcf.acp.tag.data{/lang}</legend>
			
			<dl{if $errorField == 'name'} class="formError"{/if}>
				<dt><label for="name">{lang}wcf.acp.tag.name{/lang}</label></dt>
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
			
			<dl{if $errorField == 'language' || $action == 'edit'} class="{if $action == 'edit'}disabled{else}formError{/if}"{/if}>
				<dt><label for="language">{lang}wcf.acp.tag.language{/lang}</label></dt>
				<dd>
					<select id="language" name="language"{if $action == 'edit'} disabled="disabled"{/if}>
						<option value="0"{if $languageID == 0} selected="selected"{/if}>&nbsp;</option>
						{foreach from=$languages item='language'}
							<option value="{@$language->languageID}"{if $languageID == $language->languageID} selected="selected"{/if}>{$language->languageName} ({$language->languageCode})</option>
						{/foreach}
					</select>
					{if $errorField == 'language'}
						<small class="innerError">
							{if $errorType == 'notFound'}
								{lang}wcf.acp.tag.error.language.duplicate{/lang}
							{/if}
						</small>
					{/if}
				</dd>
			</dl>
		</fieldset>
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		{if $tagID|isset}<input type="hidden" name="id" value="{@$tagID}" />{/if}
	</div>
</form>

{include file='footer'}