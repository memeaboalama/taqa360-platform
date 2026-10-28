<?php

namespace App\Support\Enum;

enum ProductSortFieldEnum: string
{
    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';
    case PRICE = 'default_price';
    case TITLE = 'title';
    case STATUS = 'status';

    public function label(): string
    {
        return match($this) {
            self::CREATED_AT => __('admin::lang.Created Date'),
            self::UPDATED_AT => __('admin::lang.Updated Date'),
            self::PRICE => __('admin::lang.Price'),
            self::TITLE => __('admin::lang.Product Name'),
            self::STATUS => __('admin::lang.Status'),
        };
    }
}
