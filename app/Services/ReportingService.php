<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Str;

class ReportingService
{

    protected $dateFrom = null;

    protected $dateTo = null;

    protected $projectType = null;

    protected $attributes = [];


    public function forDates($from, $to)
    {
        $this->dateFrom = $from;
        $this->dateTo = $to;

        return $this;
    }

    public function forProjetType($type)
    {
        $this->projectType = $type;

        return $this;
    }

    public function __get($name)
    {
        // appelle le setter si il existe et n'a pas encore été appellé
        $setter = 'set' . Str::studly($name) . 'Attribute';

        if (!isset($this->attributes[$name])) {

            if (!method_exists($this, $setter)) {
                return null;
            }

            $this->$setter();
        }

        return $this->attributes[$name];
    }

    /**
     * Factures, projets et achats à prendre en compte dans le calcul
     * @return void
     */
    public function setInvoicesAttribute()
    {
        $invoices = Invoice::with('project.supplies')->isValidated();

        if ($this->dateFrom) {
            $invoices->whereDate('creation_date', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $invoices->whereDate('creation_date', '<=', $this->dateTo);
        }

    



        $this->attributes['invoices'] = $invoices->get();
    }

    /**
     * Chiffre d'affaires
     * @return void
     */
    public function setRevenueAttribute()
    {
        $this->attributes['revenue'] = $this->invoices->sum('sell_total');
    }

    /**
     * Dépenses
     * @return void
     */
    public function setSpendingAttribute()
    {
        $this->attributes['spending'] = $this->invoices->pluck('project.supplies')->flatten()->unique()->sum('total_supply');
    }

    /**
     * Marge brute
     * @return void
     */
    public function setGrossMarginAttribute()
    {
        $this->attributes['gross_margin'] = ($this->revenue - $this->spending);
    }


    /**
     * Pourcentage de marge brute par rapport au CA
     * @return void
     */
    public function setGrossMarginPercentAttribute()
    {
        $this->attributes['gross_margin_percent'] = ($this->gross_margin * 100 / ($this->revenue || 1) );
    }

}
