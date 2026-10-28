<?php

namespace App\Support\Enum;

enum ProductFilterFieldEnum: string
{
    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';
    case PRICE = 'default_price';
    case STATUS = 'status';
    case CATEGORY_ID = 'category_id';
    case MERCHANT_ID = 'merchant_id';
    case CALORIES = 'calories';
    case PREPARING_TIME = 'preparing_time';

    public function label(): string
    {
        return match($this) {
            self::CREATED_AT => __('admin::lang.Created Date'),
            self::UPDATED_AT => __('admin::lang.Updated Date'),
            self::PRICE => __('admin::lang.Price'),
            self::STATUS => __('admin::lang.Status'),
            self::CATEGORY_ID => __('admin::lang.Category'),
            self::MERCHANT_ID => __('admin::lang.Merchant'),
            self::CALORIES => __('admin::lang.Calories'),
            self::PREPARING_TIME => __('admin::lang.Preparing Time'),
        };
    }

    public function type(): string
    {
        return match($this) {
            self::CREATED_AT, self::UPDATED_AT => 'date',
            self::PRICE, self::CALORIES, self::PREPARING_TIME => 'number',
            self::CATEGORY_ID, self::MERCHANT_ID => 'select',
            self::STATUS => 'select',
        };
    }
}
