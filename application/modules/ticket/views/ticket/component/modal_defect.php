<div id="modal_defect" class="modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-0">ข้อบกพร่อง</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">


                <div class="form-group row">
                    <div class="col-12">
                        <label>สาเหตุ</label>
                        <p class="card-title remark">ไม่ระบุ</p>
                        <p class="blockquote-footer text-right">
                            <span class="lead user_active">พี่แมว</span>
                            <br>
                            <cite title="" class="small date_active">6 มิ.ย. 2566 14:52:46 น.</cite>
                        </p>
                    </div>
                </div>


            </div>

            <div class="modal-footer">
                <?php if(check_supervisor()){ ?>
                <button type="button" class="btn btn-danger waves-effect btn_del">ลบ</button>
                <?php } ?>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">ปิด</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    $(document).ready(function() {


    })
</script>