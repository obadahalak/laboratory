<?php

namespace App\Observers;

use App\Services\BuildAccount;
use App\Models\Gender;
use App\Models\Account;
use App\Models\Section;
use App\Models\TestUnit;
use App\Models\TestMethod;
use Termwind\Components\Dd;

class AccountObserver
{
    use BuildAccount;
    /**
     * Handle the Account "created" event.
     *
     * @param  \App\Models\Account  $Account
     * @return void
     */
    public function created(Account $Account)
    {
        $testUnits = TestUnit::Admin()->get();
        $testMethods = TestMethod::Admin()->get();
        $genders = Gender::AdminGenders()->get();
        $sections = Section::AdminSections()->get();

        if (count($sections)) {
            $this->sections($Account, $sections);
        }
        if (count($genders)) {
            $this->genders($Account, $genders);
        }
        if (count($testMethods)) {
            $this->testMethods($Account, $testMethods);
        }
        if (count($testUnits)) {
            $this->testUnits($Account, $testUnits);
        }
    }

    /**
     * Handle the Account "updated" event.
     *
     * @param  \App\Models\Account  $Account
     * @return void
     */
    public function updated(Account $Account)
    {
        //
    }

    /**
     * Handle the Account "deleted" event.
     *
     * @param  \App\Models\Account  $Account
     * @return void
     */
    public function deleted(Account $Account)
    {
        //
    }

    /**
     * Handle the Account "restored" event.
     *
     * @param  \App\Models\Account  $Account
     * @return void
     */
    public function restored(Account $Account)
    {
        //
    }

    /**
     * Handle the Account "force deleted" event.
     *
     * @param  \App\Models\Account  $Account
     * @return void
     */
    public function forceDeleted(Account $Account)
    {
        //
    }
}
