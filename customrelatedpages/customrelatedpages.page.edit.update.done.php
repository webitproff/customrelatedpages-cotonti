<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=page.edit.update.done
 * [END_COT_EXT]
 */
/**
 * customrelatedpages.page.edit.update.done.php - Registration of hooks in the system core to connect the logic of our file to the declared hook or hooks. File for the Plugin Custom Related Pages
 *
 * customrelatedpages plugin for Cotonti 0.9.26, PHP 8.4+
 * Filename: customrelatedpages.page.edit.update.done.php
 *
 * Date: Jan 18Th, 2026
 * @package customrelatedpages
 * @version 2.0.1
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */
 
 
defined('COT_CODE') or die('Wrong URL');

global $db, $db_pages, $db_x, $page_id, $id, $cfg; // ← $id — ключевой fallback для edit/update

// При редактировании существующей страницы используем $id (он всегда доступен после загрузки страницы)
$real_id = (int)($id > 0 ? $id : $page_id);

if ($real_id <= 0) {
    return; // Если ID всё равно 0 — не редактирование существующей страницы
}

$max = (int)($cfg['plugin']['customrelatedpages']['maxitems'] ?? 5);
if ($max < 1) $max = 5;

$db_customrelatedpages = $db_x . 'customrelatedpages';

// Удаляем старые связи
$db->delete($db_customrelatedpages, "pr_from_id = ?", [$real_id]);

$related_ids = cot_import('related_id', 'P', 'ARR');

if (!is_array($related_ids) || empty($related_ids)) {
    return;
}

$used = [];
$order = 0;

foreach ($related_ids as $val) {
    $rel_id = (int) trim($val);
    if ($rel_id < 1 || $rel_id == $real_id || in_array($rel_id, $used)) {
        continue;
    }

    $exists = $db->query(
        "SELECT 1 FROM $db_pages WHERE page_id = ? AND page_state = 0",
        [$rel_id]
    )->fetchColumn();

    if (!$exists) continue;

    $db->insert($db_customrelatedpages, [
        'pr_from_id' => $real_id,
        'pr_to_id'   => $rel_id,
        'pr_order'   => $order++
    ]);

    $used[] = $rel_id;

    if ($order >= $max) break;
}