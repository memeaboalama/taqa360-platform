<?php

namespace App\Support\Enum;

enum FilterConditionEnum: string
{
    case EQUALS = 'equals';
    case NOT_EQUALS = 'not_equals';
    case GREATER_THAN = 'greater_than';
    case GREATER_THAN_OR_EQUAL = 'greater_than_or_equal';
    case LESS_THAN = 'less_than';
    case LESS_THAN_OR_EQUAL = 'less_than_or_equal';
    case BETWEEN = 'between';
    case IN = 'in';
    case NOT_IN = 'not_in';
    case CONTAINS = 'contains';
    case STARTS_WITH = 'starts_with';
    case ENDS_WITH = 'ends_with';
    case LAST_N_DAYS = 'last_n_days';
    case NEXT_N_DAYS = 'next_n_days';

    public function label(): string
    {
        return match($this) {
            self::EQUALS => __('admin::lang.Equals'),
            self::NOT_EQUALS => __('admin::lang.Not Equals'),
            self::GREATER_THAN => __('admin::lang.Greater Than'),
            self::GREATER_THAN_OR_EQUAL => __('admin::lang.Greater Than or Equal'),
            self::LESS_THAN => __('admin::lang.Less Than'),
            self::LESS_THAN_OR_EQUAL => __('admin::lang.Less Than or Equal'),
            self::BETWEEN => __('admin::lang.Between'),
            self::IN => __('admin::lang.In List'),
            self::NOT_IN => __('admin::lang.Not In List'),
            self::CONTAINS => __('admin::lang.Contains'),
            self::STARTS_WITH => __('admin::lang.Starts With'),
            self::ENDS_WITH => __('admin::lang.Ends With'),
            self::LAST_N_DAYS => __('admin::lang.Last N Days'),
            self::NEXT_N_DAYS => __('admin::lang.Next N Days'),
        };
    }

    public function requiresValue(): bool
    {
        return true; // All conditions require a value
    }

    public function requiresTwoValues(): bool
    {
        return $this === self::BETWEEN;
    }
}
