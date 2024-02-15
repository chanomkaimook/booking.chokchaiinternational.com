<input type="hidden" id="hidden_statusbill" name="hidden_statusbill">
<div class="form-inline flex-fill">
    <div class="form-group w-100">

        <label class="d-none d-sm-block"><?= mb_ucfirst($this->lang->line('_fillter_status')) ?></label>
        <select class="form-control form-control-sm" id="item_statusbill">
            <option value="" selected><?= mb_ucfirst($this->lang->line('_fillter_text_all')) ?></option>

            <option value="1">รอ</option>
            <option value="2">กำลัง</option>
            <option value="3">สำเร็จ</option>
            <option value="4">ยกเลิก</option>
        </select>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on('change', '#item_statusbill', function() {
            $('#hidden_statusbill').val($(this).val())
        })
    })
</script>