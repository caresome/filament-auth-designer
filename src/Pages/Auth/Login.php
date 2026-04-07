<?php

declare(strict_types=1);

namespace Caresome\FilamentAuthDesigner\Pages\Auth;

use Caresome\FilamentAuthDesigner\Concerns\HasAuthDesignerLayout;
use Filament\Auth\Pages\Login as BaseLogin;

class Login extends BaseLogin
{
    use HasAuthDesignerLayout;

    protected static string $layout = 'filament-auth-designer::components.layouts.auth';

    protected function getAuthDesignerPageKey(): string
    {
        return 'login';
    }
}
