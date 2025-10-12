<?php

namespace Caresome\FilamentAuthDesigner\Pages\Auth;

use Caresome\FilamentAuthDesigner\Concerns\HasAuthDesignerLayout;
use Filament\Actions\Action;
use Filament\Auth\Pages\EmailVerification\EmailVerificationPrompt as BaseEmailVerificationPrompt;
use Filament\Facades\Filament;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Schema;

class EmailVerification extends BaseEmailVerificationPrompt
{
    use HasAuthDesignerLayout;

    protected static string $layout = 'filament-auth-designer::components.layouts.auth';

    protected function getAuthDesignerPageKey(): string
    {
        return 'email-verification';
    }

    public function logoutAction(): Action
    {
        return Action::make('logout')
            ->link()
            ->label(__('filament-panels::layout.actions.logout.label'))
            ->color('danger')
            ->size('sm')
            ->url(Filament::getLogoutUrl())
            ->postToUrl();
    }

    public function content(Schema $schema): Schema
    {
        $parentSchema = parent::content($schema);
        $parentComponents = $parentSchema->getComponents();

        return $parentSchema->components([
            ...$parentComponents,
            Actions::make([$this->logoutAction()]),
        ]);
    }
}
