<div class="row">
    <div class="form-group col-md-6">
        <span class="required"><i class="mdi mdi-svg"></i></span>
        <label class="text-capitalize">ชื่อลูกค้า</label>
        <input type="text" class="form-control" name="customer" placeholder="ระบุ" required>
        <input type="hidden" name="customer_id">
    </div>
    <div class="form-group col-md-6">
        <span class="required"><i class="mdi mdi-svg"></i></span>
        <label class="text-capitalize">วันออกบิล</label>
        <input type="text" class="form-control" name="date_order" placeholder="ระบุ" required>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label class="text-capitalize">ชื่อผู้ติดต่อ</label>
        <input type="text" class="form-control" name="agent_name" placeholder="ระบุ">
    </div>
    <div class="form-group col-md-6">
        <label class="text-capitalize">เบอร์ติดต่อ</label>
        <input type="text" class="form-control" name="agent_contact" placeholder="ระบุ">
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        <div class="d-md-flex gap-1">
            <div class="">
                <label class="text-capitalize">ที่อยู่สำหรับออกบิล</label>
            </div>
            <div class="">
                <select id="cus_id" class="form-control form-control-sm" disabled>
                    <option value="" selected>ระบุ</option>
                    <?php
                    if ($address) {
                        foreach ($address as $row) {
                            $address = substr($row->ADDRESS, 0, 11);
                            echo "<option value=\"$row->ADDRESS\" data-cus_id=\"$row->CUSTOMER_ID\">$address</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <textarea class="form-control" name="customer_address" cols="30" rows="2"></textarea>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        <div class="border">
            <div class="p-2">
                <div class="bg-light d-flex justify-content-between px-1">
                    <div class="">
                        <h5 class="">รอบจอง</h5>
                    </div>
                    <div class="pt-1">
                        <button type="button" class="btn btn-outline-success btn-sm btn-add-book">เพิ่มรอบจอง</button>
                    </div>
                </div>

                <div class="card-body p-1">
                    <div class="table-responsive">
                        <table id="list_booking" class="w-100 text-center">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th width="30%">รอบจอง</th>
                                    <th width="30%">วันเข้าชม</th>
                                    <th width="30%">จำนวนคน</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="row section_form_deposit">
    <div class="form-group col-md-3">
        <label class="text-capitalize">ยอดมัดจำ/ชำระ</label>
        <input type="text" class="form-control int_only" name="deposit" placeholder="ระบุตัวเลข">
    </div>
    <div class="form-group col-md-3">
        <label class="text-capitalize">ธนาคารที่โอนเงิน</label>
        <select id="bank" name="bank" class="form-control">
            <option value="" selected>ระบุ</option>
            <?php
            if ($bank) {
                foreach ($bank as $row) {
                    echo "<option value=\"$row->ID\">$row->NAME</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="form-group col-md-3">
        <label class="text-capitalize">วันโอน</label>
        <input type="text" class="form-control" name="deposit_date" placeholder="ระบุ">
    </div>
    <div class="form-group col-md-3">
        <label class="text-capitalize">วันที่ลง POS</label>
        <input type="text" class="form-control" name="pos_date" placeholder="ระบุ">
    </div>
</div>

<div class="row section_item">
    <div class="form-group col-md-12 section_item__detail">
        <div class="row">
            <div class="col-md-4 col-6">
                <h5><small>ราคาเต็ม </small><br><span class="total_price"></span></h5>
            </div>
            <div class="col-md-4 col-6">
                <h5><small>ส่วนลด </small><br><span class="total_discount"></span></h5>
            </div>


            <div class="col-md-4 col-6">
                <h5><small>ยอดชำระ </small><br><span class="total_net"></span></h5>
            </div>
            <div class="col-md-4 col-6">
                <h5><small>ยอดโอน </small><br><span class="total_pay"></span></h5>
            </div>
            <div class="col-md-4 col-6">
                <h5><small>จำนวนคน </small><br><span class="total_unit"></span></h5>
            </div>
            <div class="col-md-4 col-6">
                <h5><small>สถานะ </small><br><span class="status_payment"></span></h5>
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
                        <h5 class="">เลือกตั๋ว</h5>
                    </div>
                    <div class="pt-1">
                        <button type="button" class="btn btn-outline-success btn-sm btn-add-item">เพิ่มรายการ</button>
                    </div>
                </div>

                <div class="card-body p-1">
                    <div class="table-responsive">
                        <table id="list_item" class="w-100 text-center">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th width="50%">รายการ</th>
                                    <th width="12%">จำนวน</th>
                                    <th width="12%">ส่วนลด</th>

                                    <th width="12%">หน่วยละ</th>
                                    <th width="14%">ราคา</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
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
        <textarea class="form-control" name="remark" cols="30" rows="2"></textarea>
    </div>
</div>
<script>
    let table_list_body = ""
    let select = ""
    let select_booking = ""
    let input_total = `<input type="text" name="item_qty" class="form-control form-control-sm int_only" value="1" required>`
    let input_discount = `<input type="text" name="item_discount" class="form-control form-control-sm int_only" value="" >`

    let list = ""

    $(document).ready(function() {
        // select
        if (url_moduleControl != 'bill/event_calendar') {
            fetch_dataItem()
                .then((resp) => {
                    if (resp) {
                        resp.forEach(function(item, index) {
                            select += `<option value="${item.ID}" 
                        data-name="${item.NAME}"
                        data-price="${item.PRICE}">
                        ${item.NAME}
                        </option>`
                        })
                    }
                })

            fetch_dataBooking()
                .then((resp) => {
                    if (resp) {
                        resp.forEach(function(item, index) {
                            select_booking += `<option value="${item.ID}">
                        ${item.NAME}
                        </option>`
                        })
                    }
                })
        }



        table_booking_body = $('table#list_booking tbody')
        table_list_body = $('table#list_item tbody')

        // 
        // Round Booking
        // 
        // add
        $(document).on('click', '.btn-add-book', function() {
            add_html_list_booking()
        })

        // delete
        $(document).on('click', '.btn-del-booking', function() {
            let dataid = $(this).attr('data-row')

            table_booking_body.find('tr[data-row=' + dataid + ']').remove()
        })

        // 
        // Event
        // 
        // add
        $(document).on('click', '.btn-add-item', function() {
            add_html_list_item()
        })

        // delete
        $(document).on('click', '.btn-del-item', function() {
            let dataid = $(this).attr('data-row')

            table_list_body.find('tr[data-row=' + dataid + ']').remove()

            cal_item_list()
        })

        $(document).on('keyup', 'input[name=customer]', function() {
            $('input[name=customer_id]').val('')

            $('select#cus_id').attr('disabled', 'disabled')
            $('select#cus_id option').removeClass('d-none')
            $('select#cus_id').val(null)
        })

        // event for calculate price
        $(document).on('change', 'select[name=item_list]', function() {
            cal_item_list()
        })
        $(document).on('keyup', '#list_item tbody input.int_only', function() {
            cal_item_list()
        })
        $(document).on('keyup', 'input[name=deposit]', function() {
            cal_item_list()
        })
        // 
        // 
        // 

        $(document).on('change', 'select#cus_id', function() {
            let selected = $(this).find('option:selected')

            if (selected.val()) {
                $('[name=customer_address]').val(selected.val())
            } else {
                $('[name=customer_address]').val('')
            }
        })

        // 
        // promotion
        // 
        /* function get_promotion(id = null, item_data = null) {
            if (id && item_data) {
                let url = new URL(path('promotion/ctl_page/get_proitem'), domain)

                let body = new FormData();
                body.append('id', id)
                body.append('item_data', JSON.stringify(item_data))

                let method = {
                    'method': 'post',
                    'body': body,
                }
                fetch(url, method)
                    .then(res => res.json())
                    .then((resp) => {
                        console.log(resp)
                    })
            }

        } */



    })

    async function fetch_dataItem() {
        let url = new URL(path('bill/ctl_item/get_dataDisplay'), domain)

        const response = await fetch(url)
        const result = await response.json()
        return result;
    }
    async function fetch_dataBooking() {
        let url = new URL(path('information/ctl_round/get_dataDisplay'), domain)

        const response = await fetch(url)
        const result = await response.json()
        return result;
    }

    async function get_cartData(deposit = null, item_data = null) {
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

                        $('.total_pay').text(formatMoney(resp.pay))
                        $('.total_price').text(formatMoney(resp.price))
                        $('.total_discount').text(formatMoney(resp.discount))
                        $('.total_net').text(formatMoney(resp.net))
                        $('.total_unit').text(formatMoney(resp.unit, 0))
                        $('.status_payment').text(resp.payment_status)
                    })
            }
        })

    }

    //	format number and float (.00) return string!! 
    function formatMoney(number, decPlaces = 2) {
        // const r = number.toString().replace(/\B(?=(\d{3})+(?!\d))/g,",")
        const convertNumber = Number(number)
        const convertFloat = Math.abs(Number(convertNumber)).toFixed(decPlaces)
        const convertComma = convertFloat.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")

        return convertComma
    }

    function input_int_only() {
        let inputInt = d.querySelectorAll('input.int_only')
        inputInt.forEach(function(item, index) {
            item.addEventListener("keyup", function() {
                if (!this.value) {
                    // this.value = 1
                } else {
                    this.value = this.value.replace(/[^0-9]/g, '');
                }
            })
        })
    }

    function add_html_list_item() {
        let tr = create_html_list_item()
        table_list_body.append(tr)

        input_int_only()
    }

    function create_html_list_item() {
        // identify row
        let number = table_list_body.find('tr:last').attr('data-row')
        if (!number) {
            number = 1
        } else {
            number = parseInt(number) + 1
        }

        let btn = `<button data-row="${number}" type="button" class="btn btn-danger btn-sm btn-del-item"><i class="far fa-trash-alt"></i></button>`

        let item_html

        item = `
                <td>${btn}</td>
                <td class="text-left">
                    <select name="item_list" class="form-control form-control-sm">
                        <option value="" disabled selected >เลือกสินค้า</option>
                        ${select}
                    </select>
                </td>
                <td>${input_total}</td>
                <td>${input_discount}</td>
                <td class="price" ></td>
                <td class="net" ></td>
            `
        item_html = `<tr data-row="${number}">${item}</tr>`

        return item_html
    }

    function add_html_list_booking() {
        let tr = create_html_list_booking()
        table_booking_body.append(tr)

        $(".calendar").datepicker({
            autoclose: !0,
            todayHighlight: !0,
            dateFormat: 'dd/mm/yy', // datepicker for calendar theme
            format: 'dd/mm/yyyy', // new datepicker version
            beforeShow: function(input, inst) {
                setDatepickerPos(input, inst)
            }
        })

        input_int_only()
    }

    function create_html_list_booking() {
        // identify row
        let number = table_booking_body.find('tr:last').attr('data-row')
        if (!number) {
            number = 1
        } else {
            number = parseInt(number) + 1
        }

        let btn = `<button data-row="${number}" type="button" class="btn btn-danger btn-sm btn-del-booking"><i class="far fa-trash-alt"></i></button>`

        let item_html

        item = `
                <td>${btn}</td>
                <td class="text-left">
                    <select name="round[]" class="form-control form-control-sm class-round" required>
                        <option value="" disabled selected >เลือกรอบ</option>
                        ${select_booking}
                    </select>
                </td>
                <td><input type="text" class="calendar form-control form-control-sm class-bookingdate" name="bookingdate_temp[]" placeholder="ระบุ"></td>
                <td><input type="text" class="form-control form-control-sm int_only class-bookingtotal" name="bookingtotal[]" placeholder="ระบุ"></td>
            `
        item_html = `<tr data-row="${number}">${item}</tr>`

        return item_html
    }

    // 
    // calculate price list
    // 
    function cal_item_list() {

        $(function() {
            list = $('#list_item tbody').find('tr')

            if (list.length) {
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
                let deposit = $(modal).find('input[name=deposit]').val()

                $.each(list, function(index, item) {
                    id = $(item).find('select[name=item_list]').val()
                    price = $(item).find('select[name=item_list] option:selected').attr('data-price')
                    total = $(item).find('input[name=item_qty]').val()
                    discount = $(item).find('input[name=item_discount]').val()

                    if (price && total) {
                        totalprice = (price * total) - discount

                        $(item).find('td.price').text(formatMoney(price))
                        $(item).find('td.net').text(formatMoney(totalprice))
                        total_unit = total_unit + parseInt(total)

                        item_data.push({
                            'id': id,
                            'price': price,
                            'total': total,
                            'discount': discount,
                        })
                    }
                })

                //
                // get detail bill price
                if (item_data.length) {
                    get_cartData(deposit, item_data)
                }

            } else {
                // reset_detail()
            }

        })

    }

    // 
    // reset detail bill
    // 
    function reset_detail() {
        $(function() {
            $('.text_promotion').addClass('d-none')
            $('.text_promotion div').empty()

            $('.total_pay').empty()
            $('.total_price').empty()
            $('.total_discount').empty()
            $('.total_net').empty()
            $('.total_unit').empty()
            $('.status_payment').empty()
        })
    }
</script>