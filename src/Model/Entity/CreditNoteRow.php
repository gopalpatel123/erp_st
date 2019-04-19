<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CreditNoteRow Entity
 *
 * @property int $id
 * @property int $credit_note_id
 * @property int $item_id
 * @property float $quantity
 * @property float $rate
 * @property float $taxable_value
 * @property int $gst_figure_id
 *
 * @property \App\Model\Entity\CreditNote $credit_note
 * @property \App\Model\Entity\Item $item
 * @property \App\Model\Entity\GstFigure $gst_figure
 */
class CreditNoteRow extends Entity
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
