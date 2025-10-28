<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;

class Login extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'email' => 'advanced@kanban.com',
            'password' => 'demo.advancedkanban!2025',
            'remember' => true,
        ]);
    }
}
