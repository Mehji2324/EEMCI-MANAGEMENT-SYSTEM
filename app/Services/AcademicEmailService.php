<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;

class AcademicEmailService
{
    protected string $domain = 'eemci.edu';

    /**
     * Generate a unique academic email for a validated student.
     *
     * Format: {firstname}.{lastname}@eemci.edu
     * If taken: {firstname}.{lastname}2@eemci.edu, etc.
     */
    public function generate(Student $student): string
    {
        $user      = $student->user;
        $firstName = $this->normalize($user->first_name);
        $lastName  = $this->normalize($user->last_name);

        $baseEmail = "{$firstName}.{$lastName}@{$this->domain}";

        if (! $this->emailExists($baseEmail)) {
            return $baseEmail;
        }

        $counter = 2;
        do {
            $email = "{$firstName}.{$lastName}{$counter}@{$this->domain}";
            $counter++;
        } while ($this->emailExists($email));

        return $email;
    }

    /**
     * Generate a temporary random password.
     */
    public function generateTemporaryPassword(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789@#!';
        $password = '';
        for ($i = 0; $i < 10; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }

    private function normalize(string $name): string
    {
        // Remove accents, lowercase, remove non-alpha chars
        $name = iconv('UTF-8', 'ASCII//TRANSLIT', $name);
        $name = strtolower($name);
        $name = preg_replace('/[^a-z]/', '', $name);
        return $name;
    }

    private function emailExists(string $email): bool
    {
        return User::where('email', $email)->exists()
            || Student::where('academic_email', $email)->exists();
    }
}
