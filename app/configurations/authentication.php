<?php

declare(strict_types=1);

auth()->config('session', true);
auth()->config('db.table', 'users');
auth()->config('password.key', 'password');
auth()->config('messages.loginParamsError', 'Cédula o contraseña incorrecta');
auth()->config('messages.loginPasswordError', auth()->config('messages.loginParamsError'));
auth()->config('timestamps', false);
