<div class="row">
    <div class="form-group col-md-6">
        <span class="required"><i class="mdi mdi-svg"></i></span>
        <label class="text-capitalize">ชื่อลูกค้า</label>
        <h5 class="card-text customer"></h5>
    </div>
    <div class="form-group col-md-6">
        <span class="required"><i class="mdi mdi-svg"></i></span>
        <label class="text-capitalize">วันออกบิล</label>
        <h5 class="card-text date_order"></h5>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label class="text-capitalize">ชื่อผู้ติดต่อ</label>
        <h5 class="card-text agent_name"></h5>
    </div>
    <div class="form-group col-md-6">
        <label class="text-capitalize">เบอร์ติดต่อ</label>
        <h5 class="card-text agent_contact"></h5>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        <label class="text-capitalize">ที่อยู่สำหรับออกบิล</label>
        <h5 class="card-text customer_address"></h5>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label class="text-capitalize">รอบจอง</label>
        <h5 class="card-text round"></h5>
    </div>
    <!-- <div class="form-group col-md-4">
        <label class="text-capitalize">จำนวนคน</label>
        <input type="text" id="demo3" name="demo3" class="touchspin int_only">
    </div> -->

    <div class="form-group col-md-6">
        <label class="text-capitalize">วันจองเข้าชม</label>
        <h5 class="card-text bookingdate"></h5>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-3">
        <label class="text-capitalize">ยอดเงินมัดจำ</label>
        <h5 class="card-text deposit"></h5>
    </div>
    <div class="form-group col-md-3">
        <label class="text-capitalize">ธนาคารที่โอนเงิน</label>
        <h5 class="card-text bank"></h5>
    </div>
    <div class="form-group col-md-3">
        <label class="text-capitalize">วันโอน</label>
        <h5 class="card-text deposit_date"></h5>
    </div>
    <div class="form-group col-md-3">
        <label class="text-capitalize">วันที่ลง POS</label>
        <h5 class="card-text pos_date"></h5>
    </div>
</div>

<div class="row section_item">
    <div class="form-group col-md-12">
        <div class="row">
            <div class="col-md-4 col-6">
                <h5><small>ราคาเต็ม </small><br><span class="total_price_view"></span></h5>
            </div>
            <div class="col-md-4 col-6">
                <h5><small>ส่วนลด </small><br><span class="total_discount_view"></span></h5>
            </div>


            <div class="col-md-4 col-6">
                <h5><small>ยอดชำระ </small><br><span class="total_net_view"></span></h5>
            </div>
            <div class="col-md-4 col-6">
                <h5><small>ยอดโอน </small><br><span class="total_pay_view"></span></h5>
            </div>
            <div class="col-md-4 col-6">
                <h5><small>จำนวนคน </small><br><span class="total_unit_view"></span></h5>
            </div>
            <div class="col-md-4 col-6">
                <h5><small>สถานะ </small><br><span class="status_payment_view"></span></h5>
            </div>
        </div>
    </div>
    <div class="text_promotion w-100 d-none">
        <div class="form-group col-md-12">
        </div>
    </div>
    <div class="form-group col-md-12">
        <div class="border">
            <div class="p-2">
                <div class="bg-light d-flex justify-content-between px-1">
                    <div class="">
                        <h5 class="">ตั๋ว</h5>
                    </div>
                </div>

                <div class="card-body p-1">
                    <div class="table-responsive">
                        <table id="list_item_view" class="w-100 text-center">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th width="50%">รายการ</th>
                                    <th width="15%">จำนวน</th>
                                    <th width="15%">หน่วยละ</th>
                                    <th width="15%">ราคา</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        <label class="text-capitalize">หมายเหตุ</label>
        <h5 class="card-text remark"></h5>
    </div>
</div>
<script>
    let table_list_body_view = ""
    let select_view = ""
    let list_view = ""

    $(document).ready(function() {
        table_list_body_view = $('table#list_item_view tbody')

    })

    async function get_cartData_view(deposit = null, item_data = null) {
        $(function() {
            if (item_data) {
                let url = new URL(path('bill/ctl_bill/get_cartData'), domain)

                let body = new FormData();
                body.append('pay', deposit)
                body.append('item_data', JSON.stringify(item_data))

                let method = {
                    'method': 'post',
                    'body': body,
                }
                fetch(url, method)
                    .then(res => res.json())
                    .then((resp) => {

                        if (resp.promotion && resp.promotion.length) {
                            let text_promotion = $('.text_promotion')
                            text_promotion.removeClass('d-none')

                            step_pro()
                            async function step_pro() {

                                let p = ''
                                await new Promise((resolve, reject) => {
                                    resolve(
                                        $.each(resp.promotion, function(index, item) {
                                            if (item.ID != null) {
                                                p += `<div class="">
                                                <h6 class="text-info">${item.NAME} - ${formatMoney(item.DISCOUNT,0)}/คน 
                                                ราคา ${formatMoney(item.TOTAL_DISCOUNT,0)} 
                                                (ทั้งหมด ${item.TOTAL_UNIT} ท่าน)</h6>
                                                </div>`
                                            }
                                        }),
                                    )

                                })
                                await new Promise((resolve, reject) => {
                                    resolve(
                                        text_promotion.find('div').html(p)
                                    )
                                })
                            }
                        } else {
                            $('.text_promotion').addClass('d-none')
                            $('.text_promotion div').empty()
                        }

                        $('.total_pay_view').text(formatMoney(resp.pay))
                        $('.total_price_view').text(formatMoney(resp.price))
                        $('.total_discount_view').text(formatMoney(resp.discount))
                        $('.total_net_view').text(formatMoney(resp.net))
                        $('.total_unit_view').text(resp.unit)
                        $('.status_payment_view').text(resp.payment_status)
                    })
            }
        })

    }

    //	format number and float (.00) return string!! 
    function formatMoney(number, decPlaces, decSep, thouSep) {
        decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
            decSep = typeof decSep === "undefined" ? "." : decSep;
        thouSep = typeof thouSep === "undefined" ? "," : thouSep;
        var sign = number < 0 ? "-" : "";
        var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
        var j = (j = i.length) > 3 ? j % 3 : 0;

        return sign +
            (j ? i.substr(0, j) + thouSep : "") +
            i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
            (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
    }

    // 
    // calculate price list
    // 
    function cal_item_list_view() {
        $(function() {
            list_view = table_list_body_view.find('tr')

            if (list_view.length) {
                let price
                let total
                let total_unit = 0
                let net = 0.00

                let totalprice = 0.00
                let total_price = 0.00
                let totaldiscount = 0.00
                let total_discount = 0.00
                let discount = 0.00

                let item_data = []
                let deposit = $("#modal_view").find('.total_pay_view').text()

                $.each(list_view, function(index, item) {
                    price = $(item).find('.td_item_price_unit').text()
                    total = $(item).find('.td_item_qty').text()

                    if (price && total) {
                        totalprice = price * total

                        item_data.push({
                            'price': price,
                            'total': total,
                        })
                    }
                })

                //
                // get detail bill price
                if (item_data.length) {
                    get_cartData_view(deposit, item_data)
                }

            } else {

                // reset_detail()
            }

        })

        function formatMoney(number, decPlaces, decSep, thouSep) {
            decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
                decSep = typeof decSep === "undefined" ? "." : decSep;
            thouSep = typeof thouSep === "undefined" ? "," : thouSep;
            var sign = number < 0 ? "-" : "";
            var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
            var j = (j = i.length) > 3 ? j % 3 : 0;

            return sign +
                (j ? i.substr(0, j) + thouSep : "") +
                i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
                (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
        }
    }
</script>