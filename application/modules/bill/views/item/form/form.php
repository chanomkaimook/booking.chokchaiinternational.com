<div class="row">
    <div class="form-group col-md-8">
        <span class="required"><i class="mdi mdi-svg"></i></span>
        <label class="text-capitalize">ชื่อ</label>
        <input type="text" class="form-control" name="item_name" placeholder="ระบุ" value="" required>
    </div>
    <div class="form-group col-md-4">
    <span class="required"><i class="mdi mdi-svg"></i></span>
        <label class="text-capitalize">รหัสสินค้า</label>
        <input type="text" class="form-control" name="code" placeholder="ระบุ" value="" required>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label class="text-capitalize">ชื่อตั๋ว</label>
        <select id="ticket" name="ticket" class="form-control">
            <option value="" disabled selected>ระบุ</option>
            <?php
            if ($ticket) {
                foreach ($ticket as $row) {

                    echo "<option value=\"$row->ID\">$row->NAME</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="form-group col-md-4">
        <label class="text-capitalize">รุ่น</label>
        <select id="division" name="division" class="form-control">
            <option value="" disabled selected>ระบุ</option>
            <?php
            if ($division) {
                foreach ($division as $row) {

                    echo "<option value=\"$row->ID\">$row->NAME</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="form-group col-md-4">
        <label class="text-capitalize">ราคาต่อหน่วย</label>
        <input type="text" class="form-control int_only" name="price" placeholder="ระบุ" value="">
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label class="text-capitalize">หมายเหตุ</label>
        <textarea class="form-control" name="remark" cols="30" rows="2"></textarea>
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