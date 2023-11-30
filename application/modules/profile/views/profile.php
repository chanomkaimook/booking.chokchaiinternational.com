<?php

$staff_name = $data['NAME'] . ' ' . $data['LASTNAME'];
$staff_section = $data['SECTION'];
$staff_department = $data['DEPARTMENT'];
$staff_position = $data['POSITION'];
$staff_level = $data['LEVEL_NAME'];
$staff_id = $data['ID'];
$staff_email = textShow($data['EMAIL'],"-");
$head_name = "";

?>
<div class="content">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="section-tool d-flex gap-2">
            <button class="btn btn-primary btn_edit">แก้ไข</button>
        </div>

        <div class="row">
            <div class="col">
                <div class="card-box">
                    <div class="member-card-alt">
                        <div class="avatar-xxl member-thumb mb-2 float-left">
                            <img src="<?= base_url('asset/image/avatars/avatar12_big@2x.png');?>" class="img-thumbnail" alt="profile-image">
                            <i class="mdi mdi-star-circle member-star text-success" title="verified user"></i>
                        </div>

                        <div class="member-card-alt-info">
                            <h4 class="mb-1 mt-0"><?= $staff_name; ?> <span class="small">(<?= $staff_position; ?>)</span></h4>
                            <p class="text-muted"><?= $staff_department; ?> <span> | </span> <span><?= $staff_section; ?></span></p>

                            <dl class="row mb-0">
                                <dt class="col-sm-2">System ID</dt>
                                <dd class="col-sm-10"><?= $staff_id; ?></dd>
                                <dt class="col-sm-2">อีเมลล์</dt>
                                <dd class="col-sm-10"><?= $staff_email; ?></dd>
                                <dt class="col-sm-2">หัวหน้า</dt>
                                <dd class="col-sm-10"><?= $head_name; ?></dd>
                            </dl>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
    <!-- end col -->
</div>
<!-- end row -->

<!-- Modal -->
<div class="modal fade" id="edit_profile_modal" tabindex="-1" role="dialog" aria-labelledby="edit_profile_modal_Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit_profile_modal_Label">ระดับผู้อนุมัติ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">ชื่อผู้ใช้: <?php echo $this->session->userdata('user_name') ?></h4>
                            <form class="form-horizontal" autocomplete="off" id="update_data_owner">
                                <input type="hidden" id="user-id" value="<?php echo $this->session->userdata('user_code') ?>">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <label for="">ผู้อนุมัติ1</label>
                                        <select name="owner1" id="owner1" class="form-control select_owner">
                                            <option value="" selected>เลือกผู้อนุมัติ</option>
                                            <?php
                                            if ($sql_owner) {
                                                foreach ($sql_owner as $row) {

                                                    $selected = "";
                                                    if ($data['OWNER1'] == $row->ID) {
                                                        $selected = "selected";
                                                    }
                                                    echo "<option value='$row->ID' $selected >$row->NAME</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12">
                                        <label for="">ผู้อนุมัติ2</label>
                                        <select name="owner2" id="owner2" class="form-control select_owner">
                                            <option value="" selected>เลือกผู้อนุมัติ</option>
                                            <?php
                                            if ($sql_owner) {
                                                foreach ($sql_owner as $row) {
                                                    $selected = "";
                                                    if ($data['OWNER2'] == $row->ID) {
                                                        $selected = "selected";
                                                    }
                                                    echo "<option value='$row->ID' $selected>$row->NAME</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12">
                                        <label for="">ผู้อนุมัติ3</label>
                                        <select name="owner3" id="owner3" class="form-control select_owner">
                                            <option value="" selected>เลือกผู้อนุมัติ</option>
                                            <?php
                                            if ($sql_owner) {
                                                foreach ($sql_owner as $row) {
                                                    $selected = "";
                                                    if ($data['OWNER3'] == $row->ID) {
                                                        $selected = "selected";
                                                    }
                                                    echo "<option value='$row->ID' $selected>$row->NAME</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        $(document).on('change', '#update_data_owner', function() {

            let url_update_profile = new URL('profile/ctl_profile/update_profile', domain);

            var data = new FormData();
            data.append('id', $("#user-id").val());
            data.append('owner1', $("#owner1").val());
            data.append('owner2', $("#owner2").val());
            data.append('owner3', $("#owner3").val());

            let option = {
                method: 'POST',
                body: data,
            }
            fetch(url_update_profile, option)
                .then(res => res.json())
                .then((resp) => {
                    console.log(resp);
                    return false;
                })
        })

    })
</script>