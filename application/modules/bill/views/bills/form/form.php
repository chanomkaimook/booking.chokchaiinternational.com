<div class="row">
    <div class="form-group col-md-12">
        <span class="required"><i class="mdi mdi-svg"></i></span>
        <label class="text-capitalize">ชื่อลูกค้า</label>
        <input type="text" class="form-control" name="customer" placeholder="ระบุ" required>
        <input type="hidden" name="customer_id">
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
    <div class="form-group col-md-4">
        <label class="text-capitalize">รอบจอง</label>
        <select id="round" name="round" class="form-control">
            <option value="" disabled selected>ระบุ</option>
            <?php
            if ($round) {
                foreach ($round as $row) {
                    echo "<option value=\"$row->ID\">$row->NAME</option>";
                }
            }
            ?>
        </select>
    </div>
    <!-- <div class="form-group col-md-4">
        <label class="text-capitalize">จำนวนคน</label>
        <input type="text" id="demo3" name="demo3" class="touchspin int_only">
    </div> -->

    <div class="form-group col-md-4">
        <label class="text-capitalize">วันจองเข้าชม</label>
        <input type="text" class="form-control" name="bookingdate" placeholder="ระบุ">
    </div>
    <div class="form-group col-md-4">
        <label class="text-capitalize">ยอดเงินโอน</label>
        <input type="text" class="form-control int_only" name="deposit" placeholder="ระบุตัวเลข">
    </div>
</div>

<div class="row section_item">
    <div class="form-group col-md-12">
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

            <input type="hidden" name="item_net">
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
                                    <th width="15%">จำนวน</th>
                                    <th width="15%">หน่วยละ</th>
                                    <th width="15%">ราคา</th>
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
    $(document).ready(function() {
        let select
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

        let input_total = `<input type="text" name="item_qty" class="form-control form-control-sm int_only" value="1">`

        let table_list_body = $('table#list_item tbody')

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
        })

        // event for calculate price
        $(document).on('change', 'select[name=item_list]', function() {
            cal_item_list()
        })
        $(document).on('keyup', '#list_item tbody input.int_only', function() {
            cal_item_list()
        })
        $(document).on('keyup', 'input[name=deposit]', function() {
            $('.status_payment').text(cal_status_payment())
            // set show detail price
            $('.total_pay').text(formatMoney($(this).val()))
        })
        // 
        // 
        // 

        // 
        // calculate price list
        // 
        function cal_item_list() {
            let price
            let total
            let total_unit = 0
            let total_discount = 0.00
            let net = 0.00

            let list = $('#list_item tbody').find('tr')

            if (list.length) {
                let totalprice = 0.00
                $.each(list, function(index, item) {
                    price = $(item).find('select[name=item_list] option:selected').attr('data-price')
                    total = $(item).find('input[name=item_qty]').val()

                    if (price && total) {
                        totalprice = price * total
                        total_price = price * total

                        $(item).find('td.price').text(price)
                        $(item).find('td.net').text(formatMoney(totalprice))

                        // promotion price value
                        total_discount = (price - 10) * total

                        // value for set show detail price
                        net = net + totalprice
                        total_unit = total_unit + parseInt(total)
                    }
                })

                // input
                $('input[name=item_net]').val(net)

                // set show detail price
                $('.total_price').text(formatMoney(total_price))
                $('.total_discount').text(formatMoney(total_discount))
                $('.total_net').text(formatMoney(net))
                $('.total_unit').text(parseInt(total_unit))
                $('.status_payment').text(cal_status_payment(net))
            }
        }

        // 
        // calculate price list
        // 
        function cal_status_payment(net = null) {
            let result = ''

            let total_pay = $('input[name=deposit]').val() ? $('input[name=deposit]').val() : 0
            let list = $('#list_item tbody').find('tr')

            if (list.length) {
                if ($('input[name=deposit]').val() > 0) {
                    result = 'มัดจำ'
                } else {
                    result = 'รอโอน'
                }

                if (!net) {
                    if (list.length) {
                        let totalprice = 0.00
                        $.each(list, function(index, item) {
                            price = $(item).find('select[name=item_list] option:selected').attr('data-price')
                            total = $(item).find('input[name=item_qty]').val()

                            if (price && total) {
                                totalprice = price * total

                                net = net + totalprice
                            }
                        })
                    }
                }

                if (total_pay >= net) {
                    result = 'โอนเต็ม'
                }
            }
            return result
        }

        function add_html_list_item() {
            let tr = create_html_list_item()
            table_list_body.append(tr)

            input_int_only()

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
                <td class="price" ></td>
                <td class="net" ></td>
            `
            item_html = `<tr data-row="${number}">${item}</tr>`

            return item_html
        }

        async function fetch_dataItem() {
            let url = new URL(path('bill/ctl_item/get_dataDisplay'), domain)
            const response = await fetch(url)
            const result = await response.json()
            return result;
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
    })
</script>