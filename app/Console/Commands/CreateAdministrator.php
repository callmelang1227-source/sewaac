<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('admin:create {--name= : Nama administrator} {--email= : Email administrator}')]
#[Description('Membuat akun administrator tanpa menggunakan kata sandi bawaan')]
class CreateAdministrator extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = trim((string) ($this->option('name') ?: $this->ask('Nama administrator')));
        $email = Str::lower(trim((string) ($this->option('email') ?: $this->ask('Email administrator'))));
        $password = (string) $this->secret('Kata sandi (minimal 12 karakter)');
        $confirmation = (string) $this->secret('Ulangi kata sandi');

        if ($name === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Nama dan email administrator yang valid wajib diisi.');

            return self::FAILURE;
        }

        if (mb_strlen($password) < 12) {
            $this->error('Kata sandi administrator minimal terdiri dari 12 karakter.');

            return self::FAILURE;
        }

        if ($password !== $confirmation) {
            $this->error('Konfirmasi kata sandi tidak sama.');

            return self::FAILURE;
        }

        if (User::query()->where('email', $email)->exists()) {
            $this->error('Email tersebut sudah digunakan.');

            return self::FAILURE;
        }

        $administrator = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $administrator->forceFill(['is_admin' => true])->save();

        $this->info('Akun administrator berhasil dibuat.');

        return self::SUCCESS;
    }
}
