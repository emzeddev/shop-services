<?php

namespace Modules\DataGrid\Enums;

use Modules\DataGrid\ColumnTypes\Aggregate;
use Modules\DataGrid\ColumnTypes\Boolean;
use Modules\DataGrid\ColumnTypes\Date;
use Modules\DataGrid\ColumnTypes\Datetime;
use Modules\DataGrid\ColumnTypes\Decimal;
use Modules\DataGrid\ColumnTypes\Integer;
use Modules\DataGrid\ColumnTypes\Text;
use Modules\DataGrid\Exceptions\InvalidColumnTypeException;

enum ColumnTypeEnum: string
{
    /**
     * String.
     */
    case STRING = 'string';

    /**
     * Integer.
     */
    case INTEGER = 'integer';

    /**
     * Decimal.
     */
    case DECIMAL = 'decimal';

    /**
     * Boolean.
     */
    case BOOLEAN = 'boolean';

    /**
     * Date.
     */
    case DATE = 'date';

    /**
     * Date time.
     */
    case DATETIME = 'datetime';

    /**
     * Aggregate.
     */
    case AGGREGATE = 'aggregate';

    /**
     * Get the corresponding class name for the column type.
     */
    public static function getClassName(string $type): string
    {
        return match ($type) {
            self::STRING->value    => Text::class,
            self::INTEGER->value   => Integer::class,
            self::DECIMAL->value   => Decimal::class,
            self::BOOLEAN->value   => Boolean::class,
            self::DATE->value      => Date::class,
            self::DATETIME->value  => Datetime::class,
            self::AGGREGATE->value => Aggregate::class,
            default                => throw new InvalidColumnTypeException("Invalid column type: {$type}"),
        };
    }
}
