<input type="hidden" id="hidden_operator_id" name="hidden_operator_id">
<div class="form-inline flex-fill">
    <div class="form-group w-100">
        <select name="operator" class="form-control form-control-sm" id="item_operator_id">
            <option value="">ทีมงาน</option>
            <?php
            # 
            # sql provide for helpdesk permit
            $text_sql = '';
            if (check_helpdesk()) {
                $this->load->model('mdl_role_focus');

                $myself = $this->session->userdata('user_code');
                $hidden_select_option['where'] = array(
                    'staff_owner'  => $myself,
                );
                $result_helpdesk = $this->mdl_role_focus->get_data(null, $hidden_select_option);
                if ($result_helpdesk) {
                    $array_in = [];

                    foreach ($result_helpdesk as $row) {
                        $array_in[] = $row->STAFF_CHILD;
                    }

                    if (count($array_in)) {
                        $array_in[] = $myself;
                        $text_in = implode(',', $array_in);
                        $text_sql = 'staff.id in(' . $text_in . ')';
                    }
                }
            }

            $sql = $this->db->select('staff.id,concat(employee.name," ",employee.lastname) as staff_name')
                ->from('staff')
                ->join('employee', 'staff.employee_id=employee.id', 'left')
                ->where('staff.status', 1)
                ->where('(staff.roles_level >= 20 and staff.roles_level <= 29 or staff.roles_level = 11)', null, false);

            if ($text_sql) {
                $sql->where($text_sql, null, false);
            }

            $q = $sql->get();
            if ($q->result()) {
                $operator = $q->result();
                foreach ($operator as $row) {
            ?>
                    <option value="<?= $row->id; ?>"><?= $row->staff_name; ?></option>
            <?php
                }
            }
            ?>
        </select>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on('change', '#item_operator_id', function() {
            $('#hidden_operator_id').val($(this).val())
        })
    })
</script>