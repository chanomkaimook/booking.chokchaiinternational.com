<!-- Modal Add Event -->
<!-- <div class="modal fade none-border" id="modal_task_remark" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add New Eventas</h4>
            </div>
            <div class="modal-body pb-0"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success save-event waves-effect waves-light">Create event</button>
                <button type="button" class="btn btn-danger delete-event waves-effect waves-light" data-dismiss="modal">Delete</button>
            </div>
        </div>
    </div>
</div> -->
<form class="form-horizontal" id="frm_task_remark">
    <div id="modal_task_remark" class="modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mt-0">ลบรายการ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">


                    <div class="form-group row">
                        <div class="col-12">
                            <span class="required"><i class="mdi mdi-svg"></i></span>
                            <label">หมายเหตุ</label>
                            <textarea class="form-control" name="modal_remark" rows="4" placeholder="ระบุหมายเหตุ" required></textarea>
                        </div>
                    </div>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-success waves-effect waves-light px-4">บันทึก</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>