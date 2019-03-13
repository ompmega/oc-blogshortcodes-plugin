<?php

namespace Ompmega\BlogShortcodes\Models;

use October\Rain\Database\Model;
use Cache;

/**
 * Shortcode Model
 */
class Shortcode extends Model
{
    const SHORTCODE_CACHE = 'ompmega_blogshortcodes';

    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sluggable;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'ompmega_blogshortcodes_shortcodes';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'code' => 'required',
    ];

    /**
     * @var array List of attributes to automatically generate unique URL names (slugs) for.
     */
    protected $slugs = [
        'code' => 'name'
    ];

    public function afterSave()
    {
        Cache::forget(self::SHORTCODE_CACHE);
    }
}
