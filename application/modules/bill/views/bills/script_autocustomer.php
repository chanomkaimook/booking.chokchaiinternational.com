<script>
    $(function() {

        dataCustomer()

        function dataCustomer() {

            fetch_dataCustomer()
                .then(resp => {
                    let result = []
                    if (resp) {
                        resp.forEach(function(item, index) {
                            result.push(item.NAME)
                        })

                        $("[name=customer]").autocomplete({
                            source: result
                            // source: ['farmchokchai','zeer']
                        });

                        return result
                    }
                })
        }

        async function fetch_dataCustomer() {
            let url = new URL(path('information/ctl_customer/get_data'), domain)
            const response = await fetch(url)
            const result = await response.json()
            return result;
        }
    });
</script>