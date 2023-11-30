<script>
    let divScoreCard = '.score_card .card-box'
    let divScoreCardBody = $(divScoreCard).find('div')

    function draw_statusScore(data = []) {
        //
        // input score
        let waites = $(divScoreCard + ' .score_waite')
        waites.find('h1').text(data.waite)
        waites.find('.percent').text(data.waite_percent)

        let doing = $(divScoreCard + ' .score_doing')
        doing.find('h1').text(data.doing)
        doing.find('.percent').text(data.doing_percent)

        let success = $(divScoreCard + ' .score_success')
        success.find('h1').text(data.success)
        success.find('.percent').text(data.success_percent)

        let all = $(divScoreCard + ' .score_all')
        all.find('h1').text(data.all)
        all.find('.percent').text(data.all_percent)

        let defectAll = $(divScoreCard + ' .score_defectall')
        defectAll.find('h1').text(data.defect)
        defectAll.find('.percent').text(data.defect_percent)

        let ticketAVG = $(divScoreCard + ' .score_ticketavg')
        ticketAVG.find('h1').text(data.ticketavg)
        ticketAVG.find('.percent').text(data.ticketavg_percent)

        // clear remove
        cardLoadingRemove(divScoreCard)
        cardToggleDisplay(divScoreCardBody)

        let divHeight = $('.score_card .card-box .text-center').outerHeight() + 11
        $('.chart-container canvas#bar_operator').css('height', divHeight)
    }

</script>