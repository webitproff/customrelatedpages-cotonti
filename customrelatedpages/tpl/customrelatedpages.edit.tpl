<!-- BEGIN: MAIN -->
<div class="customrelated-container">

    <!-- BEGIN: ROW -->
    <div class="customrelated-row mb-3">
        <label class="form-label">Связанная страница №{NUM}</label>

        <div class="input-group">
            <!-- Это поле отправляется на сервер -->
            <input type="hidden"
                   name="related_id[{INDEX}]"
                   class="customrelated-id"
                   value="{TO_ID}" />

            <!-- select ТОЛЬКО для интерфейса, name убираем полностью -->
            <select class="customrelated-select "
                    data-placeholder="Начните вводить название страницы">
                <!-- IF {TO_ID} > 0 -->
                <option value="{TO_ID}" selected>{TO_TITLE}</option>
                <!-- ENDIF -->
            </select>
        </div>

        <small class="form-text text-muted">
            Введите минимум 2 символа для поиска страниц
        </small>
    </div>
    <!-- END: ROW -->

</div>
<!-- END: MAIN -->
/**
 * customrelatedpages.edit.tpl - Template File for the Plugin Custom Related Pages in edit.tpl 
 *
 * customrelatedpages plugin for Cotonti 0.9.26, PHP 8.4+ 
 * Filename: customrelatedpages.edit.tpl 
 *
 * Date: Jan 18Th, 2026 
 * @package customrelatedpages 
 * @version 2.0.1 
 * @author webitproff 
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff 
 * @license BSD 
 */

display: block;
  width: 100%;
  padding: .375rem 2.25rem .375rem .75rem;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: var(--bs-body-color);