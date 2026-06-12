<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Eloquent\EloquentStudentRepository;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\Eloquent\EloquentPaymentRepository;
use App\Repositories\Contracts\AbsenceRepositoryInterface;
use App\Repositories\Eloquent\EloquentAbsenceRepository;
use App\Repositories\Contracts\NoteRepositoryInterface;
use App\Repositories\Eloquent\EloquentNoteRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(StudentRepositoryInterface::class, EloquentStudentRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, EloquentPaymentRepository::class);
        $this->app->bind(AbsenceRepositoryInterface::class, EloquentAbsenceRepository::class);
        $this->app->bind(NoteRepositoryInterface::class, EloquentNoteRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
