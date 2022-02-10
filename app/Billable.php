<?php namespace App;
use Illuminate\Database\Eloquent\Model;

trait Billable {


    /**
     * Taux de tva
     * @var float
     */
    public $vat = 20;


    /**
     * Total TVA
     * @return float|int
     */
    public function getTotalVatAttribute()
    {
        return ($this->vat/100*$this->sell_total);
    }


    /**
     * Total TTC
     * @return mixed
     */
    public function getTotalWithTaxesAttribute()
    {
        return $this->sell_total + $this->total_vat;
    }


    /**
     * Retourne le montant de remise Agefiph
     * @return float|int
     */
    public function computeAgefiph()
    {
        // part du ca utile pour agefiph
        $pc_cau_agefiph = 30;

        // part de notre ca prise en compte
        $coef = 100;

        if ($this->project->project_type == 'goodies') {
            $coef = 70;
        }

        return round((($this->sell_total  * $coef / 100) * $pc_cau_agefiph / 100),2);
    }
}
