<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectionAttribute extends Model
{
    protected $fillable = ['collection_id', 'name', 'key', 'type', 'required', 'sort_order'];

    protected $casts = ['required' => 'boolean'];

    public const TYPES = [
        'text'    => 'Testo',
        'number'  => 'Numero',
        'date'    => 'Data',
        'boolean' => 'Sì/No',
        'url'     => 'URL',
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function itemAttributes(): HasMany
    {
        return $this->hasMany(ItemAttribute::class);
    }

    /** Genera una key slug dal nome se non fornita */
    public static function makeKey(string $name): string
    {
        return strtolower(preg_replace('/[^a-z0-9]+/i', '_', trim($name)));
    }
}
