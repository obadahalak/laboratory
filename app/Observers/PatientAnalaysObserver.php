<?php

namespace App\Observers;

use App\Models\Patient;
use App\Models\PatieonAnalys;
use App\Services\CalculatePrice;

class PatientAnalaysObserver
{
    use CalculatePrice;



    /**
     * Handle the PatieonAnalys "created" event.
     *
     * @param  \App\Models\PatieonAnalys  $patieonAnalys
     * @return void
     */
    public function created(PatieonAnalys $patieonAnalys)
    {
        $data = request()->data;

        if (isset($data['doctor_id']))
            return $this->doctor($data, $patieonAnalys);

        if (isset( $data['company_id']))
            return $this->company($data, $patieonAnalys);


        if (isset($data['lab_id']))
            return $this->lab($data, $patieonAnalys);
    }

    /**
     * Handle the PatieonAnalys "updated" event.
     *
     * @param  \App\Models\PatieonAnalys  $patieonAnalys
     * @return void
     */
    public function updated(PatieonAnalys $patieonAnalys)
    {
        // $patient=Patient::find(request()->patientId);
        // $totalPrices=$patient->patieonAnalys->sum('price');
        // $patient->update([
        //     'price_analysis'=>$totalPrices,
        // ]);

    }

    /**
     * Handle the PatieonAnalys "deleted" event.
     *
     * @param  \App\Models\PatieonAnalys  $patieonAnalys
     * @return void
     */
    public function deleted(PatieonAnalys $patieonAnalys)
    {
        //
    }

    /**
     * Handle the PatieonAnalys "restored" event.
     *
     * @param  \App\Models\PatieonAnalys  $patieonAnalys
     * @return void
     */
    public function restored(PatieonAnalys $patieonAnalys)
    {
        //
    }

    /**
     * Handle the PatieonAnalys "force deleted" event.
     *
     * @param  \App\Models\PatieonAnalys  $patieonAnalys
     * @return void
     */
    public function forceDeleted(PatieonAnalys $patieonAnalys)
    {
        //
    }
}
