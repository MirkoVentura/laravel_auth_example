<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = ['user_id', 'collection_id', 'location_id', 'name', 'description', 'quantity'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function itemAttributes(): HasMany
    {
        return $this->hasMany(ItemAttribute::class);
    }

    /**
     * Restituisce gli attributi come mappa key => ItemAttribute
     * per accesso rapido nelle view.
     */
    public function getAttributeMapAttribute(): \Illuminate\Support\Collection
    {
        return $this->itemAttributes->keyBy(fn($a) => $a->definition?->key);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhereHas('collection', fn($q) => $q->where('name', 'like', "%{$term}%"))
              ->orWhereHas('location', fn($q) => $q->where('name', 'like', "%{$term}%"))
              ->orWhereHas('tags', fn($q) => $q->where('name', 'like', "%{$term}%"))
              ->orWhereHas('itemAttributes', fn($q) => $q->where('value', 'like', "%{$term}%"));
        });
    }
}
