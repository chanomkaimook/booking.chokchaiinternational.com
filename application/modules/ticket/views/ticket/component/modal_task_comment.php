<form class="form-horizontal" id="frm_task_comment">
    <div id="modal_task_comment" class="modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mt-0">ความคิดเห็น</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">

                    <div class="row section_timeline d-none">
                        <div class="col-12">

                            <div class="timeline timeline-left">
                                <style>
                                    .timeline-icon i {
                                        /* left:0 !important; */
                                        margin-top: 0px !important;
                                        /* transform: translate(-50%, 0) !important; */
                                    }
                                </style>


                            </div> <!-- end timeline -->

                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="form-group row">
                        <div class="col-12">
                            <span class="required"><i class="mdi mdi-svg"></i></span>
                            <label>ความคิดเห็น</label>
                            <textarea class="form-control" name="modal_comment" rows="4" placeholder="ระบุสาเหตุ" required></textarea>
                        </div>
                    </div>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-success waves-effect waves-light px-4">บันทึก</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>
<script>
    async function get_commentdata(code = null) {
        let url = new URL(path('ticket/ctl_ticket/get_commentdata', domain))
        url.searchParams.append('code', code);

        let response = await fetch(url)
        let result = response.json()

        return result
    }

    function modal_comment_show(code = null) {

        if (code) {
            let html = ''
            get_commentdata(code)
                .then((resp) => {

                    resp.forEach(function(item, index) {
                        html += dom_timelineBlock(item)

                    })
                })
                .then(() => {
                    if(html){
                        $('.section_timeline').removeClass('d-none')
                        $('.timeline').html(html)
                    }
                })
        }
    }

    /**
     * create DOM comment
     * 
     * @param array data = {key:value}
     */
    function dom_timelineBlock(data = null) {

        let alertClass = ''
        let bgClass = 'bg-primary'
        let textName = 'text-muted'
        if($('#hidden_user_id').val() == data.STAFF_ID){
            bgClass = 'bg-success'
            textName = 'text-success'
            alertClass = 'alert alert-success'
        }

        let html = ''

        html += '<article class="timeline-item">';
        html += '<div class="timeline-desk">';
        html += '<div class="panel">';
        html += `<div class="timeline-box ${alertClass}">`;

        html += '<span class="arrow"></span>';
        html += `<span class="timeline-icon ${bgClass}"><i class="mdi mdi-chat text-white"></i></span>`;
        html += `<h4 class="${textName}">${data.USERNAME}</h4>`;
        html += `<p class="timeline-date ${textName}"><small>${data.TEXTIME}</small></p>`;
        html += `<p class="${textName}">${data.TASK}</p>`;

        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</article>';

        return html
    }
</script>