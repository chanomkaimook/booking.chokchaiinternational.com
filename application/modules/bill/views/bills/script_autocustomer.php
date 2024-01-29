<script>
    $(function() {

        dataCustomer()

        function dataCustomer() {

            fetch_dataCustomer()
                .then(resp => {
                    let result = []
                    let result_id = []
                    if (resp) {
                        resp.forEach(function(item, index) {
                            result.push({
                                label: item.NAME,
                                id: item.ID
                            })
                        })

                        $("[name=customer]").autocomplete({
                            source: result,
                            select: function(event, ui) {
                                if (ui.item.id) {
                                    $('[name=customer_id]').val(ui.item.id);

                                    // open select address
                                    $('select#cus_id').removeAttr('disabled')
                                    $('select#cus_id option[data-cus_id!='+ui.item.id+']').addClass('d-none')
                                    
                                    $('[name=customer_address]').val('')
                                }

                            }
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