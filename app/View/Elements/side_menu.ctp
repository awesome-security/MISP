<div class="actions <?php echo $debugMode;?> sideMenu">
	<ul class="nav nav-list">
		<?php
			switch ($menuList) {
				case 'event':
					if ($menuItem === 'addAttribute' ||
						$menuItem === 'addAttachment' ||
						$menuItem === 'addIOC' ||
						$menuItem === 'addThreatConnect' ||
						$menuItem === 'populateFromtemplate'
					) {
						// we can safely assume that mayModify is true if comming from these actions, as they require it in the controller and the user has already passed that check
						$mayModify = true;
						if ($isAclPublish) $mayPublish = true;
					}
					if (($menuItem === 'template_populate_results')):
					?>
						<li id='litemplate_populate_results'><a href="<?php echo $baseurl;?>/templates/index">Populate From Template</a></li>
					<?php
						endif;
					?>
					<?php if ($menuItem === 'freetextResults'): ?>
					<li id='lifreetextResults'><a href="#">Freetext Import Results</a></li>
					<li class="divider"></li>
					<?php endif;?>
					<li id='liviewEvent'><a href="<?php echo $baseurl;?>/events/view/<?php echo h($event['Event']['id']);?>">View Event</a></li>
					<li id='liviewEventGraph'><a href="<?php echo $baseurl;?>/events/viewGraph/<?php echo h($event['Event']['id']);?>">View Correlation Graph</a></li>
					<li id='lieventLog'><a href="<?php echo $baseurl;?>/logs/event_index/<?php echo h($event['Event']['id']);?>">View Event History</a></li>
					<li class="divider"></li>
					<?php if ($isSiteAdmin || (isset($mayModify) && $mayModify)): ?>
					<li id='lieditEvent'><a href="<?php echo $baseurl;?>/events/edit/<?php echo h($event['Event']['id']);?>">Edit Event</a></li>
					<li><?php echo $this->Form->postLink('Delete Event', array('action' => 'delete', h($event['Event']['id'])), null, __('Are you sure you want to delete # %s?', h($event['Event']['id']))); ?></li>
					<li id='liaddAttribute'><a href="<?php echo $baseurl;?>/attributes/add/<?php echo h($event['Event']['id']);?>">Add Attribute</a></li>
					<li id='liaddAttachment'><a href="<?php echo $baseurl;?>/attributes/add_attachment/<?php echo h($event['Event']['id']);?>">Add Attachment</a></li>
					<li id='liaddFreetext'><a href="#" onClick="getPopup(<?php echo $event['Event']['id']; ?>, 'events', 'freeTextImport');">Populate via Freetext Import</a></li>
					<li id='liaddIOC'><a href="<?php echo $baseurl;?>/events/addIOC/<?php echo h($event['Event']['id']);?>">Populate from OpenIOC</a></li>
					<li id='liaddThreatConnect'><a href="<?php echo $baseurl;?>/attributes/add_threatconnect/<?php echo h($event['Event']['id']); ?>">Populate from ThreatConnect</a></li>
					<?php if ($menuItem === 'populateFromtemplate'): ?>
							<li class="active"><a href="<?php echo $baseurl;?>/templates/populateEventFromTemplate/<?php echo $template_id . '/' . h($event['Event']['id']); ?>">Populate From Template</a></li>
						<?php endif; ?>
					<?php endif; ?>
					<?php if (($isSiteAdmin && (!isset($mayModify) || !$mayModify)) || (!isset($mayModify) || !$mayModify)): ?>
					<li id='liproposeAttribute'><a href="<?php echo $baseurl;?>/shadow_attributes/add/<?php echo h($event['Event']['id']);?>">Propose Attribute</a></li>
					<li id='liproposeAttachment'><a href="<?php echo $baseurl;?>/shadow_attributes/add_attachment/<?php echo h($event['Event']['id']);?>">Propose Attachment</a></li>
					<?php endif; ?>
					<li class="divider"></li>
					<?php
						$publishButtons = ' style="display:none;"';
						$exportButtons = ' style="display:none;"';
						if (isset($event['Event']['published']) && 0 == $event['Event']['published'] && ($isAdmin || (isset($mayPublish) && $mayPublish))) $publishButtons = "";
						if (isset($event['Event']['published']) && $event['Event']['published']) $exportButtons = "";
					?>
					<li<?php echo $publishButtons; ?> class="publishButtons"><a href="#" onClick="publishPopup('<?php echo h($event['Event']['id']); ?>', 'alert')">Publish Event</a></li>
					<li<?php echo $publishButtons; ?> class="publishButtons"><a href="#" onClick="publishPopup('<?php echo h($event['Event']['id']); ?>', 'publish')">Publish (no email)</a></li>
					<?php if (Configure::read('MISP.delegation')):?>
						<?php if ((Configure::read('MISP.unpublishedprivate') || (isset($event['Event']['distribution']) && $event['Event']['distribution'] == 0)) && (!isset($delegationRequest) || !$delegationRequest) && ($isSiteAdmin || (isset($isAclDelegate) && $isAclDelegate))): ?>
								<li id='lidelegateEvent'><a href="#" onClick="delegatePopup('<?php echo h($event['Event']['id']); ?>');">Delegate Publishing</a></li>
						<?php endif;?>
						<?php if (isset($delegationRequest) && $delegationRequest && ($isSiteAdmin || ($isAclPublish && ($me['org_id'] == $delegationRequest['EventDelegation']['org_id'] || $me['org_id'] == $delegationRequest['EventDelegation']['requester_org_id'])))): ?>
							<li class="divider"></li>
							<?php if ($isSiteAdmin || ($isAclPublish && ($me['org_id'] == $delegationRequest['EventDelegation']['org_id']))): ?>
								<li id='liacceptDelegation'><a href="#" onClick="genericPopup('<?php echo $baseurl?>/event_delegations/acceptDelegation/<?php echo h($delegationRequest['EventDelegation']['id']); ?>', '#confirmation_box');">Accept Delegation Request</a></li>
							<?php endif;?>
							<li id='lideleteDelegation'><a href="#" onClick="genericPopup('<?php echo $baseurl?>/event_delegations/deleteDelegation/<?php echo h($delegationRequest['EventDelegation']['id']); ?>', '#confirmation_box');">Discard Delegation Request</a></li>
							<li class="divider"></li>
						<?php endif;?>
					<?php endif;?>
					<li id='licontact'><a href="<?php echo $baseurl;?>/events/contact/<?php echo h($event['Event']['id']);?>">Contact Reporter</a></li>
					<li><a onClick="getPopup('<?php echo h($event['Event']['id']); ?>', 'events', 'exportChoice');" style="cursor:pointer;">Download as...</a></li>
					<li class="divider"></li>
					<li><a href="<?php echo $baseurl;?>/events/index">List Events</a></li>
					<?php if ($isAclAdd): ?>
					<li><a href="<?php echo $baseurl;?>/events/add">Add Event</a></li>
					<?php endif;
				break;

				case 'event-collection': ?>
					<li id='liindex'><a href="<?php echo $baseurl;?>/events/index">List Events</a></li>
					<?php if ($isAclAdd): ?>
					<li id='liadd'><a href="<?php echo $baseurl;?>/events/add">Add Event</a></li>
					<li id='liaddMISPExport'><a href="<?php echo $baseurl;?>/events/add_misp_export">Import From MISP Export</a></li>
					<?php endif; ?>
					<li class="divider"></li>
					<li id='lilistAttributes'><a href="<?php echo $baseurl;?>/attributes/index">List Attributes</a></li>
					<li id='lisearchAttributes'><a href="<?php echo $baseurl;?>/attributes/search">Search Attributes</a></li>
					<?php if ($menuItem == 'searchAttributes2'): ?>
					<li class="divider"></li>
					<li><a href="<?php echo $baseurl;?>/events/downloadSearchResult.json">Download results as JSON</a></li>
					<li><a href="<?php echo $baseurl;?>/events/downloadSearchResult.xml">Download results as XML</a></li>
					<li><a href="<?php echo $baseurl;?>/events/csv/download/search">Download results as CSV</a></li>
					<?php endif; ?>
					<li class="divider"></li>
					<li id='liviewProposals'><a href="<?php echo $baseurl;?>/shadow_attributes/index">View Proposals</a></li>
					<li id='liviewProposalIndex'><a href="<?php echo $baseurl;?>/events/proposalEventIndex">Events with proposals</a></li>
					<li class="divider"></li>
					<li id='liexport'><a href="<?php echo $baseurl;?>/events/export">Export</a></li>
					<?php if ($isAclAuth): ?>
					<li id='liautomation'><a href="<?php echo $baseurl;?>/events/automation">Automation</a></li>
					<?php endif;
				break;

				case 'regexp': ?>
					<li id='liindex'><?php echo $this->Html->link('List Regexp', array('admin' => $isSiteAdmin, 'action' => 'index'));?></li>
					<?php if ($isSiteAdmin): ?>
					<li id='liadd'><?php echo $this->Html->link('New Regexp', array('admin' => true, 'action' => 'add'));?></li>
					<li><?php echo $this->Form->postLink('Perform on existing', array('admin' => true, 'action' => 'clean'));?></li>
					<?php endif;
					if ($menuItem == 'edit'):?>
					<li class="divider"></li>
					<li class="active"><?php echo $this->Html->link('Edit Regexp', array('admin' => true, 'action' => 'edit', h($id)));?></li>
					<li><?php echo $this->Form->postLink('Delete Regexp', array('admin' => true, 'action' => 'delete', h($id)), null, __('Are you sure you want to delete # %s?', h($id)));?></li>
					<?php
					endif;
				break;
				case 'warninglist':?>
					<?php if ($menuItem == 'view'): ?><li class="active"><a href="#">View Warninglist</a></li><?php endif;?>
					<li id='liindex'><?php echo $this->Html->link('List Warninglists', array('action' => 'index'));?></li>
					<li><?php echo $this->Form->postLink('Update Warninglists', '/warninglists/update'); ?></li>
				<?php
				break;
				case 'whitelist':?>
					<li id='liindex'><?php echo $this->Html->link('List Whitelist', array('admin' => $isSiteAdmin, 'action' => 'index'));?></li>
					<?php if ($isSiteAdmin): ?>
					<li id='liadd'><?php echo $this->Html->link('New Whitelist', array('admin' => true, 'action' => 'add'));?></li>
					<?php endif;
					if ($menuItem == 'edit'):?>
					<li class="divider"></li>
					<li class="active"><?php echo $this->Html->link('Edit Whitelist', array('admin' => true, 'action' => 'edit', h($id)));?></li>
					<li><?php echo $this->Form->postLink('Delete Whitelist', array('admin' => true, 'action' => 'delete', h($id)), null, __('Are you sure you want to delete # %s?', h($id)));?></li>
					<?php
					endif;
				break;

				case 'globalActions':
					if (((Configure::read('MISP.disableUserSelfManagement') && $isAdmin) || !Configure::read('MISP.disableUserSelfManagement')) && ($menuItem === 'edit' || $menuItem === 'view')): ?>
					<li id='liedit'><?php echo $this->Html->link(__('Edit User', true), array('action' => 'edit', $user['User']['id'])); ?></li>
					<li class="divider"></li>
					<?php elseif (Configure::read('Plugin.CustomAuth_custom_password_reset')): ?>
					<li id='lipwreset'><a href="<?php echo h(Configure::read('Plugin.CustomAuth_custom_password_reset'));?>">Reset Password</a></li>
					<?php endif; ?>
					<li id='liview'><a href="<?php echo $baseurl;?>/users/view/me">My Profile</a></li>
					<li id='lidashboard'><a href="<?php echo $baseurl;?>/users/dashboard">Dashboard</a></li>
					<li id='limembers'><a href="<?php echo $baseurl;?>/users/memberslist">Members List</a></li>
					<li id='liindexOrg'><a href="<?php echo $baseurl;?>/organisations/index">List Organisations</a></li>
					<?php if ($menuItem === 'viewOrg'): ?>
						<li class="active"><a href="<?php echo $baseurl;?>/organisations/view/<?php echo h($id);?>">View Organisation</a></li>
					<?php endif;?>
					<li id='liroles'><a href="<?php echo $baseurl;?>/roles/index">Role Permissions</a></li>
					<li class="divider"></li>
					<?php if ($menuItem === 'editSG' || ($menuItem == 'viewSG' && $mayModify)): ?>
						<li id='lieditSG'><a href="<?php echo $baseurl;?>/sharing_groups/edit/<?php echo h($id); ?>">Edit Sharing Group</a></li>
						<li id='liviewSG'><a href="<?php echo $baseurl;?>/sharing_groups/view/<?php echo h($id);?>">View Sharing Group</a></li>
					<?php endif; ?>
					<li id='liindexSG'><a href="<?php echo $baseurl;?>/sharing_groups/index">List Sharing Groups</a></li>
					<li id='liaddSG'><a href="<?php echo $baseurl;?>/sharing_groups/add">Add Sharing Group</a></li>
					<li class="divider"></li>
					<li id='liuserGuide'><a href="<?php echo $baseurl;?>/pages/display/doc/general">User Guide</a></li>
					<li id='literms'><a href="<?php echo $baseurl;?>/users/terms">Terms &amp; Conditions</a></li>
					<li id='listatistics'><a href="<?php echo $baseurl;?>/users/statistics">Statistics</a></li>
					<?php
				break;

				case 'sync':
					if ($menuItem === 'previewEvent' && $isSiteAdmin) : ?>
					<li class="active"><?php echo $this->Html->link('Explore Remote Event', array('controller' => 'servers', 'action' => 'previewEvent', h($server['Server']['id']), h($event['Event']['id']))); ?></li>
					<li><?php echo $this->Form->postLink('Fetch This Event', '/servers/pull/' . $server['Server']['id'] . '/' . $event['Event']['id'], null, __('Are you sure you want to fetch and save this event on your instance?', $this->Form->value('Server.id'))); ?></li>
					<li><?php echo $this->Html->link('Explore Remote Server', array('controller' => 'servers', 'action' => 'previewIndex', h($server['Server']['id']))); ?></li>
					<?php endif;
					if ($menuItem === 'previewIndex' && $isSiteAdmin) : ?>
					<li class="active"><?php echo $this->Html->link('Explore Remote Server', array('controller' => 'servers', 'action' => 'previewIndex', h($id))); ?></li>
					<?php endif; ?>
					<?php if ($menuItem === 'edit' && $isSiteAdmin): ?>
					<li class="active"><?php echo $this->Html->link('Edit Server', array('controller' => 'servers', 'action' => 'edit')); ?></li>
					<li><?php echo $this->Form->postLink('Delete', array('action' => 'delete', $this->Form->value('Server.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Server.id'))); ?></li>
					<li class="divider"></li>
					<?php endif; ?>
					<li id='liindex'><?php echo $this->Html->link('List Servers', array('controller' => 'servers', 'action' => 'index'));?></li>
					<?php if ($isSiteAdmin): ?>
					<li id='liadd'><?php echo $this->Html->link(__('New Server'), array('controller' => 'servers', 'action' => 'add')); ?></li>
					<?php endif;?>
					<?php
				break;

				case 'admin':
					if ($menuItem === 'editUser' || $menuItem === 'viewUser'): ?>
					<li id='liviewUser'><?php echo $this->Html->link('View User', array('controller' => 'users', 'action' => 'view', 'admin' => true, h($id))); ?> </li>
					<li><a href="#/" onClick="initiatePasswordReset('<?php echo h($id); ?>');">Send Credentials</a></li>
					<li id='lieditUser'><?php echo $this->Html->link('Edit User', array('controller' => 'users', 'action' => 'edit', 'admin' => true, h($id))); ?> </li>
					<li><?php echo $this->Form->postLink('Delete User', array('admin' => true, 'action' => 'delete', h($id)), null, __('Are you sure you want to delete # %s? It is highly recommended to never delete users but to disable them instead.', h($id)));?></li>
					<li class="divider"></li>
					<?php endif;
					if ($isSiteAdmin && $menuItem === 'editRole'): ?>
					<li class="active"><?php echo $this->Html->link('Edit Role', array('controller' => 'roles', 'action' => 'edit', 'admin' => true, h($id))); ?> </li>
					<li><?php echo $this->Form->postLink('Delete Role', array('controller' => 'roles', 'admin' => true, 'action' => 'delete', h($id)), null, __('Are you sure you want to delete # %s?', h($id)));?></li>
					<li class="divider"></li>
					<?php endif;
					if ($isSiteAdmin): ?>
					<li id='liaddUser'><?php echo $this->Html->link('Add User', array('controller' => 'users', 'action' => 'add', 'admin' => true)); ?> </li>
					<li id='liindexUser'><?php echo $this->Html->link('List Users', array('controller' => 'users', 'action' => 'index', 'admin' => true)); ?> </li>
					<?php endif; ?>
					<?php if ($isAdmin): ?>
					<li id='licontact'><?php echo $this->Html->link('Contact Users', array('controller' => 'users', 'action' => 'email', 'admin' => true)); ?> </li>
					<?php endif; ?>
					<li class="divider"></li>
					<?php if ($isSiteAdmin): ?>
					<li id='liaddOrg'><a href="<?php echo $baseurl;?>/admin/organisations/add">Add Organisation</a></li>
					<?php if ($menuItem === 'editOrg' || $menuItem === 'viewOrg'): ?>
						<li id='lieditOrg'><a href="<?php echo $baseurl;?>/admin/organisations/edit/<?php echo h($id);?>">Edit Organisation</a></li>
						<li id='limergeOrg'><a class="useCursorPointer" onClick="getPopup('<?php echo h($id); ?>', 'organisations', 'merge', 'admin');">Merge Organisation</a></li>
					<?php endif;?>
					<?php if ($menuItem === 'editOrg' || $menuItem === 'viewOrg'): ?>
						<li id='liviewOrg'><a href="<?php echo $baseurl;?>/organisations/view/<?php echo h($id);?>">View Organisation</a></li>
					<?php endif;?>
					<li id='liindexOrg'><a href="<?php echo $baseurl;?>/organisations/index">List Organisations</a></li>
					<li class="divider"></li>
					<li id='liaddRole'><?php echo $this->Html->link('Add Role', array('controller' => 'roles', 'action' => 'add', 'admin' => true)); ?> </li>
					<?php endif; ?>
					<li id='liindexRole'><?php echo $this->Html->link('List Roles', array('controller' => 'roles', 'action' => 'index', 'admin' => true)); ?> </li>
					<?php if ($isSiteAdmin): ?>
						<li class="divider"></li>
						<li id='liadminTools'><a href="<?php echo $baseurl;?>/pages/display/administration">Administrative Tools</a></li>
						<li id='liserverSettings'><a href="<?php echo $baseurl;?>/servers/serverSettings">Server Settings</a></li>
						<li class="divider"></li>
						<?php if (Configure::read('MISP.background_jobs')): ?>
							<li id='lijobs'><a href="<?php echo $baseurl;?>/jobs/index">Jobs</a></li>
							<li class="divider"></li>
							<li id='litasks'><a href="<?php echo $baseurl;?>/tasks">Scheduled Tasks</a></li>
						<?php endif;
						if (Configure::read('MISP.enableEventBlacklisting')): ?>
							<li <?php if ($menuItem === 'eventBlacklistsAdd') echo 'class="active"';?>><a href="<?php echo $baseurl;?>/eventBlacklists/add">Blacklists Event</a></li>
							<li <?php if ($menuItem === 'eventBlacklists') echo 'class="active"';?>><a href="<?php echo $baseurl;?>/eventBlacklists">Manage Event Blacklists</a></li>
						<?php endif;
						if (Configure::read('MISP.enableOrgBlacklisting')): ?>
							<li <?php if ($menuItem === 'orgBlacklistsAdd') echo 'class="active"';?>><a href="<?php echo $baseurl;?>/orgBlacklists/add">Blacklists Organisation</a></li>
							<li <?php if ($menuItem === 'orgBlacklists') echo 'class="active"';?>><a href="<?php echo $baseurl;?>/orgBlacklists">Manage Org Blacklists</a></li>
						<?php endif;
					endif;
				break;

				case 'logs': ?>
					<li id='liindex'><?php echo $this->Html->link('List Logs', array('admin' => true, 'action' => 'index'));?></li>
					<li id='lisearch'><?php echo $this->Html->link('Search Logs', array('admin' => true, 'action' => 'search'));?></li>
					<?php
				break;

				case 'threads':

					if ($menuItem === 'add' || $menuItem === 'view') {
						if (!(empty($thread_id) && empty($target_type))) { ?>
					<li  id='view'><?php echo $this->Html->link('View Thread', array('controller' => 'threads', 'action' => 'view', h($thread_id)));?></li>
					<li  id='add'><?php echo $this->Html->link('Add Post', array('controller' => 'posts', 'action' => 'add', 'thread', h($thread_id)));?></li>
					<li class="divider"></li>
					<?php
						}
					}
					if ($menuItem === 'edit') { ?>
						<li><?php echo $this->Html->link('View Thread', array('controller' => 'threads', 'action' => 'view', h($thread_id)));?></li>
						<li class="active"><?php echo $this->Html->link('Edit Post', array('controller' => 'threads', 'action' => 'view', h($id)));?></li>
						<li class="divider"></li>
					<?php
					}
					?>
					<li id='liindex'><?php echo $this->Html->link('List Threads', array('controller' => 'threads', 'action' => 'index'));?></li>
					<li id='liadd'><a href = "<?php echo Configure::read('MISP.baseurl');?>/posts/add">New Thread</a></li>
					<?php
				break;

				case 'tags': ?>
					<li id='liindexfav'><?php echo $this->Html->link('List Favourite Tags', array('action' => 'index', true));?></li>
					<li id='liindex'><?php echo $this->Html->link('List Tags', array('action' => 'index'));?></li>
					<?php if ($isAclTagEditor): ?>
					<li id='liadd'><?php echo $this->Html->link('Add Tag', array('action' => 'add'));?></li>
					<?php
					endif;
					if ($menuItem === 'edit'):
					?>
					<li class="active"><?php echo $this->Html->link('Edit Tag', array('action' => 'edit'));?></li>
					<?php
					endif;
				break;

				case 'taxonomies': ?>
					<li id='liindex'><a href="<?php echo $baseurl;?>/taxonomies/index">List Taxonomies</a></li>
					<?php if ($menuItem === 'view'): ?>
						<li id='liview'><a href="">View Taxonomy</a></li>
						<li id='lidelete'><a class="useCursorPointer" onClick="deleteObject('taxonomies', 'delete', '<?php echo h($id); ?>', '<?php echo h($id); ?>');">Delete Taxonomy</a></li>
					<?php
					endif;
					if ($isSiteAdmin):
					?>
						<li id='liupdate'><?php echo $this->Form->postLink('Update Taxonomies', array('controller' => 'taxonomies', 'action' => 'update'));?></li>
					<?php
					endif;
				break;

				case 'templates': ?>
					<li id='liindex'><a href="<?php echo $baseurl;?>/templates/index">List Templates</a></li>
					<?php if ($isSiteAdmin || $isAclTemplate): ?>
					<li id='liadd'><a href="<?php echo $baseurl;?>/templates/add">Add Template</a></li>
					<?php
					endif;
					if (($menuItem === 'view' || $menuItem === 'edit')):
					?>
					<li id='liview'><a href="<?php echo $baseurl;?>/templates/view/<?php echo h($id); ?>">View Template</a></li>
					<?php if ($mayModify): ?>
					<li id='liedit'><a href="<?php echo $baseurl;?>/templates/edit/<?php echo h($id); ?>">Edit Template</a></li>
					<?php
					endif;
					endif;
				break;

				case 'feeds': ?>
					<li id='liindex'><a href="<?php echo $baseurl;?>/feeds/index">List Feeds</a></li>
					<li id='liadd'><a href="<?php echo $baseurl;?>/feeds/add">Add Feed</a></li>
					<?php if ($menuItem === 'edit'): ?>
						<li class="active"><a href="#">Edit Feed</a></li>
					<?php elseif ($menuItem === 'previewIndex'): ?>
						<li id='lipreviewIndex'><a href="<?php echo $baseurl;?>/feeds/previewIndex/<?php echo h($feed['Feed']['id']); ?>">PreviewIndex</a></li>
					<?php elseif ($menuItem === 'previewEvent'): ?>
						<li id='lipreviewEvent'><a href="<?php echo $baseurl;?>/feeds/previewEvent/<?php echo h($feed['Feed']['id']); ?>/<?php echo h($id);?>">PreviewEvent</a></li>
					<?php endif;
				break;

				case 'news': ?>
					<li id='liindex'><a href="<?php echo $baseurl;?>/news/index">View News</a></li>
				<?php
					if ($isSiteAdmin): ?>
						<li id='liadd'><a href="<?php echo $baseurl;?>/news/add">Add News Item</a></li>
						<?php if ($menuItem === 'edit'): ?>
							<li class="active"><a href="#">Edit News Item</a></li>
						<?php endif;
					endif;
				break;
			}
		?>
	</ul>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#li<?php echo h($menuItem); ?>').addClass("active");
});
</script>
