<?php

declare(strict_types=1);

namespace Caresome\FilamentAuthDesigner\Enums;

enum MediaPosition: string
{
    case Left = 'left';
    case Right = 'right';
    case Top = 'top';
    case Bottom = 'bottom';
    case Cover = 'cover';

    public function isHorizontal(): bool
    {
        return in_array($this, [self::Left, self::Right], true);
    }

    public function isVertical(): bool
    {
        return in_array($this, [self::Top, self::Bottom], true);
    }

    public function isCover(): bool
    {
        return $this === self::Cover;
    }
}
