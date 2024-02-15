<input type="hidden" id="hidden_statuspayment" name="hidden_statuspayment">
<div class="form-inline flex-fill">
    <div class="form-group w-100">

        <label class="d-none d-sm-block">ชำระ</label>
        <select class="form-control form-control-sm" id="item_statuspayment">
            <option value="" selected><?= mb_ucfirst($this->lang->line('_fillter_text_all')) ?></option>

            <option value="6">รอโอน</option>
            <option value="7">มัดจำ</option>
            <option value="8">โอนครบ</option>
        </select>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on('change', '#item_statuspayment', function() {
            $('#hidden_statuspayment').val($(this).val())
        })
    })
</script>