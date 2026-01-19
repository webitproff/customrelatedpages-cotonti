<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=page.edit.tags
 * Tags=page.edit.tpl:{CUSTOMRELATED_EDIT}
 * [END_COT_EXT]
 */
/**
 * customrelatedpages.page.edit.tags.php - Registration of hooks in the system core to connect the logic of our file to the declared hook or hooks. File for the Plugin Custom Related Pages
 *
 * customrelatedpages plugin for Cotonti 0.9.26, PHP 8.4+
 * Filename: customrelatedpages.page.edit.tags.php
 *
 * Date: Jan 18Th, 2026
 * @package customrelatedpages
 * @version 2.0.1
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

global $cfg, $L, $db, $db_pages, $db_x, $id, $t; // ← Используем $id вместо $page_id, как в core page.edit.php

$max = (int)($cfg['plugin']['customrelatedpages']['maxitems'] ?? 5);
if ($max < 1) $max = 5;

$table = $db_x . 'customrelatedpages';

$related = [];
// Проверка прав администратора
if (cot::$usr['isadmin']) {
    $related = $db->query(
        "SELECT pr.pr_order, pr.pr_to_id AS id, p.page_title AS title
         FROM $table pr
         LEFT JOIN $db_pages p ON p.page_id = pr.pr_to_id
         WHERE pr.pr_from_id = ?
         ORDER BY pr.pr_order ASC
         LIMIT ?",
        [$id, $max]
    )->fetchAll(PDO::FETCH_ASSOC);
} else {
    $related = $db->query(
        "SELECT pr.pr_order, pr.pr_to_id AS id, p.page_title AS title
         FROM $table pr
         LEFT JOIN $db_pages p ON p.page_id = pr.pr_to_id
         WHERE pr.pr_from_id = ? AND p.page_ownerid = ?
         ORDER BY pr.pr_order ASC
         LIMIT ?",
        [$id, cot::$usr['id'], $max]
    )->fetchAll(PDO::FETCH_ASSOC);
}
/* if ($id > 0) {
    $related = $db->query(
        "SELECT pr.pr_order, pr.pr_to_id AS id, p.page_title AS title
         FROM $table pr
         LEFT JOIN $db_pages p ON p.page_id = pr.pr_to_id
         WHERE pr.pr_from_id = ?
         ORDER BY pr.pr_order ASC
         LIMIT ?",
        [$id, $max]
    )->fetchAll(PDO::FETCH_ASSOC);
} */

$tpl = new XTemplate(cot_tplfile('customrelatedpages.edit', 'plug'));

for ($i = 0; $i < $max; $i++) {
    $item = $related[$i] ?? ['id' => 0, 'title' => ''];
    $tpl->assign([
        'NUM' => $i + 1,
        'INDEX' => $i,
        'TO_ID' => (int)$item['id'],
        'TO_TITLE' => htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8')
    ]);
    $tpl->parse('MAIN.ROW');
}

$tpl->parse('MAIN');
$t->assign('CUSTOMRELATED_EDIT', $tpl->text('MAIN'));

Resources::linkFileFooter(Resources::SELECT2);

// Передаём в ajax-запрос ID текущей страницы и ID текущего пользователя
$ajaxUrl = cot_url('plug', [
    'r'              => 'customrelatedpages',
    'ajax'           => 'search',
    'current_page_id' => $id,
    'current_user_id' => cot::$usr['id']
], '', true);

$placeholder = addslashes($L['customrelatedpages_selectpage'] ?? 'Выберите страницу');

Resources::embedFooter(<<<JS
document.addEventListener('DOMContentLoaded', function () {
    $('.customrelated-select').each(function () {
        if (this.dataset.inited) return;
        this.dataset.inited = true;
        const \$select = $(this);
        const \$hidden = \$select.closest('.customrelated-row').find('.customrelated-id');
        \$select.select2({
            ajax: {
                url: '{$ajaxUrl}',
                dataType: 'json',
                delay: 300,
                data: params => ({ q: params.term || '' }),
                processResults: data => ({ results: data.results || [] }),
                cache: true
            },
            minimumInputLength: 2,
            width: '100%',
            placeholder: '{$placeholder}',
            allowClear: true,
            tags: false
        });
        // Синхронизация значения при любом изменении
        const syncValue = () => {
            \$hidden.val(\$select.val() || '');
        };
        \$select.on('change', syncValue);
        \$select.on('select2:select select2:unselect', syncValue);
        // Инициализация уже выбранного значения
        const preselectedId = \$hidden.val();
        if (preselectedId && parseInt(preselectedId) > 0) {
            let text = \$select.find('option[value="' + preselectedId + '"]').text();
            if (!text) text = 'Страница #' + preselectedId;
            const option = new Option(text, preselectedId, true, true);
            \$select.append(option).trigger('change');
        }
    });
});
JS
);