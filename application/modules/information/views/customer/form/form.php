<div class="row">
    <div class="form-group col-md-12">
        <span class="required"><i class="mdi mdi-svg"></i></span>
        <label class="text-capitalize">ชื่อ</label>
        <input type="text" class="form-control" name="item_name" placeholder="ระบุ" value="" required>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label class="text-capitalize">สถานะ</label>
        <select name="status_offview" class="form-control">
            <?php
            echo html_status_offview();
            ?>
        </select>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label class="text-capitalize">ประวัติที่อยู่</label>
        <ul class="list-group from_cus_address">
        </ul>

    </div>
</div>