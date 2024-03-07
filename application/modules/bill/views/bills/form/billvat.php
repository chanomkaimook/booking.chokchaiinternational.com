<div class="row">
    <div class="form-group col-md-6">
        <span class="required"><i class="mdi mdi-svg"></i></span>
        <label class="text-capitalize">เลขที่ใบกำกับอย่างย่อ</label>
        <input type="text" class="form-control" name="codetext" placeholder="ระบุ" required>
    </div>
    <div class="form-group col-md-6">
        <label class="text-capitalize">จำนวนคนเข้าชม</label>
        <input type="text" class="form-control form-control-sm int_only" name="total_unit" placeholder="หากไม่ระบุจะเป็นจำนวนจากใบเสนอราคา">
    </div>
</div>

<!-- <div class="form-group col-md-6">
        <span class="required"><i class="mdi mdi-svg"></i></span>
        <label class="text-capitalize">วันที่ออกใบ</label>
        <input type="text" class="form-control" name="date_order_show" placeholder="ระบุ" required>
    </div>
    <div class="form-group col-md-6">
        <span class="required"><i class="mdi mdi-svg"></i></span>
        <label class="text-capitalize">ยอดเงินมัดจำ</label>
        <input type="text" class="form-control int_only" name="deposit" placeholder="ระบุ" required>
    </div> -->


<div class="row">
    <div class="form-group col-md-6">
        <label class="text-capitalize">ยอดมัดจำ/ชำระ</label>
        <input type="text" class="form-control int_only" name="deposit" placeholder="ระบุตัวเลข">
    </div>
    <div class="form-group col-md-6">
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
    <div class="form-group col-md-6">
        <span class="required"><i class="mdi mdi-svg"></i></span>
        <label class="text-capitalize">วันโอน/ชำระเงิน</label>
        <input type="text" class="form-control" name="deposit_date" placeholder="ระบุ" required>
    </div>
    <div class="form-group col-md-6">
        <label class="text-capitalize">วันที่ลง POS</label>
        <input type="text" class="form-control" name="pos_date" placeholder="ระบุ">
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        <div class="pl-1">
            <div class="checkbox checkbox-primary">
                <input id="cash" name="cash" type="checkbox">
                <label for="cash">
                    ชำระหน้าฟาร์ม
                </label>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        <label class="text-capitalize">หมายเหตุ</label>
        <textarea class="form-control" name="deposit_remark" cols="30" rows="2"></textarea>
    </div>
    <div class="form-group col-md-12">
        <label class="text-capitalize text-danger">** หากทำการบันทึกการแก้ไข โปรดตรวจสอบใบเสร็จรับเงินด้วย</label>
    </div>
</div>
<script>
    $(document).ready(function() {
        let form_deposit = "#frm_deposit"
        let modal = "#modal_billvat"
        $(document).on('submit', '#frm_deposit', function() {
            let item_id = $(modal).find('[name=frm_deposit_hidden_id]').val()

            let data = $(form_deposit).serializeArray()

            let bill_id = $('#data-bill_id').val();
            let bill_code = $('#data-bill_code').val();
            let bill_booking = $('#data-bill_booking').val();
            if (bill_id) {
                data.push({
                    'name': 'id',
                    'value': bill_id,
                })
                data.push({
                    'name': 'codebill',
                    'value': bill_code,
                })
                data.push({
                    'name': 'booking_date',
                    'value': bill_booking,
                })
            }

            /* let date_order = $('[name=date_order_show]').val();
            if (date_order) {
                set_date_order = date_order.split("/")
                let new_date_order = set_date_order[2] + "-" + set_date_order[1] + "-" + set_date_order[0]

                data.push({
                    'name': 'date_order',
                    'value': new_date_order,
                })
            } */

            let deposit_date = $('[name=deposit_date]').val();
            if (deposit_date) {
                set_deposit_date = deposit_date.split("/")
                let new_deposit_date = set_deposit_date[2] + "-" + set_deposit_date[1] + "-" + set_deposit_date[0]

                data.push({
                    'name': 'deposit_date',
                    'value': new_deposit_date,
                })
            }

            let pos_date = $('[name=pos_date]').val();
            if (pos_date) {
                set_pos_date = pos_date.split("/")
                let new_pos_date = set_pos_date[2] + "-" + set_pos_date[1] + "-" + set_pos_date[0]

                data.push({
                    'name': 'pos_date',
                    'value': new_pos_date,
                })
            }

            let bank = $('#bank').val();
            if (bank) {
                data.push({
                    'name': 'bank_name',
                    'value': $('#bank option:selected').text()
                })
            }



            if (item_id) {
                func = async_update_deposit(item_id, data)
            } else {
                func = async_insert_deposit(data)
            }
            func
                .then((resp) => {
                    if (resp.error == 1) {
                        swalalert('error', resp.txt, {
                            auto: false
                        })
                    } else {
                        Swal.fire({
                            type: 'success',
                            title: 'สำเร็จ',
                            text: resp.txt,
                            timer: swal_autoClose,
                        }).then((result) => {

                            //
                            // document bill
                            get_allbill()

                            modalHide()
                        })
                    }
                })

            return false
        })

        input_int_only()

        function input_int_only() {
            let inputInt = document.querySelectorAll('input.int_only')
            inputInt.forEach(function(item, index) {
                item.addEventListener("keyup", function() {
                    this.value = this.value.replace(/[^0-9.]/g, '');
                })
            })
        }

        //  *
        //  * CRUD
        //  * insert
        //  * 
        //  * insert data 
        //  *
        async function async_insert_deposit(data = []) {
            let url = new URL(path(url_moduleControl + '/insert_deposit'), domain)

            let body = new FormData();
            if (data.length) {
                data.forEach(function(item, index) {
                    body.append(item.name, item.value)
                })
            }

            let method = {
                'method': 'post',
                'body': body
            }
            let response = await fetch(url, method)
            let result = await response.json()

            return result
        }

        //  *
        //  * CRUD
        //  * update
        //  * 
        //  * update data 
        //  *
        async function async_update_deposit(id = null, data = []) {

            let url = new URL(path(url_moduleControl + '/update_deposit'), domain)
            let body = new FormData();
            if (data.length) {
                data.forEach(function(item, index) {
                    body.append(item.name, item.value)
                })

                body.append('item_id', id)
            }

            let method = {
                'method': 'post',
                'body': body
            }
            let response = await fetch(url, method)
            let result = await response.json()

            return result
        }

        //  *
        //  * Modal
        //  * Modal Hide
        //  * 
        //  * call reset form when modal hide
        //  *
        $(modal).on('hidden.bs.modal', function(e) {
            e.preventDefault()

            resetForm()
        })

        //  *
        //  * Modal
        //  * hiding modal
        //  *
        function modalHide() {
            $(modal).modal('hide')

            resetForm()
        }

        function resetForm() {
            let form = document.querySelectorAll("form")

            form.forEach((item, key) => {
                document.getElementsByTagName('form')[key].reset();
            })
        }
    })
</script>