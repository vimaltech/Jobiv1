<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescriptionTemplate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'body',
        'shortcodes',
        'status',
    ];

    const SHORTCODES = [
        "[company_name]",
        "[year_of_establishment]",
        "[company_intro]",
        "[company_size]",
        "[company_address]",
    ];

    const JOBSHORTCODES = [
        "[job_name]"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'shortcodes' => 'array',
    ];


    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_templates', 'template_id', 'category_id');
    }

    // helper methods
    public static function getShortCodesFrom($text): array
    {

        $pattern = '/\[(.*?)\]/';  // Regular expression pattern to match text within square brackets

        preg_match_all($pattern, $text, $matches);

        $result = array_map(function ($match) {
            return '[' . $match . ']';
        }, $matches[1]); // Wrap the captured text with square brackets

        return $result;
    }

    public static function getShortCodes($type = null): array
    {
        $allShortCodes = [
            'job' => self::JOBSHORTCODES,
            'company' => self::SHORTCODES,
        ];

        return match ($type) {
            'job' => self::JOBSHORTCODES,
            'company' => self::SHORTCODES,
            'all' => $allShortCodes,
            default => $allShortCodes
        };
    }
}
