<script>
    $(document).ready(function() {
        const d = document
        let frm = document.getElementById('form_add')
        let modal_name = $("#add-category")
        let modal_body = modal_name.find('.modal-body')

        // const selectRound = d.querySelectorAll('select.selectpicker')
        // frm.reset();
        // $(".selectpicker").selectpicker("refresh");

        //  =========================
        //  Get Started
        //  =========================

        // reset form
        $('.modal').on('hidden.bs.modal', function(e) {
            e.preventDefault()

            resetForm()
        })
    })

    //  =========================
    //  Function
    //  =========================

    //
    // reset
    function resetForm() {
        let form = document.querySelectorAll("form")

        form.forEach((item, key) => {
            document.getElementsByTagName('form')[key].reset();
        })
    }

    //  =========================
    //  Modal
    //  =========================

    // 
    //  Modal
    function modalHide() {
        $('.modal').modal('hide')
    }

</script>