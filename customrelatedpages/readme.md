`page.edit.tpl`
```
<!-- IF {PHP|cot_plugin_active('customrelatedpages')} -->
<!-- IF {PHP|cot_auth('plug', 'customrelatedpages', 'W')} -->
<div class="col-12">
  {CUSTOMRELATED_EDIT}
</div>
<!-- ENDIF -->
<!-- ENDIF -->
```
`page.tpl`
```
<!-- IF {PHP|cot_plugin_active('customrelatedpages')} -->
<!-- BEGIN: CUSTOMRELATED_PAGES -->
<div class="py-3">
<p class="h6 mt-3">{PHP.L.customrelatedpages_title_item}</p>
	<div class="list-group list-group-striped list-group-flush">
	<!-- BEGIN: CUSTOMRELATED_ROW -->
		<div class="list-group-item list-group-item-action">
			<div class="d-flex align-items-start gap-3">
				<!-- IF {CUSTOMRELATED_ROW_LINK_MAIN_IMAGE} -->
				<a href="{CUSTOMRELATED_ROW_URL}">
				<img src="{CUSTOMRELATED_ROW_LINK_MAIN_IMAGE}" alt="{CUSTOMRELATED_ROW_TITLE}" class="me-3 flex-shrink-0 rounded" style="width:80px; height:80px; object-fit:cover;"></a>
				<!-- ENDIF -->
				<div class="flex-grow-1">
					<a href="{CUSTOMRELATED_ROW_URL}">
					<p class="mb-1">{CUSTOMRELATED_ROW_TITLE}</p>
					</a>
					<div><small class="text-secondary">{CUSTOMRELATED_ROW_DESC}</small></div>
					<div><small><a href="{CUSTOMRELATED_ROW_CAT_URL}">{CUSTOMRELATED_ROW_CAT_TITLE}</a></small></div>
				</div>
			</div>
		</div>
	<!-- END: CUSTOMRELATED_ROW -->
	</div>
</div>
<!-- END: CUSTOMRELATED_PAGES -->
<!-- ENDIF -->
```
