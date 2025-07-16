<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ProductRepository
{
    public function bulkUpdate(array $data): int
    {
        if (empty($data)) return 0;
        $table = 'products';
        $columns = array_keys(reset($data));
        $ids = array_keys($data);

        $cases = [];
        foreach ($columns as $column) {
            $case = "CASE id ";
            foreach ($data as $id => $fields) {
                $value = addslashes($fields[$column]);
                $case .= "WHEN {$id} THEN '{$value}' ";
            }
            $case .= "END";
            $cases[] = "{$column} = {$case}";
        }

        $sql = sprintf(
            "UPDATE %s SET %s WHERE id IN (%s)",
            $table,
            implode(', ', $cases),
            implode(', ', $ids)
        );

        return DB::update($sql);
    }

}
