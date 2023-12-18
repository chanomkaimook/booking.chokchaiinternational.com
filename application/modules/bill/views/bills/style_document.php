<style>
    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    @media print {


        div.A4 {
            margin-top: 4cm !important;
            width: 21cm;
            height: 29.7cm;

            scale: 1.3;

            background-color: red !important;
            border: 1px solid #111;
        }

        /* div.A4[layout="portrait"] {
            width: 29.7cm;
            height: 21cm;
            border: 1px solid #111 !important;
        } */

        table tr td,
        table tr th {
            padding: initial;
        }

        p {
            margin-bottom: initial;
        }
    }

    body .card-box {
        background: rgb(204, 204, 204);
    }

    div.A4 {
        background: white;
        width: 21cm;
        height: 29.7cm;
        display: block;
        margin: 0 auto;
        padding: 50px 35px;
        margin-bottom: 0.5cm;
        box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
    }

    /* 
                            |
                            | Header
                            |
                            */
    .page_header {
        text-align: center;
        width: 100%;
    }

    .page_header .logo img {
        width: 4cm;
    }

    /* 
                            |
                            | Body
                            |
                            */
    .page_body {
        width: 100%;
    }

    .page_body .pb_head .pb_head_cus {
        width: 70%;
    }

    .page_body .pb_head .pb_head_doc {
        width: 30%;
    }

    /* 
                            |
                            | Item
                            |
                            */
    .page_item {
        width: 100%;
    }

    .page_item tr {
        text-align: center
    }

    .page_item tbody tr {
        vertical-align: top;
    }

    .page_item tbody tr td:nth-child(2) {
        text-align: left
    }

    .page_item tbody tr td .doc_remark {
        position: absolute;
        bottom: 2px;
    }



    .page_item_total {
        width: 100%;
    }

    .page_item_total tr {
        text-align: center
    }

    .page_item_total tr th:last-child() {
        text-align: right
    }



    /* 
                            |
                            | Footer
                            |
                            */
    .page_footer {
        width: 100%;
    }

    .page_footer tr {
        text-align: center
    }


    .point_condition {
        text-align: left;
        padding: 10px 4px;
        font-size: 11px;
        font-weight: normal;
    }

    table tr td,
    table tr th {
        padding: 0px 4px;
    }

    p {
        margin-bottom: 6px;
    }
</style>