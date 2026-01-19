# customrelatedpages-cotonti

## Overview and Guide for the Custom Related Pages Plugin for Cotonti

The **Custom Related Pages** plugin for the **Cotonti** content management system is designed to manually configure related or recommended pages for each article. It allows site administrators to manage this process through a simple interface, providing the ability to display up to three related items for each page. This article will explain in detail how to install, configure, and use this plugin, as well as how it interacts with the system and is configured for multilingual sites.

## 1. Plugin Installation

### 1.1 Downloading and Installing the Plugin

The first step is to install the plugin on your Cotonti-powered website. The plugin can be uploaded to the Cotonti plugin directory and then activated via the admin panel.

### 1.2 Creating the Database Table

After installing the plugin, a database table must be created to store the related pages' data. This is done automatically upon activation of the plugin, and the table will be named **cot_customrelatedpages**. This table stores the relationships between pages, their display order, and other metadata.

## 2. Plugin Configuration

The plugin allows several parameters to be configured via the admin panel. These include:

- **Maximum Number of Related Items**: This limit defines how many related pages can be assigned to each article. The administrator can choose one of the following values: 0, 1, 2, or 3.
- **Short Description Length**: This parameter allows you to set the maximum length of the text that will be displayed in the recommendation block for each related page. This value can be set to 100, 200, or 300 characters.
- **Use of Select2 for Page Search**: Enabling this option improves the related page selection interface by adding autocomplete when typing text. This makes searching for pages more convenient and faster.

These settings can be modified in the admin panel, within the plugin configuration section.

## 3. Managing Related Pages

### 3.1 Selecting Related Pages

The plugin provides an interface for selecting related pages when editing content. Multiple related items can be assigned to each page. In the interface, the administrator selects pages from a list and can specify their display order.

### 3.2 Managing Display Order

Each related page is assigned a unique display order, which determines the sequence in which the pages will appear on the site. This value is stored in the database and can be changed as needed. In the page editing interface, the administrator can drag and drop related pages to change their order.

### 3.3 Deleting Related Pages

If it is necessary to remove one or more related pages, this can be done directly from the editing interface. Deleting the relationship does not delete the actual page but only removes the link between the current page and the selected item.

## 4. Displaying Related Pages on the Site

### 4.1 Template for Displaying Related Pages

Related pages are displayed on the site using a special template, which can be customized based on the design requirements. The recommendation block will display the page title, short description, and a link to the category.

### 4.2 Multilingual Support

The plugin fully supports working with multilingual websites on the Cotonti platform. If the i18n module is active, the plugin will use translations for page titles and categories based on the user's locale. This allows related pages to be displayed in different languages, which is particularly useful for international projects.

### 4.3 Additional Display Parameters

The template for displaying related pages can be customized to show various elements. For example, the category display can be turned off or the number of characters in the description can be limited. All of these settings can be configured in the template to adapt it to the site's style.

## 5. Working with the Admin Panel

### 5.1 Page Editing Interface

The plugin adds a new block to the admin panel, allowing administrators and editors to select related pages for each article. This block appears on the content editing page, where related pages can be added, removed, or modified.

### 5.2 Using Select2

If the Select2 option is enabled, a search interface with autocomplete will be used for selecting pages. This allows for quick finding of pages by title, which is particularly useful when dealing with a large number of items on the site. Using Select2 also improves the user experience by allowing faster interaction with the interface.

## 6. Handling Requests via Ajax

The plugin supports using Ajax to perform page searches without the need to reload the page. This speeds up the process of working with the plugin, allowing administrators to quickly find pages when adding them to the related list.

## 7. Deleting Related Pages

When an article is deleted, all related pages are also removed from the database. This ensures that there are no "broken" links left and that the site functions correctly. However, it is important to note that deleting the relationship does not remove the actual pages.

## 8. Conclusion

The **Custom Related Pages** plugin for Cotonti provides an effective tool for managing related pages. It is easy to use and allows for easy configuration of which pages will be displayed as recommendations for each article. The multilingual support makes the plugin an ideal solution for sites operating in multiple languages. With its simple interface and settings, the plugin is suitable for both small websites and large international projects.
___
```
/**
 *
 * Date: Jan 18Th, 2026
 * @package customrelatedpages
 * @version 2.0.1
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */
```
___
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
### [More info](https://abuyfile.com/en/market/cotonti/plugs/customrelatedpages)
