<?php

namespace App\Repositories\Dictionaries;

use App\Models\Permission;
use Illuminate\Support\Collection;

class PermissionDictionaryRepository
{
    public function all(): Collection
    {
        return Permission::query()
            ->select(['id', 'slug', 'title'])
            ->orderBy('id')
            ->get();
    }
}


