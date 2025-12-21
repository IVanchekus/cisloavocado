<?php

namespace App\Repositories\Dictionaries;

use App\Models\Role;
use Illuminate\Support\Collection;

class RoleDictionaryRepository
{
    public function all(): Collection
    {
        return Role::query()
            ->select(['id', 'slug', 'title'])
            ->orderBy('id')
            ->get();
    }
}


