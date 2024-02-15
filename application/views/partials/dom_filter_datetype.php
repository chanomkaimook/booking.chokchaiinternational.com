<input type="hidden" id="hidden_datetype" name="hidden_datetype" value="booking_date">
<div class="form-inline flex-fill">
    <div class="form-group w-100">

        <label class="d-none d-sm-block">แบบวัน</label>
        <select class="form-control form-control-sm" id="item_datetype">
            <option value="booking_date" selected>วันจองเข้าชม</option>
            <option value="date_order">วันออกบิล</option>
            <option value="date_starts">วันสร้าง</option>
            <option value="date_update">วันอัพเดตล่าสุด</option>
        </select>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on('change', '#item_datetype', function() {
            $('#hidden_datetype').val($(this).val())
        })
    })
</script>