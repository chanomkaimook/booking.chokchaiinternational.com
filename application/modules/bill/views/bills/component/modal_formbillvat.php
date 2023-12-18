<div id="modal_billvat" class="modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <style>
        @media print {

            .modal-header button,
            .modal-footer {
                display: none;
            }

            body {
                font-size: large;
            }

            body p {
                font-size: 24px;
            }
        }
    </style>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Form -->
            <form class="form-horizontal" autocomplete="off" id="frm_deposit">
                <input type="hidden" name="frm_deposit_hidden_id">
                <div class="modal-header">
                    <h4 class="modal-title mt-0 modal_text_header truncate"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                    <div>
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">ปิด</button>
                        <button type="button" class="btn-del btn btn-danger waves-effect waves-light">ลบ</button>
                        <button type="button" class="btn-edit btn btn-warning waves-effect waves-light px-4">แก้ไข</button>
                        <button type="submit" class="btn btn-success waves-effect waves-light px-4">บันทึก</button>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="modal-body-content" style="height:70vh">
                        <div class="color-scroll" style="max-height:70vh">
                            <!-- HTML -->
                            <div class="modal-body-view">
                                <?php include __DIR__ . '../../form/billvat_view.php' ?>
                            </div>
                            <!-- End HTML -->
                            <!-- Form -->
                            <div class="modal-body-form">
                                <?php include __DIR__ . '../../form/billvat.php' ?>
                            </div>
                            <!-- End Form -->
                        </div>
                    </div>
                </div>
            </form>
            <!-- End Form -->

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->