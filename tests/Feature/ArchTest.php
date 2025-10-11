<?php

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('ConfigKeys class is used instead of magic strings')
    ->expect('Caresome\FilamentAuthDesigner\AuthDesignerPlugin')
    ->not->toUse('filament-auth-designer.login.media')
    ->not->toUse('filament-auth-designer.registration.media')
    ->not->toUse('filament-auth-designer.password-reset.media')
    ->not->toUse('filament-auth-designer.email-verification.media');

arch('HasAuthDesignerLayout trait is only used in auth pages')
    ->expect('Caresome\FilamentAuthDesigner\Concerns\HasAuthDesignerLayout')
    ->toOnlyBeUsedIn('Caresome\FilamentAuthDesigner\Pages\Auth');

arch('all enums have string backing values')
    ->expect('Caresome\FilamentAuthDesigner\Enums')
    ->toBeEnums();
