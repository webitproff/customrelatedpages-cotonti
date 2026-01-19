<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=page.edit.delete.done
 * [END_COT_EXT]
 */
/**
 * customrelatedpages.page.edit.delete.done.php - Registration of hooks in the system core to connect the logic of our file to the declared hook or hooks. File for the Plugin Custom Related Pages
 *
 * customrelatedpages plugin for Cotonti 0.9.26, PHP 8.4+
 * Filename: customrelatedpages.page.edit.delete.done.php
 *
 * Date: Jan 18Th, 2026
 * @package customrelatedpages
 * @version 2.0.1
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL');

global $db, $db_customrelatedpages, $id;

if ($id > 0)
{
    $db->delete($db_customrelatedpages, "pr_from_id = ? OR pr_to_id = ?", [$id, $id]);
}