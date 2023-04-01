<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class Unique4User implements ValidationRule
{
    private string $table;
    private ?string $columnName;
    private ?int $user_id;
    private string $userIdField;

    /**
     * @param string $table
     * @param string|null $columnName
     * @param int|null $user_id
     * @param string $userIdField
     */
    public function __construct(string $table, string $columnName = null, int $user_id = null, string $userIdField = 'user_id')
    {

        $this->table = $table;
        $this->columnName = $columnName;
        $this->user_id = $user_id ?? auth()->id();
        $this->userIdField = $userIdField;
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $field = !empty($this->columnName) ? $this->columnName : $attribute;
        $count = DB::table($this->table)->where($field, $value)->where($this->userIdField, $this->user_id)->count();
        if ($count != 0)
            $fail('title', 'this value already exist');
    }
}
