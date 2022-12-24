<?php

namespace App\Nova\Actions;

use App\Models\Account;
use App\Repositories\AccountRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class ResetAccountSubscription extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $repository = new AccountRepository();
        $successfulCount = 0;
        foreach ($models as $model) {
            if ($model instanceof Account) {
                try {
                    $repository->resetSubscription($model);
                } catch (\Exception $exception) {
                    return Action::danger("Successful Count: {$successfulCount}, {$exception->getMessage()}");
                }
                $successfulCount++;
            }
        }
        return Action::message("Done!");
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
