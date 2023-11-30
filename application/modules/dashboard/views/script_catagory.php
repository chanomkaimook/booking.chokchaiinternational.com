<script>
    let divScoreCatagory = '.card-body .score_catagory'
    let divScoreCatagoryBody = $(divScoreCatagory)

    let barChart = document.getElementById('bar')
    let pieChart = document.getElementById('pie')
    let pieCompareChart = document.getElementById('pie_compare')
    let doughnutChart = document.getElementById('doughnut')
    let divBarChart = '#graph'

    let barjs
    let pie
    let pieCompare
    let doughnutjs

    let scoreCatagory = []

    let setColor = [
        'rgba(255, 99, 132)',
        'rgba(255, 159, 64)',
        'rgba(255, 205, 86)',
        'rgba(75, 192, 192)',
        'rgba(54, 162, 235)',
        'rgba(153, 102, 255)',
        'rgba(201, 203, 207)'
    ]

    function draw_catagoryScore(data = []) {

        if (data.length) {
            creatBlockCatagory(data)
                .then((resp) => {
                    divScoreCatagoryBody.html(resp)
                })
        }

        // clear remove
        cardLoadingRemove(divScoreCatagory)
        cardToggleDisplay(divScoreCatagoryBody)
    }

    async function creatBlockCatagory(data) {
        let html_block = ''
        scoreCatagory = []

        data.forEach(function(item, index) {
            html_block += '<div class="score">'
            html_block += `<p class="text-uppercase font-13 font-weight-medium">${item.name}</p>`
            html_block += '<h5 class="mb-0">'
            html_block += `<i class="mdi mdi-arrow-up text-muted"></i>`
            html_block += `<span class="text-danger">${item.total}</span>`
            html_block += `/<span class="text-purple">${item.all}</span>`
            html_block += '</h5>'
            html_block += '</div>'

            // keep data for another function
            scoreCatagory.push({
                id: item.id,
                name: item.name,
                total: item.total,
                all: item.all,
            })
        })
        return await new Promise((resolve, reject) => {

            resolve(html_block)
        })
    }

    async function fetch_dataCatagoryScore() {
        let url = new URL(path(url_moduleControl + '/get_dataCatagoryScore'), domain)
        url.searchParams.append('workstatus', 3)

        let response = await fetch(url)
        let result = await response.json()

        return result
    }

    function draw_grap_bar() {
        let arrayFilterCatagory_key = []
        let arrayFilterCatagory_data = []

        let arrayNetCatagory_data = [];

        fetch_dataCatagoryScore()
            .then((resp) => {
                arrayFilterCatagory_key = resp.catagory.map(function(item, index) {
                    return item.name
                })
                arrayFilterCatagory_data = resp.catagory.map(function(item, index) {
                    return item.total
                })

            }).then(() => {
                setTimeout(() => {
                    // clear remove
                    cardLoadingRemove(divBarChart)
                    $(divBarChart).find(content3).removeClass('d-none')

                    doughnut_graph(arrayFilterCatagory_key, arrayFilterCatagory_data)
                }, 800);
            })

        // console.log(scoreCatagory)
        let totaltemp = 0
        let nettemp = 0
        let arrayKey = scoreCatagory.map(function(item, index) {
            return item.name
        })
        let arrayFilter_data = scoreCatagory.map(function(item, index) {
            totaltemp += parseInt(item.total)
            return item.total
        })
        let arrayAll_data = scoreCatagory.map(function(item, index) {
            nettemp += parseInt(item.all)
            return item.all
        })

        arrayNetCatagory_data = [totaltemp, nettemp]
        // console.log(arrayNetCatagory_data)
        // console.log(arrayKey)
        // console.log(arrayFilter_data)
        // console.log(arrayAll_data)

        $(divBarChart).prepend(loading)

        setTimeout(() => {

            // clear remove
            cardLoadingRemove(divBarChart)
            $(divBarChart).find(content3).removeClass('d-none')

            barjs = new Chart(barChart, barChart_config)
            barjs.canvas.parentNode.style.height = '250px';

            pie = new Chart(pieChart, pie_config)
            pie.canvas.parentNode.style.height = '250px';

            pieCompare = new Chart(pieCompareChart, pieCompare_config)
            pieCompare.canvas.parentNode.style.height = '250px';
        }, 800);

        //
        // config barchart
        const barChart_data = {
            labels: arrayKey,

            datasets: [{
                    label: 'จำนวนที่ปิดงาน',
                    data: arrayFilter_data,
                    borderColor: 'rgb(255, 159, 64)',
                    backgroundColor: ['rgba(255, 159, 64, 0.2)'],
                    borderWidth: 1,
                },
                {
                    label: 'จำนวนรวม',
                    data: arrayAll_data,
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: ['rgba(54, 162, 235, 0.2)'],
                    borderWidth: 1,
                }
            ]
        }
        const barChart_config = {
            type: 'bar',
            data: barChart_data,
            options: {
                indexAxis: 'y',
                // responsive: true,
                maintainAspectRatio: false,
            }
        }

        const pie_data = {
            labels: arrayKey,
            datasets: [{
                label: 'My First Dataset',
                data: arrayFilter_data,
                hoverOffset: 4
            }]
        };
        const pie_config = {
            type: 'pie',
            data: pie_data,
        };

        const pieCompare_data = {
            labels: ['ตัวเลือก', 'ทั้งหมด'],
            datasets: [{
                label: 'My First Dataset',
                data: arrayNetCatagory_data,
                backgroundColor: [
                    setColor[0],
                    setColor[5]
                ],
                hoverOffset: 4
            }]
        };
        const pieCompare_config = {
            type: 'pie',
            data: pieCompare_data,
        };

    }

    function remove_grap() {
// console.log(operatorChart.style.display)
        if(barChart.style.display){
            barjs.destroy()
        }
        if(pieChart.style.display){
            pie.destroy()
        }
        if(pieCompareChart.style.display){
            pieCompare.destroy()
        }
        if(doughnutChart.style.display){
            doughnutjs.destroy()
        }
    }

    function doughnut_graph(label, data) {
        //
        // config doughnut
        const doughnut_data = {
            labels: label,
            datasets: [{
                label: 'My First Dataset',
                data: data,
                /*  backgroundColor: [
                     'rgb(255, 99, 132)',
                     'rgb(54, 162, 235)',
                     'rgb(255, 205, 86)'
                 ], */
                hoverOffset: 4
            }]
        };
        const doughnut_config = {
            type: 'doughnut',
            data: doughnut_data,
        };

        doughnutjs = new Chart(doughnutChart, doughnut_config)
        doughnutjs.canvas.parentNode.style.height = '250px';
    }
</script>