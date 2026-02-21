<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemAttribute extends Model
{
    protected $fillable = ['item_id', 'collection_attribute_id', 'value'];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function definition(): BelongsTo
    {
        return $this->belongsTo(CollectionAttribute::class, 'collection_attribute_id');
    }

    /** Restituisce il valore castato in base al tipo della definizione */
    public function getCastedValueAttribute(): mixed
    {
        if ($this->value === null) {
            return null;
        }

        return match ($this->definition?->type) {
            'number'  => is_numeric($this->value) ? $this->value + 0 : $this->value,
            'boolean' => (bool) $this->value,
            default   => $this->value,
        };
    }
}
