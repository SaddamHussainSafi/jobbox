<?php

namespace Botble\JobBoard\Models;

use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\Language;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class AccountLanguage extends BaseModel
{
    protected $table = 'jb_account_languages';

    protected $fillable = [
        'account_id',
        'language_level_id',
        'language',
        'is_native',
    ];

    protected $casts = [
        'is_native' => 'bool',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function languageLevel(): BelongsTo
    {
        return $this->belongsTo(LanguageLevel::class);
    }

    protected function languageName(): Attribute
    {
        return Attribute::get(fn () => Arr::get(Language::getLocales(), $this->language))->shouldCache();
    }
}
