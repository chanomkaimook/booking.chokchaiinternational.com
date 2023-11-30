<div class="">
    <div class="card-box">
        <h4 class="header-title mb-3">กรอกข้อมูลเพื่อสร้างใบงานของคุณ</h4>

        <form class="form-horizontal" autocomplete="off" id="form_add">

            <div class="row">
                <div class="form-group col-md-3">
                    <span class="required"><i class="mdi mdi-svg"></i></span>
                    <label for="" class="text-capitalize">ฝ่ายที่แจ้ง</label>
                    <select name="member_section" id="member_section" class="form-control" required>
                        <option value="" disabled selected>ระบุฝ่าย</option>
                        <?php
                        if ($section) :
                            foreach ($section as $row) :
                        ?>
                                <option value="<?= $row->ID; ?>"><?= $row->NAME; ?></option>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <span class="required"><i class="mdi mdi-svg"></i></span>
                    <label for="" class="text-capitalize">ผู้แจ้ง</label>
                    <input type="text" class="form-control" name="member_name" placeholder="ระบุชื่อผู้แจ้ง" value="" required>
                </div>

                <div class="form-group col-md-3">
                    <span class="required"><i class="mdi mdi-svg"></i></span>
                    <label for="" class="text-capitalize">ประเภท</label>
                    <select name="catagory_id" id="catagory_id" class="form-control" required>
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
                    <input type="hidden" name="catagory_name">
                </div>

                <div class="form-group col-md-3">
                    <label for="" class="text-capitalize">อีเมลล์ผู้แจ้ง</label>
                    <input type="email" class="form-control" name="member_email" placeholder="ระบุอีเมลล์">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-12">
                    <span class="required"><i class="mdi mdi-svg"></i></span>
                    <label for="" class="text-capitalize">แจ้งปัญหา</label>
                    <textarea class="form-control" rows="2" name="task" required></textarea>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-12">
                    <label for="" class="text-capitalize">หมายเหตุ</label>
                    <textarea class="form-control" rows="2" name="remark"></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-success waves-effect waves-light">เพิ่มข้อมูล</button>
                </div>
            </div>
        </form>

    </div>
</div>
<script>
    $(document).ready(function(){
        $(document).on('change','[name=catagory_id]',function() {
            let value = $('option:selected',this).text()
            $('[name=catagory_name]').val(value)
        })
    })
</script>