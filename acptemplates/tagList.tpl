{include file='header' pageTitle='wcf.acp.tag.list'}

<script type="text/javascript">
	//<![CDATA[
	$(function() {
		new WCF.Action.Delete('wcf\\data\\tag\\TagAction', '.jsTagRow');
	});
	//]]>
</script>

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.acp.tag.list{/lang}</h1>
	</hgroup>
</header>

{if $items}
	<form action="{link controller='TagList'}{/link}">
		<div class="container containerPadding marginTop">
			<fieldset><legend>{lang}wcf.acp.tag.list.search{/lang}</legend>
				<dl>
					<dt><label for="search">{lang}wcf.acp.tag.list.search.query{/lang}</label></dt>
					<dd>
						<input type="search" id="search" name="search" value="{$search}" required="required" autofocus="autofocus" class="medium" />
					</dd>
				</dl>
			</fieldset>

			<div class="formSubmit">
				<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
				{@SID_INPUT_TAG}
			</div>
		</div>
	</form>
{/if}

<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller="TaglList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&search=$search"}
	
	{hascontent}
		<nav>
			<ul>
				{content}
					{if $__wcf->session->getPermission('admin.content.tag.canAddTag')}
						<li><a href="{link controller='TagAdd'}{/link}" title="{lang}wcf.acp.tag.add{/lang}" class="button"><span class="icon icon16 icon-plus"></span> <span>{lang}wcf.acp.tag.add{/lang}</span></a></li>
					{/if}
					
					{event name='contentNavigationButtonsTop'}
				{/content}
			</ul>
		</nav>
	{/hascontent}
</div>

{if $objects|count}
	<div class="tabularBox tabularBoxTitle marginTop">
		<hgroup>
			<h1>{lang}wcf.acp.tag.list{/lang} <span class="badge badgeInverse" title="{lang}wcf.acp.tag.list.count{/lang}">{#$items}</span></h1>
		</hgroup>
		
		<table class="table">
			<thead>
				<tr>
					<th class="columnID columnTagID{if $sortField == 'tagID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link controller='TagList'}pageNo={@$pageNo}&sortField=tagID&sortOrder={if $sortField == 'tagID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&search={@$search|rawurlencode}{/link}">{lang}wcf.global.objectID{/lang}</a></th>
					<th class="columnTitle columnName{if $sortField == 'name'} active {@$sortOrder}{/if}"><a href="{link controller='TagList'}pageNo={@$pageNo}&sortField=name&sortOrder={if $sortField == 'name' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&search={@$search|rawurlencode}{/link}">{lang}wcf.acp.tag.name{/lang}</a></th>
					<th class="columnNumber columnUsageCount{if $sortField == 'usageCount'} active {@$sortOrder}{/if}"><a href="{link controller='TagList'}pageNo={@$pageNo}&sortField=usageCount&sortOrder={if $sortField == 'usageCount' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&search={@$search|rawurlencode}{/link}">{lang}wcf.acp.tag.usageCount{/lang}</a></th>
					<th class="columnText columnLanguage{if $sortField == 'language'} active {@$sortOrder}{/if}"><a href="{link controller='TagList'}pageNo={@$pageNo}&sortField=language&sortOrder={if $sortField == 'language' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&search={@$search|rawurlencode}{/link}">{lang}wcf.acp.tag.languageID{/lang}</a></th>
					<th class="columnText columnSynonymFor">{lang}wcf.acp.tag.synonymFor{/lang}</th>
					
					{event name='headColumns'}
				</tr>
			</thead>
			
			<tbody>
				{foreach from=$objects item=tag}
					<tr class="jsTagRow">
						<td class="columnIcon">
							{if $__wcf->session->getPermission('admin.content.tag.canEditTag')}
								<a href="{link controller='TagEdit' id=$tag->tagID}{/link}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip"><span class="icon icon16 icon-pencil"></span></a>
							{/if}
							{if $__wcf->session->getPermission('admin.content.tag.canDeleteTag')}
								<span class="icon icon16 icon-remove jsDeleteButton jsTooltip pointer" title="{lang}wcf.global.button.delete{/lang}" data-object-id="{@$tag->tagID}" data-confirm-message="{lang}wcf.acp.tag.delete.sure{/lang}"></span>
							{/if}
							
							{event name='buttons'}
						</td>
						<td class="columnID"><p>{#$tag->tagID}</p></td>
						<td class="columnTitle columnName"><p>{if $__wcf->session->getPermission('admin.content.tag.canEditTag')}<a href="{link controller='TagEdit' id=$tag->tagID}{/link}" class="badge">{$tag->name}</a>{else}<span class="badge">{$tag->name}</span>{/if}</p></td>
						<td class="columnNumber columnUsageCount"><p>{if $tag->synonymFor === null}{#$tag->usageCount}{/if}</p></td>
						<td class="columnText columnLanguage"><p>{if $tag->languageName !== null}{$tag->languageName} ({$tag->languageCode}){/if}</p></td>
						<td class="columnText columnSynonymFor"><p>{if $tag->synonymFor !== null}<a href="{link controller='TagList'}search={@$tag->synonymName|rawurlencode}{/link}" class="badge">{$tag->synonymName}</a>{/if}</p></td>
						
						{event name='columns'}
					</tr>
				{/foreach}
			</tbody>
		</table>
		
	</div>
	
	<div class="contentNavigation">
		{@$pagesLinks}
		
		{hascontent}
			<nav>
				<ul>
					{content}
						{if $__wcf->session->getPermission('admin.content.tag.canAddTag')}
							<li><a href="{link controller='TagAdd'}{/link}" title="{lang}wcf.acp.tag.add{/lang}" class="button"><span class="icon icon16 icon-plus"></span> <span>{lang}wcf.acp.tag.add{/lang}</span></a></li>
						{/if}
						
						{event name='contentNavigationButtonsBottom'}
					{/content}
				</ul>
			</nav>
		{/hascontent}
	</div>
{else}
	<p class="info">{lang}wcf.acp.tag.noneAvailable{/lang}</p>
{/if}

{include file='footer'}
