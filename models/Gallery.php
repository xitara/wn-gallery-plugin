<?php

namespace Xitara\Gallery\Models;

use Model;
use Str;

/**
 * Gallery Model
 */
class Gallery extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'xitara_gallery_galleries';

    /**
     * implement translate if installed
     *
     * due compatibility issues we implement both of winter and rainlab
     *
     * @var array
     */
    public $implement = [
        '@Winter.Translate.Behaviors.TranslatableModel',
    ];

    /**
     * @var array Attributes that support translation, if available.
     */
    public $translatable = [
        'name',
        ['slug', 'index' => true],
        'description',
        // ['options[text]', 'index' => true],
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = ['options'];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array Relations
     */
    public $hasOne        = [];
    public $hasMany       = [];
    public $belongsTo     = [];
    public $belongsToMany = [];
    public $morphTo       = [];
    public $morphOne      = [];
    public $morphMany     = [];
    public $attachOne     = [];
    public $attachMany    = [
        'images' => [
            'System\Models\File',
            'delete' => true,
        ],
    ];

    public function beforeSave()
    {
        if ($this->slug == '') {
            $this->slug = \Xitara::slug($this->name);
        }

        $options = $this->options;

        if (isset($options['text'])) {
            foreach ($options['text'] as $key => $text) {
                $options['text'][$key]['id'] = $key;
            }

            $this->options = $options;
        }
    }
}
