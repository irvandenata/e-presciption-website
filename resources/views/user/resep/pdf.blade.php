<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .top_rw {
            background-color: #f4f4f4;
        }

        .td_w {}

        button {
            padding: 5px 10px;
            font-size: 14px;
        }

        .invoice-box {

            font-size: 14px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-bottom: solid 1px #ccc;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: middle;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            font-size: 12px;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        <blade media|%20only%20screen%20and%20(max-width%3A%20600px)%20%7B>.invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }

        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        }

        /** RTL **/
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(2) {
            text-align: left;
        }

    </style>
</head>

<body>

    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top_rw">
                <td colspan="2">
                    <h2 style="margin-bottom: 0px;"> Invoice Resep Obat</h2>
                    <span style=""> Kontak: 27B00032991LQ354 <br> Tanggal: {{ date('d M Y',strtotime($items->first()->resep->created_at)) }}</span>
                </td>
                <td style="width:30%; margin-right: 10px;">
                    Nomor Invoice: 6580083283
                </td>
            </tr>
            <td colspan="3">
                <table cellspacing="0px" cellpadding="2px">
                    <tr class="heading">
                        <td style="width:15%;">
                            Nama Obat
                        </td>
                        <td style="width:10%; text-align:center;">
                            Jenis Obat
                        </td>
                        <td style="width:10%; text-align:right;">
                            Signa
                        </td>
                        <td style="width:15%; text-align:right;">
                            Keterangan
                        </td>
                    </tr>

                    @foreach($items as $item)
                        <tr class="item">
                            <td style="width:15%;">
                                {{ $item->isRacikan ? $item->nama_racikan : $item->obatResepSignas->first()->obat->obatalkes_nama }}
                            </td>
                            <td style="width:10%; text-align:center;">
                                {{ $item->isRacikan ? 'Racikan' : 'Non Racikan' }}
                            </td>
                            <td style="width:10%; text-align:right;">
                                {{ $item->signa->signa_nama }}
                            </td>
                            <td style="width:15%; text-align:right;">
                                @php
                                      $txt = "<small>";
                foreach ($item->obatResepSignas as $value) {
                    $txt = $txt . " " . $value->obat->obatalkes_nama . " ( Qty : " . $value->jumlah . " ) <br>";
                }
                $txt = $txt . "<small>";
                                @endphp

                            {!! $txt !!}
                            </td>

                        </tr>
                    @endforeach


            </td>
        </table>
        <tr class="total">
            <td colspan="3" align="right"> Total Biaya : <b> Rp. 50.000,00- </b> </td>
        </tr>
        <tr>
            <td colspan="3">
                <table cellspacing="0px" cellpadding="2px">
                    <tr>
                        <td width="50%">
                            <b> Deskripsi: </b> <br>
                            Perhatikan Anjuran untuk meminum obat, semoga cepat sembuh.
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                        </td>
                        <td>
                            <b> Pegawai </b>
                            <br>
                            <br>
                            ...................................
                            <br>
                            <br>
                            <br>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </table>
    </div>

</body>

</html>
