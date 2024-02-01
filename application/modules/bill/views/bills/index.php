<div class="content">
    <input type="hidden" id="hidden_task_id">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="section-tool d-flex flex-column flex-md-row justify-content-between">

            <div class="mb-1 mb-md-0">
                <div class="d-flex gap-2">
                    <div class="tool-btn">
                        <?php
                        if (check_permit('bill.insert')) :
                        ?>
                            <button type="button" class="btn-add btn"><?= mb_ucfirst($this->lang->line('_form_btn_add')) ?></button>
                        <?php
                        endif;
                        ?>
                    </div>
                </div>
            </div>

            <div class="">
                <?php require_once('application/views/partials/e_filter_base.php'); ?>
            </div>

        </div>
        <style>
            .truncate {
                max-width: 100px;
            }
        </style>
        <div class="">
            <div class="card-box">
                <table id="datatable" class="table table-hover m-0 table-actions-bar dt-responsive dataTable no-footer dtr-inline" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>เลขที่</th>
                            <th>ลูกค้า</th>
                            <th>จำนวน</th>
                            <th>วันเข้าชม</th>
                            <th>ชำระ</th>
                            <th>สถานะ</th>
                            <th>โดย</th>
                            <th>วันล่าสุด</th>
                            <th class="hidden-sm">เพิ่มเติม</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

        <!-- end row -->

    </div> <!-- end container-fluid -->

</div> <!-- end content -->

<!-- Modal -->
<?php require_once('component/modal_item.php') ?>
<!-- End Modal -->
<?php include('script.php') ?>
<script>
    function effect_after_event(string = null) {
        switch (string) {
            case 'update':
                window.location.reload()
                break;
            case 'insert':
                dataReload()
                break;
        }
    }
    $(document).ready(function() {
        getData()

        input_int_only()

        function input_int_only() {
            let inputInt = d.querySelectorAll('input.int_only')
            inputInt.forEach(function(item, index) {
                item.addEventListener("keyup", function() {
                    this.value = this.value.replace(/[^0-9.]/g, '');
                })
            })
        }

        $("[name=bookingdate]").datepicker({
            autoclose: !0,
            todayHighlight: !0,
            dateFormat: 'dd/mm/yy',
        })
        $("[name=date_order]").datepicker({
            autoclose: !0,
            todayHighlight: !0,
            dateFormat: 'dd/mm/yy',
        })
        $('[name=date_order]').datepicker("setDate", new Date());

        $("[name=deposit_date]").datepicker({
            autoclose: !0,
            todayHighlight: !0,
            dateFormat: 'dd/mm/yy',
        })

        $("[name=pos_date]").datepicker({
            autoclose: !0,
            todayHighlight: !0,
            dateFormat: 'dd/mm/yy',
        })

        $(".touchspin").TouchSpin({
            min: 1,
            max: 100,
        })

    })
</script>

<?php include('script_crud.php') ?>
<?php include('script_datatable.php') ?>
<?php include('script_autocustomer.php') ?>