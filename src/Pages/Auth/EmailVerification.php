<?php

namespace Caresome\FilamentAuthDesigner\Pages\Auth;

use Caresome\FilamentAuthDesigner\Concerns\HasAuthDesignerLayout;
use Filament\Auth\Pages\EmailVerification\EmailVerificationPrompt as BaseEmailVerificationPrompt;

class EmailVerification extends BaseEmailVerificationPrompt
{
    use HasAuthDesignerLayout;

    protected static string $layout = 'filament-auth-designer::components.layouts.auth';

    protected function getAuthDesignerPageKey(): string
    {
        return 'email-verification';
    }
}
