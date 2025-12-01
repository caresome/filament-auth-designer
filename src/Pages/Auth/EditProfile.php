<?php

namespace Caresome\FilamentAuthDesigner\Pages\Auth;

use Caresome\FilamentAuthDesigner\Concerns\HasAuthDesignerLayout;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    use HasAuthDesignerLayout;

    protected static ?string $title = 'Profile';

    protected function getAuthDesignerPageKey(): string
    {
        return 'profile';
    }

    public function getLayout(): string
    {
        return static::$layout ?? (static::isSimple() ? 'filament-auth-designer::components.layouts.auth' : 'filament-panels::components.layout.index');
    }
}
