<?php
$text_task = "Task";
$text_remark = "หมายเหตุ";
$text_problems = "ปัญหาที่พบ";
$text_correction = "แนวทางแก้ไข";
?>
<div id="modal_ticket" class="modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-0 ticket_code"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <!-- Form -->
            <form class="form-horizontal" id="frm_ticket">
                <div class="modal-body">

                    <div class="modal-body-content" style="height:70vh">
                        <div class="color-scroll" style="max-height:70vh">

                            <!-- HTML -->
                            <div class="modal-body-html">
                                <div class="form-group d-flex">
                                    <div class="bg-information px-1"><strong>Technician</strong></div>
                                    <div class="flex-grow-1 bg-light px-1 ticket_technician"></div>
                                    <div class="bg-information px-1"><strong>Part</strong></div>
                                    <div class="flex-grow-1 px-1 bg-light ticket_tech_department"></div>
                                </div>
                                <div class="form-group d-flex">
                                    <div class=""><strong>Status</strong></div>
                                    <div class="flex-grow-1 px-1 ticket_workstatus"></div>
                                    <div class=""><strong>Total</strong></div>
                                    <div class="flex-grow-1 px-1 ticket_totaldays"></div>
                                </div>
                                <div class="form-group d-flex">
                                    <div class=""><strong>Begin</strong></div>
                                    <div class="flex-grow-1 px-1 ticket_begin"></div>
                                    <div class=""><strong>End</strong></div>
                                    <div class="flex-grow-1 px-1 ticket_end"></div>
                                </div>
                                <div class="form-group">
                                    <strong><?= $text_task; ?></strong>
                                    <blockquote class="blockquote">
                                        <p class="mb-0 ticket_task"></p>
                                        <p class="small"><?= $text_remark; ?>-<span class="ticket_remark"></span></p>
                                        <footer class="blockquote-footer text-right">
                                            <span class="ticket_member_name"></span>
                                            <cite title="" class="ticket_member_section"></cite>
                                            <br>
                                            <cite title="" class="small ticket_datestart"></cite>
                                        </footer>
                                    </blockquote>
                                </div>

                                <div class="form-group">
                                    <strong><?= $text_problems; ?></strong>
                                    <blockquote class="blockquote">
                                        <p class="mb-0 ticket_problems"></p>
                                    </blockquote>
                                </div>

                                <div class="form-group">
                                    <strong><?= $text_correction; ?></strong>
                                    <blockquote class="blockquote">
                                        <p class="mb-0 ticket_correction"></p>
                                    </blockquote>
                                </div>
                            </div>


                            <!-- Form input -->
                            <div class="modal-body-form">
                                <?php include __DIR__ . '../../form/add_detail.php' ?>
                            </div>
                            <!-- End form -->

                        </div>
                    </div>



                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">ปิด</button>
                    <button type="button" class="frm_ticket_edit btn btn-warning waves-effect waves-light px-4 d-none">แก้ไข</button>
                    <button type="submit" class="btn btn-success waves-effect waves-light px-4 d-none">บันทึก</button>
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    showHTML()

    function submitUpdateTicket() {

        let modal = $('#modal_ticket')

        let task_id = document.getElementById('hidden_task_id').value
        let task = modal.find('[name=ticket_input_task]').val()
        let remark = modal.find('[name=ticket_input_remark]').val()
        let problems = modal.find('[name=ticket_input_problems]').val()
        let correction = modal.find('[name=ticket_input_correction]').val()

        let data = new FormData()
        data.append('id', task_id)
        data.append('task', task)
        data.append('remark', remark)
        data.append('problems', problems)
        data.append('correction', correction)
        data.append('method', "update")

        return data
    }

    $(document).on('click', '#frm_ticket .frm_ticket_edit', function() {
        showForm()
    })

    $(document).ready(function() {
        $('#modal_ticket').on('hidden.bs.modal', function(e) {
            e.preventDefault()
            resetModalTicket()
        })
    })

    async function get_dataTicket(code = null) {
        let url = new URL(path('ticket/ctl_ticket/get_data', domain))
        url.searchParams.append('code', code);

        let response = await fetch(url)
        let result = response.json()

        return result
    }

    function modal_ticket_show(code = null) {
        let modal = $("#modal_ticket")
        // modal.find('.modal-body-content').addClass('invisible')
        modal.find('.modal-body-content').hide()
        modal.find('.modal-body').prepend(loading)

        footerBtn_show()

        get_dataTicket(code)
            .then((resp) => {
                modal.find('.modal-body .loading').remove()
                modal.find('.modal-body-content').show()

                if (resp) {
                    let item = resp[0]
                    // 
                    // insert data on modal
                    let beginDate = item.BEGIN_DATE ? toThaiDateTimeString(item.BEGIN_DATE, 'dateindent') : ""
                    let endnDate = item.END_DATE ? toThaiDateTimeString(item.END_DATE, 'dateindent') : ""
                    let dateStart = item.DATE_STARTS ? toThaiDateTimeString(item.DATE_STARTS, 'datetimeindent') : ""

                    let total = item.TOTAL ? item.TOTAL + " วัน" : ""

                    // input DOM
                    modal
                        .find('.ticket_technician').text(item.NAME)
                        .end()
                        .find('.ticket_tech_department').text(item.DEPARTMENT_NAME)
                        .end()
                        .find('.ticket_workstatus').html(item.WORKSTATUS_DISPLAY)
                        .end()
                        .find('.ticket_totaldays').text(total)
                        .end()
                        .find('.ticket_begin').text(beginDate)
                        .end()
                        .find('.ticket_end').text(endnDate)
                        .end()
                        .find('.ticket_task').text(item.TASK)
                        .end()
                        .find('.ticket_remark').text(item.REMARK)
                        .end()
                        .find('.ticket_member_name').text(item.MEMBER_NAME)
                        .end()
                        .find('.ticket_member_section').text(item.MEMBER_SECTION)
                        .end()
                        .find('.ticket_datestart').text(dateStart)
                        .end()
                        .find('.ticket_problems').text(item.PROBLEMS)
                        .end()
                        .find('.ticket_correction').text(item.CORRECTION)
                        .end()
                        .find('.ticket_correction').text(item.CORRECTION)
                        .end()

                    // input form
                    modal
                        .find('[name=ticket_input_task]').val(item.TASK)
                        .end()
                        .find('[name=ticket_input_remark]').val(item.REMARK)
                        .end()
                        .find('[name=ticket_input_problems]').val(item.PROBLEMS)
                        .end()
                        .find('[name=ticket_input_correction]').val(item.CORRECTION)
                        .end()

                    if (item.WORKSTATUS_ID != 3) {
                        footerBtn_showEdit()
                    }
                }

            })
    }

    function resetModalTicket() {
        showHTML()
    }

    function showHTML() {
        let modal = $("#modal_ticket")

        modal.find('.modal-body-html').show()
        modal.find('.modal-body-form').hide()

        footerBtn_showEdit()
    }

    function showForm() {
        let modal = $("#modal_ticket")

        modal.find('.modal-body-html').hide()
        modal.find('.modal-body-form').show()

        footerBtn_showSubmit()
    }

    function footerBtn_showEdit() {
        $('#frm_ticket').find('.frm_ticket_edit').removeClass('d-none')
        $('#frm_ticket').find('button[type=submit]').addClass('d-none')
    }

    function footerBtn_showSubmit() {
        $('#frm_ticket').find('.frm_ticket_edit').addClass('d-none')
        $('#frm_ticket').find('button[type=submit]').removeClass('d-none')
    }

    function footerBtn_show() {
        $('#frm_ticket').find('.frm_ticket_edit').addClass('d-none')
        $('#frm_ticket').find('button[type=submit]').addClass('d-none')
    }
</script>