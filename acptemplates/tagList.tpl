{include file='header'}

<script type="text/javascript">
	//<![CDATA[
	$(function() {
		new WCF.Action.Delete('wcf\\data\\tag\\TagAction', $('.jsTagRow'));
	});
	//]]>
</script>

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.acp.tag.list{/lang}</h1>
	</hgroup>
</header>

<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller="TaglList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&search=$search"}
	
	{if $__wcf->session->getPermission('admin.content.tag.canAddTag')}
		<nav>
			<ul>
				<li><a href="{link controller='TagAdd'}{/link}" title="{lang}wcf.acp.tag.add{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/add.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.tag.add{/lang}</span></a></li>
			</ul>
		</nav>
	{/if}
</div>

{hascontent}
	<div class="tabularBox tabularBoxTitle marginTop shadow">
		<hgroup>
			<h1>{lang}wcf.acp.tag.list{/lang} <span class="badge badgeInverse" title="{lang}wcf.acp.tag.list.count{/lang}">{#$items}</span></h1>
		</hgroup>
		
		<table class="table">
			<thead>
				<tr>
					<th class="columnID columnTagID{if $sortField == 'tagID'} active{/if}" colspan="2"><a href="{link controller='TagList'}pageNo={@$pageNo}&sortField=tagID&sortOrder={if $sortField == 'tagID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&search={@$search|rawurlencode}{/link}">{lang}wcf.global.objectID{/lang}{if $sortField == 'tagID'} <img src="{@$__wcf->getPath()}icon/sort{@$sortOrder}.svg" alt="" />{/if}</a></th>
					<th class="columnTitle columnName{if $sortField == 'name'} active{/if}"><a href="{link controller='TagList'}pageNo={@$pageNo}&sortField=name&sortOrder={if $sortField == 'name' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&search={@$search|rawurlencode}{/link}">{lang}wcf.acp.tag.name{/lang}{if $sortField == 'name'} <img src="{@$__wcf->getPath()}icon/sort{@$sortOrder}.svg" alt="" />{/if}</a></th>
					<th class="columnNumber columnUsageCount{if $sortField == 'usageCount'} active{/if}"><a href="{link controller='TagList'}pageNo={@$pageNo}&sortField=usageCount&sortOrder={if $sortField == 'usageCount' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&search={@$search|rawurlencode}{/link}">{lang}wcf.acp.tag.usageCount{/lang}{if $sortField == 'usageCount'} <img src="{@$__wcf->getPath()}icon/sort{@$sortOrder}.svg" alt="" />{/if}</a></th>
					<th class="columnText columnLanguage{if $sortField == 'language'} active{/if}"><a href="{link controller='TagList'}pageNo={@$pageNo}&sortField=language&sortOrder={if $sortField == 'language' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&search={@$search|rawurlencode}{/link}">{lang}wcf.acp.tag.language{/lang}{if $sortField == 'language'} <img src="{@$__wcf->getPath()}icon/sort{@$sortOrder}.svg" alt="" />{/if}</a></th>
					
					{event name='headColumns'}
				</tr>
			</thead>
			
			<tbody>
				{content}
					{foreach from=$objects item=tag}
						<tr class="jsTagRow">
							<td class="columnIcon">
								{if $__wcf->session->getPermission('admin.content.tag.canEditTag')}
									<a href="{link controller='TagEdit' id=$tag->tagID}{/link}"><img src="{@$__wcf->getPath()}icon/edit.svg" alt="" title="{lang}wcf.global.button.edit{/lang}" class="icon16 jsTooltip" /></a>
								{else}
									<img src="{@$__wcf->getPath()}icon/edit.svg" alt="" title="{lang}wcf.global.button.edit{/lang}" class="icon16 disabled" />
								{/if}
								{if $__wcf->session->getPermission('admin.content.tag.canDeleteTag')}
									<img src="{@$__wcf->getPath()}icon/delete.svg" alt="" title="{lang}wcf.global.button.delete{/lang}" class="icon16 jsDeleteButton jsTooltip" data-object-id="{@$tag->tagID}" data-confirm-message="{lang}wcf.acp.tag.delete.sure{/lang}" />
								{else}
									<img src="{@$__wcf->getPath()}icon/delete.svg" alt="" title="{lang}wcf.global.button.delete{/lang}" class="icon16 disabled" />
								{/if}
								
								{event name='buttons'}
							</td>
							<td class="columnID"><p>{#$tag->tagID}</p></td>
							<td class="columnTitle columnName"><p>{if $__wcf->session->getPermission('admin.content.tag.canEditTag')}<a href="{link controller='TagEdit' id=$tag->tagID}{/link}" title="{$tag->name}" class="badge">{$tag->name}</a>{else}<span class="badge">{$tag->name}</span>{/if}</p></td>
							<td class="columnNumber columnUsageCount"><p>{#$tag->usageCount}</p></td>
							<td class="columnText columnLanguage"><p>{if $tag->languageName !== null}{$tag->languageName} ({$tag->languageCode}){/if}</p></td>
							
							{event name='columns'}
						</tr>
					{/foreach}
				{/content}
			</tbody>
		</table>
		
	</div>
	
	<div class="contentNavigation">
		{@$pagesLinks}
		
		{if $__wcf->session->getPermission('admin.content.tag.canAddTag')}
			<nav>
				<ul>
					<li><a href="{link controller='TagAdd'}{/link}" title="{lang}wcf.acp.tag.add{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/add.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.tag.add{/lang}</span></a></li>
				</ul>
			</nav>
		{/if}
	</div>
{hascontentelse}
	<p class="info">{lang}wcf.acp.tag.noneAvailable{/lang}</p>
{/hascontent}

{include file='footer'}
