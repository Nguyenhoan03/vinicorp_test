<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ModelFinder
{
    /**
     * Find a model by ID or fail.
     *
     * @param string $modelClass
     * @param mixed $id
     * @return mixed
     */
    public function findModelOrFail(string $modelClass, $id)
    {
        try {
            return $modelClass::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            abort(404, 'Không tìm thấy dữ liệu.');
        }
    }
}