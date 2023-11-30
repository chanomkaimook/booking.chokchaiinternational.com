<div class="row">
    <div class="form-group col-md-12">
        <span class="required"><i class="mdi mdi-svg"></i></span>
        <label class="text-capitalize"><?= $text_task; ?></label>
        <select name="ticket_catagory_id" id="ticket_catagory_id" class="form-control" required>
            <option value="" disabled selected>ระบุประเภท</option>
            <?php
            if ($catagory) :
                foreach ($catagory as $row) :
            ?>
                    <option value="<?= $row->ID; ?>"><?= $row->NAME; ?></option>
            <?php
                endforeach;
            endif;
            ?>
        </select>
        <input type="hidden" name="ticket_catagory_name">
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <span class="required"><i class="mdi mdi-svg"></i></span>
        <label class="text-capitalize"><?= $text_task; ?></label>
        <textarea class="form-control" rows="2" name="ticket_input_task" required></textarea>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label class="text-capitalize"><?= $text_remark; ?></label>
        <textarea class="form-control" rows="2" name="ticket_input_remark"></textarea>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label for="" class="text-capitalize"><?= $text_problems; ?></label>
        <textarea class="form-control" rows="2" name="ticket_input_problems"></textarea>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label class="text-capitalize"><?= $text_correction; ?></label>
        <textarea class="form-control" rows="2" name="ticket_input_correction"></textarea>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(document).on('change','[name=ticket_catagory_id]',function() {
            let value = $('option:selected',this).text()
            $('[name=ticket_catagory_name]').val(value)
        })
    })
</script>