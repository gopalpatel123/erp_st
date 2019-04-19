<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StockJournal Entity
 *
 * @property int $id
 * @property int $voucher_no
 * @property int $company_id
 * @property \Cake\I18n\FrozenDate $transaction_date
 * @property int $reference_no
 * @property string $narration
 *
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\Inward[] $inwards
 * @property \App\Model\Entity\Outward[] $outwards
 */
class StockJournal extends Entity
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
