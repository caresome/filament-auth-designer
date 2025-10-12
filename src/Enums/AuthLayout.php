<?php

namespace Caresome\FilamentAuthDesigner\Enums;

enum AuthLayout: string
{
    case None = 'none';
    case Split = 'split';
    case Overlay = 'overlay';
    case Top = 'top';
    case Panel = 'panel';
}
