<?php namespace ArcheeNic\LaraTools;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class toolsMigration extends Migration
{

    protected function UIIDPrimaryKey(Blueprint $blueprint, $column = 'id'){
        $blueprint->uuid($column)->primary()->default(DB::raw('gen_random_uuid()'));
    }

    /**
     * Добавить стандартные поля
     *
     * @param Blueprint $table
     * @param boolean   $ignoreId если нужно сделать другим ключевым полем
     */
    protected function upStandardFields(Blueprint $table, $ignoreId = false)
    {
        if (!$ignoreId) {
            $table->increments('id');
        }
        $table->timestamps();
    }

    /**
     * Создать и связать поле
     *
     * @deprecated
     *
     * @param Blueprint $blueprint    класс работы с таблицей
     * @param array     $createArray  правила создания, гле ключ - метод
     * @param string    $parent_key   родительский ключ
     * @param string    $parent_table родительская таблица
     * @param string    $onDelete     реакция при удалении родительской записи
     * @param string    $onUpdate     реакция при обновлении родительской записи
     */
    protected function createForeignFieldInteger(
        Blueprint $blueprint,
        $createArray,
        $parent_key,
        $parent_table,
        $onDelete = 'restrict',
        $onUpdate = 'restrict'
    ) {
        $field = $blueprint;

        if (!empty($createArray['integer'])) {
            $createArray['field'] = $createArray['integer'];
        }

        if (empty($createArray['field'])) {
            print 'undefined or empty "field" key in $createArray';
            dd();
        }
        $fieldName = $createArray['field'];

        unset($createArray['field']);

        foreach ($createArray as $k => $v) {
            if (is_null($v)) {
                $v = [];
            }
            if (!is_array($v)) {
                $v = [$v];
            }
            $field = call_user_func_array([$field, $k], $v);
        }
        $blueprint->foreign($fieldName)->references($parent_key)->on($parent_table)->onDelete($onDelete)
            ->onUpdate($onUpdate);
    }

    protected function setForeignField(
        Blueprint $blueprint,
        $field_name,
        $parent_key,
        $parent_table,
        $onDelete = 'restrict',
        $onUpdate = 'restrict'
    ) {
        $blueprint->foreign($field_name)->references($parent_key)->on($parent_table)->onDelete($onDelete)
            ->onUpdate($onUpdate);
    }

    /**
     * Создать и связать поле
     *
     * @param Blueprint $blueprint    класс работы с таблицей
     * @param array     $createArray  правила создания, гле ключ - метод
     * @param string    $parent_key   родительский ключ
     * @param string    $parent_table родительская таблица
     * @param string    $onDelete     реакция при удалении родительской записи
     * @param string    $onUpdate     реакция при обновлении родительской записи
     */
    protected function createForeignField(
        Blueprint $blueprint,
        $createArray,
        $parent_key,
        $parent_table,
        $onDelete = 'restrict',
        $onUpdate = 'restrict'
    ) {
        $field = $blueprint;


        if (!empty($createArray['integer'])) {
            $createArray['field'] = $createArray['integer'];
        } elseif (!empty($createArray['string'])) {
            $createArray['field'] = $createArray['string'];
        }

        if (empty($createArray['field'])) {
            print 'undefined or empty "field" key in $createArray';
            dd();
        }
        $fieldName = $createArray['field'];

        unset($createArray['field']);

        foreach ($createArray as $k => $v) {
            if (is_null($v)) {
                $v = [];
            }
            if (!is_array($v)) {
                $v = [$v];
            }
            $field = call_user_func_array([$field, $k], $v);
        }
        $blueprint->foreign($fieldName)->references($parent_key)->on($parent_table)->onDelete($onDelete)
            ->onUpdate($onUpdate);
    }

    /**
     * Установить комментарий для таблицы
     *
     * @param $table
     * @param $comment
     */
    protected function setCommentTable($table, $comment)
    {
        $this->query("COMMENT ON TABLE $table IS '$comment';");
    }

    protected function query($sql_string)
    {
        \DB::connection()->getPdo()->exec($sql_string);
    }
}