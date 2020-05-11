<?php

namespace App\Traits\Model;


trait BaseModelTrait
{
    /**
     * 查询的字段
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getColumns()
    {
        $tableName = $this->table;
        return config('model-service.columns.' . $tableName);
    }

    /**
     * 重载getFillable
     * @return array
     */
    public function getFillable()
    {
        $newColumns = [];
        $tableName = $this->table;
        $columns = config('model-service.columns.' . $tableName);
        if (isset($columns)) {
            foreach ($columns as $item) {
                if (!in_array($item, ['id', 'deleted_at', 'created_at', 'updated_at'])) {
                    array_push($newColumns, $item);
                }
            }
        }
        return $newColumns;
    }
}
