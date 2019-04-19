<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ContraVoucher Entity
 *
 * @property int $id
 * @property int $voucher_no
 * @property string $reference_no
 * @property int $company_id
 * @property \Cake\I18n\FrozenDate $transaction_date
 * @property string $narration
 * @property float $total_credit_amount
 * @property float $total_debit_amount
 *
 * @property \App\Model\Entity\Company $company
 */
class ContraVoucher extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
