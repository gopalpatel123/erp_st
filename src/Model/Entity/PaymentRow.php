<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PaymentRow Entity
 *
 * @property int $id
 * @property int $payment_id
 * @property int $company_id
 * @property string $type
 * @property int $ledger_id
 * @property float $debit
 * @property float $credit
 * @property string $mode_of_payment
 * @property string $cheque_no
 * @property \Cake\I18n\FrozenDate $cheque_date
 *
 * @property \App\Model\Entity\Payment $payment
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\Ledger $ledger
 */
class PaymentRow extends Entity
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
	
	protected $_virtual = [
        'cheque_date'
    ];
                
                protected function _getChequeDate()
    {
                                if(!empty($this->_properties['cheque_no']))
                                {
                                return date('Y-m-d', strtotime($this->_properties['cheque_date']));
                                }
                                else
                                { return "000:00:00";}
    }
}
