<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Course;
use App\Models\Topic;
use App\Models\Lesson;
use App\Policies\CoursePolicy;
use App\Policies\TopicPolicy;
use App\Policies\LessonPolicy;

class AppServiceProvider extends ServiceProvider
{
protected $policies = [
    Course::class => CoursePolicy::class,
    Topic::class  => TopicPolicy::class,
    Lesson::class => LessonPolicy::class,
];

protected function bootPolicies(): void
{
    foreach ($this->policies as $model => $policy) {
        \Illuminate\Support\Facades\Gate::policy($model, $policy);
    }
}
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
