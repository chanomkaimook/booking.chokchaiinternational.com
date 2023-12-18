<div class="row">
    <div class="form-group col-md-3">
        <label class="text-capitalize">รหัสใบเสร็จรับเงิน</label>
        <h5 class="card-text code"></h5>
    </div>
    <div class="form-group col-md-3">
        <label class="text-capitalize">ราคา</label>
        <h5 class="card-text price_novat"></h5>
    </div>
    <div class="form-group col-md-3">
        <label class="text-capitalize">ภาษี</label>
        <h5 class="card-text vat"></h5>
    </div>
    <div class="form-group col-md-3">
        <label class="text-capitalize">ราคารวมภาษี</label>
        <h5 class="card-text net"></h5>
    </div>

    <div class="form-group col-md-6">
        <label class="text-capitalize">เลขที่ใบกำกับอย่างย่อ</label>
        <h5 class="card-text codetext"></h5>
        <input type="text" class="form-control d-none" name="rc_codetext" placeholder="ระบุ" readonly="readonly">
        <button type="button" class="btn btn-success btn-sm btn-update-codetext">ดึงเลขใบกำกับย่อ</button>
    </div>
    <div class="form-group col-md-6">
        <label class="text-capitalize">วันที่ออกใบ</label>
        <h5 class="card-text booking_date"></h5>
        <input type="text" class="form-control" name="date_receipt_show" placeholder="ระบุ" required>
    </div>
    <div class="form-group col-md-12">
        <label class="text-capitalize">หมายเหตุ</label>
        <h5 class="card-text remark_receipt"></h5>
        <textarea class="form-control d-none" name="receipt_remark" cols="30" rows="2"></textarea>
    </div>
    <div class="form-group col-md-6">
        <label class="text-capitalize">ผู้จัดทำล่าสุด</label>
        <h5 class="card-text user_active"></h5>
    </div>
    <div class="form-group col-md-6">
        <label class="text-capitalize">เวลาล่าสุด</label>
        <h5 class="card-text date_active"></h5>
    </div>
</div>
<script>
    $(document).ready(function() {
        let form_receipt = "#frm_receipt"
        let modal = "#modal_receipt"
        $(document).on('submit', form_receipt, function() {
            let item_id = $(modal).find('[name=frm_receipt_hidden_id]').val()
            let data = $(form_receipt).serializeArray()

            let date_order = $('[name=date_receipt_show]').val();
            if (date_order) {
                set_date_order = date_order.split("/")
                let new_date_order = set_date_order[2] + "-" + set_date_order[1] + "-" + set_date_order[0]

                data.push({
                    'name': 'date_order',
                    'value': new_date_order,
                })
            }

            async_update_receipt(item_id, data)
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
                            get_receipt()

                            modalReceiptHide()
                        })
                    }
                })

            return false
        })

        //  *
        //  * CRUD
        //  * update
        //  * 
        //  * update data 
        //  *
        async function async_update_receipt(id = null, data = []) {

            /* console.log(id)
            console.log(data)
            return false */
            let url = new URL(path(url_moduleControl + '/update_receipt'), domain)
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

            resetFormReceipt()
        })

        //  *
        //  * Modal
        //  * hiding modal
        //  *
        function modalReceiptHide() {
            $(modal).modal('hide')

            resetFormReceipt()
        }

        function resetFormReceipt() {
            let form = document.querySelectorAll("form")

            form.forEach((item, key) => {
                document.getElementsByTagName('form')[key].reset();
            })
        }
    })
</script>