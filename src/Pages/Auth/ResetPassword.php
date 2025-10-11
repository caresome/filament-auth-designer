<?php

namespace Caresome\FilamentAuthDesigner\Pages\Auth;

use Caresome\FilamentAuthDesigner\Concerns\HasAuthDesignerLayout;
use Filament\Auth\Pages\PasswordReset\ResetPassword as BaseResetPassword;

class ResetPassword extends BaseResetPassword
{
    use HasAuthDesignerLayout;

    protected static string $layout = 'filament-auth-designer::components.layouts.auth';

    protected function getAuthDesignerPageKey(): string
    {
        return 'password-reset';
    }
}
