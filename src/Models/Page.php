<?php

namespace MSA\LaravelGrapes\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MSA\LaravelGrapes\Services\GenerateFrontEndService;

class Page extends Model
{
    use HasFactory;

    protected $table = 'pages';

    protected $fillable = [
        'name',
        'slug',
        'page_data',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'page_data' => 'array',
        ];
    }

    public function scopeWhereActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getStylesAttribute()
    {
        return $this->page_data['gjs-css'];
    }

    public function getHtmlAttribute(): string
    {
        $slug = $this->slug === '/' ? 'home-page' : $this->slug;

        return app(GenerateFrontEndService::class)->htmlInitialization($this->page_data['gjs-html'], $slug);
    }
}
