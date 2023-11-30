<script>
    let scoreOperator
    let scoreOperatorChart = document.getElementById('bar_operator')

    let divScoreOperator = '.score_graph .chart-container'
    let divScoreOperatorBody = $(divScoreOperator).find('canvas')

    function draw_operatorScore(data = []) {

        if (data) {
            
            let arrayOperator = data.map(function(item, index) {
                return item.name
            })
            let arrayData = data.map(function(item, index) {
                return parseInt(item.success)
            })
            //
            // config barchart
            const operatorChart_data = {
                labels: arrayOperator,

                datasets: [{
                    label: 'จำนวนงาน',
                    data: arrayData,
                    borderColor: 'rgb(255, 159, 64)',
                    backgroundColor: ['rgba(255, 159, 64, 0.2)'],
                    borderWidth: 1,
                }]
            }
            const operatorChart_config = {
                type: 'bar',
                data: operatorChart_data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            }
            // clear remove
            cardLoadingRemove(divScoreOperator)
            cardToggleDisplay(divScoreOperatorBody)

            scoreOperator = new Chart(scoreOperatorChart, operatorChart_config)
            // scoreOperator.canvas.parentNode.style.height = '5rem';
        }

    }

    function remove_operator() {
        if ($('#bar_operator').length && scoreOperatorChart.style.display) {
            scoreOperator.destroy()
        }
    }
</script>