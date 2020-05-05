<?php namespace ArcheeNic\LaraTools;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class toolsMigration extends Migration
{

    protected function UUIDPrimaryKey(Blueprint $blueprint, $column = 'id', $default = 'gen_random_uuid()'){
        $blueprint->uuid($column)->primary()->default(DB::raw($default));
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
        } elseif (!empty($createArray['uuid'])) {
            $createArray['field'] = $createArray['uuid'];
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

    /**
     * @deprecated
     * Создать обычный индекс с именем, рекомендуемым postgres
     */
    protected function createIndexByPg($table, $fields = [])
    {
        if (!is_array($fields)) {
            die('incorrect type $fields parameter in createIndexByPg');
        }
        $fields = array_filter($fields);
        if (empty($fields)) {
            die('Empty $fields parameter in createIndexByPg');
        }
        sort($fields);
        $fields_index = implode(',', $fields);
        $fields_key   = implode('_', $fields);
        $this->query("CREATE INDEX {$table}_{$fields_key}_idx  ON {$table} ({$fields_index});");
    }

    /**
     * @deprecated
     * Создать обычный индекс с именем, рекомендуемым postgres
     */
    protected function dropIndexByPg($table, $fields = [])
    {
        if (!is_array($fields)) {
            die('incorrect type $fields parameter in dropIndexByPg');
        }
        $fields = array_filter($fields);
        if (empty($fields)) {
            die('Empty $fields parameter in dropIndexByPg');
        }
        sort($fields);
        $fields_key = implode('_', $fields);
        $this->query("DROP INDEX {$table}_{$fields_key}_idx;");
    }
    protected function query($sql_string)
    {
        \DB::connection()->getPdo()->exec($sql_string);
    }
    /**
     * @deprecated
     * Создает GIN индекс
     *
     * @param        $table_name
     * @param        $gin_field
     * @param array  $fields
     * @param string $method
     */
    function createGinIndex($table_name, $gin_field, $fields = [], $method = 'gin_trgm_ops')
    {
        $fields = array_filter($fields);

        $index_name   = [];
        $index_name[] = $table_name;
        if (!empty($fields)) {
            $index_name[] = implode('_', $fields);
        }
        $index_name[] = $gin_field;
        $index_name[] = 'idx_gin';
        $index_name   = implode('_', $index_name);

        $index_fields = [];
        if (!empty($fields)) {
            foreach ($fields as $v) {
                $index_fields[] = $v;
            }
        }
        $index_fields[] = $gin_field;
        $index_fields   = implode(',', $index_fields);

        print "CREATE INDEX $index_name ON $table_name USING GIN ($index_fields $method);\n";
        \DB::connection()->getPdo()->exec("CREATE INDEX $index_name ON $table_name USING GIN ($index_fields $method);");
    }

    /**
     * @deprecated
     * Создает GIN индекс
     *
     * @param        $table_name
     * @param        $gin_field
     * @param array  $fields
     * @param string $method
     */
    function dropGinIndex($table_name, $gin_field, $fields = [], $method = 'gin_trgm_ops')
    {
        $fields = array_filter($fields);

        $index_name   = [];
        $index_name[] = $table_name;
        if (!empty($fields)) {
            $index_name[] = implode('_', $fields);
        }
        $index_name[] = $gin_field;
        $index_name[] = 'idx_gin';
        $index_name   = implode('_', $index_name);

        print "DROP INDEX $index_name;\n";
        \DB::connection()->getPdo()->exec("DROP INDEX $index_name;");
    }

    /**
     * @deprecated
     * Добавляет комментарий для поля
     * Необходим для postgres
     *
     * @param string $table
     * @param string $field
     * @param string $comment
     */
    protected function addFieldComment($table, $field, $comment)
    {
        $this->query("COMMENT ON COLUMN $table.$field IS '$comment';");
    }
}