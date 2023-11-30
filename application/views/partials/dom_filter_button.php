<div class="filter-button px-2 px-sm-0 rounded">
    <button class="btn px-1 py-0 btn-primarysoft button_search btn-block btn-lg h-100 mb-2 mb-sm-0" type="button">
        <i class="fas fa-search"></i>
    </button>
</div>
<style>
    @media only screen and (min-width: 768px) {

        #accordion input,
        #accordion select {
            width: 6rem !important;
        }
    }

    #accordion .form-group {
        margin: 0
    }
</style>
<script>
    $('#datatable_filter label input').attr ('id', 'search');

    if ($('.filter-button').length) {
        let ele = $('.filter-button')

        // ele.addClass('btn-primarysoft')
    }

    if ($('.filter').length) {
        let ele = $('.filter')

        // create html width tool button
        ele.addClass('d-flex')
        // ele.addClass('w-100')
        ele.addClass('ml-md-auto')
        // ele.addClass('btn-primarysoft')
        ele.addClass('rounded')
        ele.addClass('px-2')
        ele.addClass('p-1')
    }
    if ($('.filter-add').length) {
        let eleadd = $('.filter-add')

        // create html width tool button
        eleadd.addClass('d-flex')
        eleadd.addClass('flex-fill')
        // eleadd.addClass('w-100')
        // eleadd.addClass('ml-auto')
        // eleadd.addClass('btn-primarysoft')
        eleadd.addClass('rounded')
        eleadd.addClass('px-2')
        eleadd.addClass('p-sm-1')
        eleadd.addClass('pb-1')

        // eleadd.addClass('pt-0')
        // eleadd.addClass('pb-1')
    }
</script>