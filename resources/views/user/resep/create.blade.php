@extends('layouts.template')

@section('title', $title)

@push('css')
    <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Responsive datatable examples -->
@endpush

@push('style')
    <style>
        .mtop-100 {
            margin-top: 150px !important;
        }

        .hide {
            display: none;
        }

    </style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card-box table-responsive">
            <h3>Tambah Obat</h3>
            <div class="row">
                <div class="col-12 form-group">
                    <label for="additional">Jenis Obat</label>
                    <select class="form-control" name="" id="jenisObat">
                        <option value selected disabled>== Pilih Jenis ==</option>
                        <option value="1">Non Racikan</option>
                        <option value="2">Racikan</option>
                    </select>
                </div>
                <div id="nonRacikan" class="col-12 hide">
                    <div class="row">
                        <div class="col-3 form-group">
                            <label for="additional">Nama Obat</label>
                            <select class="form-control obat" name="" id="obat">

                            </select>
                        </div>
                        <div class="col-4 form-group">
                            <label for="additional">Signa</label>
                            <select class="form-control signa" name="" id="signa">

                            </select>
                        </div>
                        <div class="col-2 form-group">
                            <label for="number">Jumlah</label>
                            <input type="number" min="0" value="0" class="form-control jumlah">
                        </div>
                        <div class="col-2 form-group">
                            <label for="number">Ketersediaan</label>
                            <input type="number" name="file" value="0" class="form-control ketersediaan" disabled>
                        </div>
                        <div class="col-12 text-right mt-4">
                            <div class="btn btn-success" id="tamObat">Tambah Obat</div>
                        </div>
                    </div>
                </div>
                <div id="racikan" class="col-12 hide">
                    <div class="row">
                        <div class="col-6 form-group">
                            <label for="number">Nama Racikan</label>
                            <input type="text" name="" class="form-control namaRacikan">
                        </div>
                        <div class="col-6 form-group">
                            <label for="additional">Signa</label>
                            <select class="form-control signaRacikan" name="" id="">

                            </select>
                        </div>
                        <div class="col-8 form-group">
                            <label for="additional">Obat</label>
                            <select class="form-control obatRacik" name="" id="">

                            </select>
                        </div>

                        <div class="col-2 form-group">
                            <label for="number">Jumlah</label>
                            <input type="number" min="0" name="file" class="form-control jumlahRacik" value="0">
                        </div>
                        <div class="col-2 form-group">
                            <label for="number">Ketersediaan</label>
                            <input type="number" name="file" value="0" class="form-control ketersediaanRacik" disabled>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-warning" id="masukkan">Masukkan</button>
                        </div>
                        <div class="col-12 mt-4">
                            <b>list Obat</b>
                        </div>
                        <div class="col-12">
                            <div class="row" id="listObat">

                            </div>
                        </div>


                        <div class="col-12 text-right mt-4">
                            <button class="btn btn-success" id="simpanRacikan">Tambah Obat</button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-12">
        <div class="card-box table-responsive">

            <table id="datatable" class="table table-bordered  m-t-30">
                <thead>
                    <tr>
                        <th width="15%">Nama Obat</th>
                        <th width="10%">Jenis Obat</th>
                        <th width="15%">Signa</th>
                        <th>Jumlah</th>
                        <th width="5%">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <div class="mt-3 text-right">
                <button class="btn btn-primary sendData">Simpan</button>
            </div>
        </div>

    </div>
</div>
@endsection

@push('js')

    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    {{-- sweat allert --}}

    <!-- Responsive examples -->
    <script src="{{ asset('assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Selection table -->
    <script src="{{ asset('assets/plugins/datatables/dataTables.select.min.js') }}"></script>


@endpush

@push('script')
    <script>
        let obat = [];
        let kuantitasObat = {}
        let obatRacikan = [];

        const To = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            allowOutsideClick: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        let dataTable = $('#datatable').DataTable();
        $('.obat').select2({
            ajax: {
                url: "/api/resep/get-obat",
                data: function (params) {
                    var q = {
                        q: params.term
                    }
                    return q;
                },
                processResults: function (data) {

                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.obatalkes_nama + ' - ' + item.obatalkes_kode,
                                id: item.obatalkes_id,
                                data: item
                            }
                        })
                    }
                }
            },
            placeholder: "--- Pilih Obat ---",
            allowClear: true
        });

        $('.obatRacik').select2({
            ajax: {
                url: "/api/resep/get-obat",
                data: function (params) {
                    var q = {
                        q: params.term
                    }
                    return q;
                },
                processResults: function (data) {

                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.obatalkes_nama + ' - ' + item.obatalkes_kode,
                                id: item.obatalkes_id,
                                data: item
                            }
                        })
                    }
                }
            },
            placeholder: "--- Pilih Obat ---",
            allowClear: true
        });

        $('.signa').select2({
            ajax: {
                url: "/api/resep/get-signa",
                data: function (params) {
                    var q = {
                        q: params.term
                    }
                    return q;
                },
                processResults: function (data) {

                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.signa_nama + ' - ' + item.signa_kode,
                                id: item.signa_id,
                            }
                        })
                    }
                }
            },
            placeholder: "--- Pilih Signa ---",
            allowClear: true
        });
        $('.signaRacikan').select2({
            ajax: {
                url: "/api/resep/get-signa",
                data: function (params) {
                    var q = {
                        q: params.term
                    }
                    return q;
                },
                processResults: function (data) {

                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.signa_nama + ' - ' + item.signa_kode,
                                id: item.signa_id,
                            }
                        })
                    }
                }
            },
            placeholder: "--- Pilih Signa ---",
            allowClear: true
        });


        $('#jenisObat').on('change', function () {
            let jenisObat = $(this).val();
            if (jenisObat == 1) {
                $('#nonRacikan').show();
                $('#racikan').hide();
            } else {
                $('#nonRacikan').hide();
                $('#racikan').show();
            }
            $('.signa').val('').trigger('change');
            $('.jumlah').val(0)
            $('.ketersediaan').val(0)
            $('.obat').val('').trigger('change');
        });

        $('.obat').on('change', function () {
            let obat = $(this).val();
            $.ajax({
                url: "/api/resep/get-obat/" + obat,
                type: "GET",
                success: function (data) {

                    if (kuantitasObat[data.obatalkes_id] == undefined) {

                        $('.ketersediaan').val(data.stok);
                    } else {
                        console.log(kuantitasObat[data.obatalkes_id])
                        let v = (data.stok - kuantitasObat[data.obatalkes_id])
                        if (v < 0) {
                            To.fire({
                                icon: 'error',
                                title: 'Stok Dibawah Jumlah dari Draft Sebelumnya (' + v + ')\nSebaiknya Input Obat Ini Kembali'
                            })

                            $('.ketersediaan').val(0);
                        } else {
                            $('.ketersediaan').val(v);
                        }

                    }

                }
            });
        })


        $('.obatRacik').on('change', function () {
            let obat = $(this).val();
            $.ajax({
                url: "/api/resep/get-obat/" + obat,
                type: "GET",
                success: function (data) {

                    if (kuantitasObat[data.obatalkes_id] == undefined) {

                        $('.ketersediaanRacik').val(data.stok);
                    } else {
                        let v = (data.stok - kuantitasObat[data.obatalkes_id])
                        if (v < 0) {
                            To.fire({
                                icon: 'error',
                                title: 'Stok Dibawah Jumlah dari Draft Sebelumnya (' + v + ')\nSebaiknya Input Obat Ini Kembali'
                            })
                            $('.ketersediaanRacik').val(0);
                        } else {
                            $('.ketersediaanRacik').val(v);
                        }

                    }
                    //
                }
            });
        })
        $('.jumlah').on('change', async function () {
            let jumlah = parseFloat($(this).val());
            let stok = parseFloat($('.ketersediaan').val());

            if (jumlah > stok) {
                  To.fire({
                                icon: 'error',
                                title: 'Jumlah yang diinputkan melebihi stok'
                            })

                $(this).val(0);
            }
        })

        $('#tamObat').on('click', function () {
            To.fire({
                icon: 'success',
                title: 'Obat Berhasil ditambahkan'
            })
            if ($('.jumlah').val() == 0 || $('.jumlah').val() == '') {
                To.fire({
                    icon: 'error',
                    title: 'Jumlah yang diinputkan tidak boleh 0'
                })

                return;
            }
            if ($('.signa').val() == undefined || $('.signa').val() == '') {
                To.fire({
                    icon: 'error',
                    title: 'Signa Tidak Bolah Kosong'
                })
                return;
            }

            let obatId = $('.obat').val();
            let signaId = $('.signa').val();
            let jumlah = $('.jumlah').val();
            kuantitasObat[obatId] = (kuantitasObat[obatId] != undefined ? kuantitasObat[obatId] : 0) + parseInt(jumlah)
            let id = obat.length
            obat.push({
                signaId: signaId,
                name: $('.signa').find(':selected').text(),
                komponen: [{
                    obatId: obatId,
                    jumlah: jumlah,
                    name: $('.obat').find(':selected').text()
                }]
            })
            var btn = "<div class='btn btn-danger trash' ><i class='fa fa-trash'></i></div>"
            dataTable.row.add([$('.obat').find(':selected').text(), $('#jenisObat').find(':selected').text(), $('.signa').find(':selected').text(), $('.obat').find(':selected').text() + " ( " + jumlah + " )", btn]).draw(false);
            $('.signa').val('').trigger('change');
            $('.jumlah').val(0)
            $('.ketersediaan').val(0)
            $('.obat').val('').trigger('change');

        });


        $('.sendData').on('click', async function () {
            if (obat.length < 1) {
                To.fire({
                    icon: 'error',
                    title: 'Tidak Ada Obat Yang Di inputkan'
                })
                return;
            }
            To.fire({
                icon: 'success',
                title: 'Loading...'
            })
            Swal.showLoading()
            Swal.stopTimer()
            await $.ajax({
                url: '/resep',
                type: "post",
                dataType: 'json',
                data: {
                    data: obat
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: async function (result) {
                    await To.fire({
                        icon: result.status,
                        title: result.message
                    })

                },
                error: function (result) {

                    if (result.responseJSON) {
                        To.fire({
                            icon: result.responseJSON.status,
                            title: result.responseJSON.message.errorInfo[2]
                        })
                    } else {
                        console.log(result);
                    }
                },
            }).then(function () {
                setTimeout(function () {
                    location.href = '/resep'
                }, 5000);

            })
        })

        $('#datatable tbody').on('click', '.trashM', function () {
            let index = dataTable.row($(this).parent().parent()).index()
            obat[index]['komponen'].forEach(value => {
                     kuantitasObat[value['obatId']] =  parseInt(kuantitasObat[value['obatId']]) - parseInt(value['jumlah'])
            });
            obat.splice(index, 1);
            dataTable
                .row($(this).parents('tr'))
                .remove()
                .draw();
        });



        $('#datatable tbody').on('click', '.trash', function () {
            let index = dataTable.row($(this).parent().parent()).index()
            console.log()
            kuantitasObat[obat[index]['komponen'][0]['obatId']] = parseInt(kuantitasObat[obat[index]['komponen'][0]['obatId']]) - parseInt(obat[index]['komponen'][0]['jumlah'])
            console.log(kuantitasObat[obat[index]['komponen'][0]['obatId']])

            obat.splice(index, 1);
            dataTable
                .row($(this).parents('tr'))
                .remove()
                .draw();
        });


        $('#masukkan').on('click', function () {

            if ($('.jumlahRacik').val() == 0 || $('.jumlahRacik').val() == '') {
                To.fire({
                    icon: 'error',
                    title: 'Jumlah yang diinputkan tidak boleh 0'
                })

                return;
            }
            if (parseInt($('.jumlahRacik').val()) > parseInt($('.ketersediaanRacik').val())) {
                To.fire({
                    icon: 'error',
                    title: 'Jumlah Obat Melebihi Stok'
                })

                return;
            }
            let obatId = $('.obatRacik').val();
            let jumlah = $('.jumlahRacik').val();
            kuantitasObat[obatId] = (kuantitasObat[obatId] != undefined ? kuantitasObat[obatId] : 0) + parseInt(jumlah)
            if (obatRacikan[obatId] == undefined) {
                obatRacikan[obatId] = {
                    obatId: obatId,
                    jumlah: jumlah,
                    name: $('.obatRacik').find(':selected').text()
                }
            } else {
                obatRacikan[obatId]['jumlah'] = parseInt(obatRacikan[obatId]['jumlah']) + parseInt(jumlah)
                $("#listObat").find(".head [data-id='" + obatId + "']").parent().remove()
            }
            $('#listObat').append(`<div class="col-6 mt-2 row ml-2 head">
                                    <input type="text" name="listObat[]" value="` + $('.obatRacik').find(':selected').text() + ` ( Jumlah : ` + obatRacikan[obatId]['jumlah'] + ` )" data-id='` + obatId + `' class="form-control col-10 lObat" disabled>
                                    <div class="btn btn-danger removeObat" href=""><i class="fa fa-trash"></i></div>
                                </div>`)
            $(".removeObat").unbind('click');
            $('.removeObat').on('click', function () {
                let index = $(this).parent().find('.lObat').data('id')

                kuantitasObat[index] = parseInt(kuantitasObat[index]) - parseInt(obatRacikan[obatId]['jumlah'])
                obatRacikan.splice(index, 1);
                $(this).parent().remove()
                $('.jumlahRacik').val(0)
                $('.ketersediaanRacik').val(0)
                $('.obatRacik').val('').trigger('change');
            })
            $('.jumlahRacik').val(0)
            $('.ketersediaanRacik').val(0)
            $('.obatRacik').val('').trigger('change');

        })

        $('#simpanRacikan').on('click', function () {
            To.fire({
                icon: 'success',
                title: 'Obat Racikan Berhasil ditambahkan'
            })
            if (obatRacikan.length < 1) {
                To.fire({
                    icon: 'error',
                    title: 'Anda Belum Menambahkan Obat'
                })

                return;
            }
            if ($('.signaRacikan').val() == undefined || $('.signaRacikan').val() == '') {
                To.fire({
                    icon: 'error',
                    title: 'Signa Tidak Boleh Kosong'
                })

                return;
            }
            if ($('.namaRacikan').val() == undefined || $('.namaRacikan').val() == '') {
                To.fire({
                    icon: 'error',
                    title: 'Nama Racikan Tidak Boleh Kosong'
                })

                return;
            }


            let namaRacikan = $('.namaRacikan').val();
            let signaId = $('.signaRacikan').val();
            let data = {
                signaId: signaId,
                name: $('.signaRacikan').find(':selected').text(),
                namaRacikan: namaRacikan,
                komponen: obatRacikan
            }
            obat.push(data)
            console.log(obat)
            var btn = "<div class='btn btn-danger trashM' ><i class='fa fa-trash'></i></div>"
            var detail = '<small>'
            obatRacikan.forEach(value => {
                console.log(value)
                if (value != undefined) {
                    console.log(value)
                    detail = detail + value['name'] + "( " + value['jumlah'] + " )<br>"
                }
            });
            detail = detail + "</small>"
            dataTable.row.add([namaRacikan, $('#jenisObat').find(':selected').text(), $('.signaRacikan').find(':selected').text(), detail, btn]).draw(false);
            $('.signaRacikan').val('').trigger('change');
            $('#listObat').empty();
            obatRacikan = []
        });

    </script>


@endpush
