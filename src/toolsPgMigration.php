<?php

namespace ArcheeNic\LaraTools;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class toolsPgMigration extends Migration
{
    protected function UUIDPrimaryKey(Blueprint $blueprint, $column = 'id', $default = 'gen_random_uuid()'): void
    {
        $blueprint->uuid($column)->primary()->default(DB::raw($default));
    }

    protected function upStandardFields(Blueprint $table, bool $ignoreId = false): void
    {
        if (!$ignoreId) {
            $table->increments('id');
        }
        $table->timestamps();
    }

    protected function createForeignField(
        Blueprint $blueprint,
        array $createArray,
        string $parentKey,
        string $parentTable,
        string $onDelete = 'restrict',
        string $onUpdate = 'restrict'
    ): void {
        $field = $blueprint;


        if (!empty($createArray['integer'])) {
            $createArray['field'] = $createArray['integer'];
        } elseif (!empty($createArray['string'])) {
            $createArray['field'] = $createArray['string'];
        } elseif (!empty($createArray['uuid'])) {
            $createArray['field'] = $createArray['uuid'];
        }

        if (empty($createArray['field'])) {
            throw new RuntimeException('undefined or empty "field" key in $createArray');
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
        $blueprint->foreign($fieldName)->references($parentKey)->on($parentTable)->onDelete($onDelete)
            ->onUpdate($onUpdate);
    }

    protected function setCommentTable(string $name, string $comment): void
    {
        $this->exec("COMMENT ON TABLE $name IS '$comment';");
    }

    protected function setCommentSchema(string $name, string $comment): void
    {
        $this->exec("COMMENT ON SCHEMA $name IS '$comment';");
    }

    final protected function exec(string $sqlString): void
    {
        DB::connection()->getPdo()->exec($sqlString);
    }

    protected function addSchema(string $name): void
    {
        $this->exec("CREATE SCHEMA $name");
    }

    protected function dropSchema(string $name): void
    {
        $this->exec("DROP SCHEMA $name");
    }
}
