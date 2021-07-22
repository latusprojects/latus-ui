<?php


namespace Latus\UI\Models;


use Illuminate\Database\Eloquent\Model;

class PageSetting extends Model
{
    protected $fillable = [
        'page', 'module', 'key', 'value', 'value_long'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getValue()
    {
        return $this->value ?? $this->value_long;
    }

    public function setValue(string $value)
    {
        if (strlen($value) <= 255) {
            $this->value_long = '';
            $this->value = $value;
            return;
        }
        $this->value = null;
        $this->value_long = $value;
    }
}