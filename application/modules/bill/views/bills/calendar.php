<div class="content">
    <input type="hidden" id="hidden_task_id">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="section-tool d-flex flex-column flex-md-row justify-content-between">

            <div class="mb-1 mb-md-0">
                <div class="d-flex gap-2">
                    <div class="tool-btn">
                        <button type="button" class="btn-add btn"><?= mb_ucfirst($this->lang->line('_form_btn_add')) ?></button>
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


        <div class="row">

            <div class="col-lg-3">
                <div class="mt-3">
                    <p class="text-muted">รายการที่ยังไม่ลงวันจอง
                        <br>
                        <span class="text-warning">
                            <i class="mdi mdi-checkbox-blank-circle mr-1 vertical-middle"></i>รอโอน
                        </span>
                        <span class="text-success">
                            <i class="mdi mdi-checkbox-blank-circle mr-1 vertical-middle"></i>โอนแล้ว
                        </span>
                    </p>
                </div>
                <div id="external-events" class="order_list">
                </div>
            </div>

            <!-- Begin calendar -->
            <div class="col-lg-9">
                <div id="calendar"></div>
            </div>
            <!-- End calendar -->
        </div>

    </div> <!-- end container-fluid -->

</div> <!-- end content -->

<!-- Modal -->
<?php require_once('component/modal_item.php') ?>
<!-- End Modal -->

<?php require_once('script_calendar.php') ?>