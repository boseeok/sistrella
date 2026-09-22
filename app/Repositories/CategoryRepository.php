<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository
{
    protected function model(): string
    {
        return Category::class;
    }

    /**
     * Full active category tree (3 levels) for the mega menu.
     *
     * @return Collection<int,Category>
     */
    public function menuTree(): Collection
    {
        return $this->query()->active()->roots()
            ->with(['children' => fn ($q) => $q->active()->with(['children' => fn ($c) => $c->active()])])
            ->orderBy('sort_order')
            ->get();
    }

    public function findBySlug(string $slug): ?Category
    {
        return $this->query()->active()->where('slug', $slug)->first();
    }
}
