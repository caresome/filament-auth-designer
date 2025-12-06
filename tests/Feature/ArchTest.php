<?php

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('Data classes are final')
    ->expect('Caresome\FilamentAuthDesigner\Data')
    ->toBeFinal();

arch('HasAuthDesignerLayout trait is only used in auth pages')
    ->expect(\Caresome\FilamentAuthDesigner\Concerns\HasAuthDesignerLayout::class)
    ->toOnlyBeUsedIn('Caresome\FilamentAuthDesigner\Pages\Auth');

arch('all enums have string backing values')
    ->expect('Caresome\FilamentAuthDesigner\Enums')
    ->toBeEnums();

arch('Support classes have no dependencies on Pages')
    ->expect('Caresome\FilamentAuthDesigner\Support')
    ->not->toUse('Caresome\FilamentAuthDesigner\Pages');
