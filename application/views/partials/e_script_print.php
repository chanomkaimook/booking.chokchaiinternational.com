<script>
    // print div
    function printDivss(divName) {
        var printContents = document.getElementById(divName).innerHTML

        let ifram = document.createElement("iframe");
        ifram.style = "display:none";
        document.body.appendChild(ifram);

        pri = ifram.contentWindow;
        pri.document.open();
        pri.document.write(printContents);
        pri.document.close();
        pri.focus();
        pri.print();

    }

    function printDivs(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        // document.body.innerHTML = originalContents;
        window.close();

    }

    function printDivssss(divName) {
        var printContents = document.getElementById(divName).innerHTML
        var originalContents = document.documentElement.innerHTML

        var w = window.open()
        // w.document.write(document.head.innerHTML)
        w.document.head.innerHTML = document.head.innerHTML

        // w.document.write(document.scripts.innerHTML)
        // w.document.documentElement.innerHTML = originalContents;
        w.document.body.innerHTML = printContents;

        // w.document.write(printContents)

        // w.focus()

        // w.print()


        /*  setTimeout(() => {

             w.print();
         }, 2000); */

        // w.document.body.innerHTML = printContents;
        // w.close();
    }

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML
        var w = window.open()

        loadContent()

        async function loadContent() {

            let doing1 = await new Promise((resolve, reject) => {
                resolve(
                    w.document.head.innerHTML = document.head.innerHTML,
                    w.document.body.innerHTML = printContents
                )
            })

            let doing2 = await new Promise((resolve, reject) => {
                setTimeout(() => {
                    resolve(
                        w.print(),
                        w.close()
                    )
                }, 100);
            })

            /* let result = new Promise((resolve, reject) => {
                var printContents = document.getElementById(divName).innerHTML

                w.document.head.innerHTML = document.head.innerHTML,
                    w.document.body.innerHTML = printContents
                resolve()
            }) */
        }

    }
</script>