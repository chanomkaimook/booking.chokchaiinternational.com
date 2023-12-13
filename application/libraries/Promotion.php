<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Promotion
{

    private $tb;

    public function __construct()
    {
        //=	 call database	=//
        $this->ci = &get_instance();
        $this->ci->load->database();
        //===================//
    }


    /**
     * get item promotion
     *
     * @param integer|null $id = item id
     * @param array|null $item_data = [id,price,total]
     * @return void
     */
    function get_itemPromotion(int $item_id = null,$item_data = [])
    {
        if ($item_id && $item_data) {

            $result = array(
                'id' => $item_id,
                'type' => '',
                'discount' => ''
            );

            if ($item_data && is_string($item_data)) {
                $item_data = json_decode($item_data);
            }

            // split item data
            $item_data_id = $item_data->id;
            $item_data_price = $item_data->price;
            $item_data_total = $item_data->total;

            $this->ci->load->model('mdl_promotion');
            $optional['where'] = array(
                'item_id'   => $item_id,
            );
            $q = $this->ci->mdl_promotion->get_data(null, $optional);

            if ($q) {
                $next = 1;
                $condition_pass = false;    // value for check condition
                foreach ($q as $row) {
                    if ($condition_pass == false) {
                        $next = 1;


                        //
                        // condition first
                        $total_fist = $row->TOTAL_FIRST;
                        if ($total_fist && $next == 1) {
                            if ($item_data_total >= $total_fist) {
                                $condition_pass = true;
                            } else {
                                $next = 0;
                            }
                        }

                        //
                        // condition second
                        $total_second = $row->TOTAL_SECOND;
                        if ($total_second && $next == 1) {
                            if ($item_data_total <= $total_second) {
                                $condition_pass = true;
                            } else {
                                $next = 0;
                            }
                        }

                        // discount
                        if ($next == 1 && $condition_pass == true) {
                            $next = 0; // no next loop
                            $result = array(
                                'id' => $row->ITEM_ID,
                                'type' => $row->UNIT,
                                'discount' => $row->DISCOUNT,
                                'promotion' => $row
                            );
                        }
                    }
                }
            }
            return $result;
        }
    }
}
