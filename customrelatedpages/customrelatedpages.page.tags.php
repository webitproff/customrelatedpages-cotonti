<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=page.tags
 * Tags=page.tpl:{PAGE_CUSTOMRELATED}
 * [END_COT_EXT]
 */
/**
 * customrelatedpages.page.tags.php - Registration of hooks in the system core to connect the logic of our file to the declared hook or hooks. File for the Plugin Custom Related Pages
 *
 * customrelatedpages plugin for Cotonti 0.9.26, PHP 8.4+
 * Filename: customrelatedpages.page.tags.php
 *
 * Date: Jan 18Th, 2026
 * @package customrelatedpages
 * @version 2.0.1
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */
// Этот файл является точкой входа для подключения тегов плагина Custom Related Pages в шаблон страницы (page.tpl)
// Регистрируется через хук page.tags и отвечает за генерацию блока {PAGE_CUSTOMRELATED} в шаблоне
// Основная задача — выводить список вручную заданных связанных страниц для текущей статьи

// Защита от прямого обращения к файлу через браузер — стандартная проверка Cotonti
defined('COT_CODE') or die('Wrong URL');

// Объявляем глобальные объекты базы данных и таблиц, которые будем использовать в этом скрипте
// $db_i18n_pages добавлен специально для поддержки многоязычных переводов страниц (плагин i18n)
global $db, $db_pages, $db_users, $db_i18n_pages;

// Подключаем языковой файл плагина, чтобы иметь доступ к переводам строк интерфейса
require_once cot_langfile('customrelatedpages', 'plug');

// Подключаем основной файл функций и настроек плагина Custom Related Pages
require_once cot_incfile('customrelatedpages', 'plug');

// Инициализируем переменные, которые будут хранить основную информацию о текущей странице
// Эти значения будут заполняться ниже в зависимости от контекста
$page_id = 0;
$page_owner_name = '';
$page_url = '';

// Проверяем, передан ли массив $pag — это основной массив данных текущей страницы в Cotonti
// Если массив существует и является корректным, начинаем извлекать из него ключевые поля
if (isset($pag) && is_array($pag)) {

    // Приводим page_id к целому числу — это основной идентификатор страницы в базе
    $page_id = isset($pag['page_id']) ? (int)$pag['page_id'] : 0;

    // Определяем текущую языковую локаль пользователя или берём значение по умолчанию из конфига
    $current_locale = Cot::$usr['lang'] ?? Cot::$cfg['defaultlang'];

    // Проверяем, активен ли плагин i18n, отличается ли текущая локаль от дефолтной и есть ли ID страницы
    // Если условия выполняются — пытаемся подгрузить перевод текущей страницы
    if (cot_plugin_active('i18n') && $current_locale !== Cot::$cfg['defaultlang'] && $page_id > 0) {

        // Запрос к таблице переводов страниц для получения заголовка, описания и текста на нужном языке
        $translation = $db->query(
            "SELECT ipage_title, ipage_desc, ipage_text
             FROM $db_i18n_pages
             WHERE ipage_id = ? AND ipage_locale = ?",
            [$page_id, $current_locale]
        )->fetch(PDO::FETCH_ASSOC);

        // Если перевод найден — заменяем значения в массиве $pag на переведённые
        if ($translation) {
            if (!empty($translation['ipage_title'])) $pag['page_title'] = $translation['ipage_title'];
            if (!empty($translation['ipage_desc'])) $pag['page_desc'] = $translation['ipage_desc'];
            if (!empty($translation['ipage_text'])) $pag['page_text'] = $translation['ipage_text'];
        }
    }
}

// Формируем имя таблицы плагина customrelatedpages с учётом префикса базы данных
$table = $db_x . 'customrelatedpages';

// Читаем из конфигурации плагина максимальное количество связанных страниц для вывода
$max_related = (int) ($cfg['plugin']['customrelatedpages']['maxitems'] ?? 5);

// Ограничиваем длину описания связанных страниц — минимум 40 символов, по умолчанию около 160
$desc_len = (int) ($cfg['plugin']['customrelatedpages']['desc_length'] ?? 160);
if ($desc_len < 40) $desc_len = 120;

// Инициализируем пустой массив, в который будут помещены данные связанных страниц
$related_pages = [];

// Проверяем наличие категории у текущей страницы — без неё нет смысла искать связи
if (isset($pag['page_cat']) && !empty($pag['page_cat'])) {

    // Основной запрос: получаем связанные страницы через связующую таблицу customrelatedpages
    // Учитываем только опубликованные страницы (page_state = 0), сортируем по заданному порядку
    $related_pages = $db->query(
        "SELECT
            p.page_id,
            p.page_title,
            p.page_desc,
            p.page_text,
            p.page_cat,
            p.page_alias
         FROM $table pr
         INNER JOIN $db->pages p ON p.page_id = pr.pr_to_id
         WHERE pr.pr_from_id = ?
           AND p.page_state = 0
         ORDER BY pr.pr_order ASC
         LIMIT ?",
        [$pag['page_id'], $max_related]
    )->fetchAll();
}

// Если связанные страницы найдены — начинаем подготовку блока в шаблоне
if (!empty($related_pages)) {

    // Устанавливаем флаг наличия связанных материалов — может использоваться в шаблоне
    $t->assign('CUSTOMRELATED_PAGES', true);

    // Проходим по каждой связанной странице и подготавливаем теги для строки списка
    foreach ($related_pages as $related_page) {

        // Приводим ID связанной страницы к целому числу для безопасной работы
        $related_id = (int)($related_page['page_id'] ?? 0);

        // Если i18n активен и локаль отличается от дефолтной — пытаемся подгрузить перевод
        if (cot_plugin_active('i18n') && $current_locale !== Cot::$cfg['defaultlang'] && $related_id > 0) {

            // Запрос перевода заголовка, описания и текста для данной страницы и языка
            $rel_translation = $db->query(
                "SELECT ipage_title, ipage_desc, ipage_text
                 FROM $db_i18n_pages
                 WHERE ipage_id = ? AND ipage_locale = ?",
                [$related_id, $current_locale]
            )->fetch(PDO::FETCH_ASSOC);

            // При наличии перевода заменяем значения в массиве текущей итерации
            if ($rel_translation) {
                if (!empty($rel_translation['ipage_title'])) $related_page['page_title'] = $rel_translation['ipage_title'];
                if (!empty($rel_translation['ipage_desc'])) $related_page['page_desc'] = $rel_translation['ipage_desc'];
                if (!empty($rel_translation['ipage_text'])) $related_page['page_text'] = $rel_translation['ipage_text'];
            }
        }

        // Получаем название категории текущей (главной) страницы с учётом перевода i18n_structure
        $category_code = $pag['page_cat'] ?? '';
        if (!empty($category_code)) {

            // Сначала пытаемся найти перевод категории в таблице i18n_structure
            if (cot_plugin_active('i18n') && $current_locale !== Cot::$cfg['defaultlang']) {
                $cat_translation = $db->query(
                    "SELECT istructure_title FROM $db_i18n_structure
                     WHERE istructure_code = ? AND istructure_locale = ?",
                    [$category_code, $current_locale]
                )->fetchColumn();
                if ($cat_translation) {
                    $page_category_name = htmlspecialchars($cat_translation, ENT_QUOTES, 'UTF-8');
                }
            }

            // Если перевода нет — берём оригинальное название из таблицы structure
            if (empty($page_category_name)) {
                $category_name_result = $db->query(
                    "SELECT structure_title FROM $db_structure WHERE structure_code = ? AND structure_area = 'page'",
                    [$category_code]
                )->fetchColumn();
                $page_category_name = !empty($category_name_result)
                    ? htmlspecialchars($category_name_result, ENT_QUOTES, 'UTF-8')
                    : htmlspecialchars($category_code, ENT_QUOTES, 'UTF-8');
            }
        }

        // Получаем ссылку на главное изображение связанной страницы (функция плагина)
        $related_image = get_customrelatedpages_main_first_image($related_page['page_id'] ?? 0);

        // Формируем корректный URL страницы — через alias, если он есть, иначе через ID
        $related_url = (isset($related_page['page_alias']) && !empty($related_page['page_alias']))
            ? cot_url('page', 'c=' . $related_page['page_cat'] . '&al=' . $related_page['page_alias'])
            : cot_url('page', 'id=' . ($related_page['page_id'] ?? 0));

        // Заполняем все необходимые теги для одной строки блока связанных материалов
        $t->assign([
            'CUSTOMRELATED_ROW_URL' => htmlspecialchars($related_url, ENT_QUOTES, 'UTF-8'),
            'CUSTOMRELATED_ROW_TITLE' => htmlspecialchars($related_page['page_title'] ?? '', ENT_QUOTES, 'UTF-8'),
            'CUSTOMRELATED_ROW_DESC' => htmlspecialchars(
                cot_string_truncate(strip_tags($related_page['page_text'] ?? ''), $desc_len, true, false),
                ENT_QUOTES,
                'UTF-8'
            ),
            'CUSTOMRELATED_ROW_CAT_TITLE' => htmlspecialchars($page_category_name, ENT_QUOTES, 'UTF-8'),
            'CUSTOMRELATED_ROW_CAT_URL' => cot_url('page', 'c=' . $pag['page_cat'], '', true),
            'CUSTOMRELATED_ROW_LINK_MAIN_IMAGE' => htmlspecialchars($related_image, ENT_QUOTES, 'UTF-8'),
        ]);

        // Парсим строку списка связанных страниц внутри цикла
        $t->parse('MAIN.CUSTOMRELATED_PAGES.CUSTOMRELATED_ROW');
    }

    // После обработки всех строк парсим основной блок связанных страниц
    $t->parse('MAIN.CUSTOMRELATED_PAGES');
}