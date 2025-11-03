<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\RequestRepositoryInterface;
use App\Domain\Repositories\ClassRepositoryInterface;
use App\Domain\Repositories\ProfessorRepositoryInterface;
use App\Domain\Repositories\StudentRepositoryInterface;
use App\Infrastructure\DataSources\RequestDataSource;
use App\Infrastructure\DataSources\ClassDataSource;
use App\Infrastructure\DataSources\ProfessorDataSource;
use App\Infrastructure\DataSources\StudentDataSource;
use App\Domain\UseCases\GetPendingRequestsUseCase;
use App\Domain\UseCases\UpdateRequestStatusUseCase;
use App\Domain\UseCases\GetAllClassesUseCase;
use App\Domain\UseCases\CreateClassUseCase;
use App\Domain\UseCases\UpdateClassUseCase;
use App\Domain\UseCases\DeleteClassUseCase;
use App\Domain\UseCases\GetAllProfessorsUseCase;
use App\Domain\UseCases\CreateProfessorUseCase;
use App\Domain\UseCases\UpdateProfessorUseCase;
use App\Domain\UseCases\DeleteProfessorUseCase;
use App\Domain\Services\RequestObserverService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register observer service
        $this->app->singleton(RequestObserverService::class);

        // Register repositories (interfaces -> implementations)
        $this->app->bind(RequestRepositoryInterface::class, RequestDataSource::class);
        $this->app->bind(ClassRepositoryInterface::class, ClassDataSource::class);
        $this->app->bind(ProfessorRepositoryInterface::class, ProfessorDataSource::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentDataSource::class);

        // Register use cases
        $this->app->bind(GetPendingRequestsUseCase::class, function ($app) {
            return new GetPendingRequestsUseCase(
                $app->make(RequestRepositoryInterface::class)
            );
        });

        $this->app->bind(UpdateRequestStatusUseCase::class, function ($app) {
            return new UpdateRequestStatusUseCase(
                $app->make(RequestRepositoryInterface::class)
            );
        });

        $this->app->bind(GetAllClassesUseCase::class, function ($app) {
            return new GetAllClassesUseCase(
                $app->make(ClassRepositoryInterface::class)
            );
        });

        $this->app->bind(CreateClassUseCase::class, function ($app) {
            return new CreateClassUseCase(
                $app->make(ClassRepositoryInterface::class)
            );
        });

        $this->app->bind(UpdateClassUseCase::class, function ($app) {
            return new UpdateClassUseCase(
                $app->make(ClassRepositoryInterface::class)
            );
        });

        $this->app->bind(DeleteClassUseCase::class, function ($app) {
            return new DeleteClassUseCase(
                $app->make(ClassRepositoryInterface::class)
            );
        });

        $this->app->bind(GetAllProfessorsUseCase::class, function ($app) {
            return new GetAllProfessorsUseCase(
                $app->make(ProfessorRepositoryInterface::class)
            );
        });

        $this->app->bind(CreateProfessorUseCase::class, function ($app) {
            return new CreateProfessorUseCase(
                $app->make(ProfessorRepositoryInterface::class)
            );
        });

        $this->app->bind(UpdateProfessorUseCase::class, function ($app) {
            return new UpdateProfessorUseCase(
                $app->make(ProfessorRepositoryInterface::class)
            );
        });

        $this->app->bind(DeleteProfessorUseCase::class, function ($app) {
            return new DeleteProfessorUseCase(
                $app->make(ProfessorRepositoryInterface::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
