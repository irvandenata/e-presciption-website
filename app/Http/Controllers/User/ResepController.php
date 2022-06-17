<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use App\Models\ObatResepSigna;
use App\Models\Resep;
use App\Models\ResepSigna;
use App\Models\Signa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use PDF;
class ResepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $items = Resep::where('user_id', auth()->user()->id)->latest()->get();

            return DataTables::of($items)

                ->addColumn('action', function ($item) {
                    return '<a class="btn btn-warning btn-sm" href="/api/resep/cetak/'.$item->id.'" ><i class="fa fa-print"></i></span></a>  <a class="btn btn-info btn-sm" onclick="detailItem(' . $item->id . ')"><i class="fa fa-eye"></i></span></a> <a class="btn btn-danger btn-sm" onclick="deleteItem(' . $item->id . ')"><i class="fa fa-trash"></i></span></a>';
                })
                ->editColumn('created_at', function ($item) {
                    return $item->created_at->format('d M Y');
                })
                ->removeColumn('id')
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
        $data['title'] = "Resep";
        return view('user.resep.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Resep";
        return view('user.resep.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        DB::beginTransaction();
        try {
            $resep = new Resep();
            $resep->user_id = auth()->user()->id;
            $resep->save();
            foreach ($request['data'] as $value) {
                $resepSigna = new ResepSigna();
                $resepSigna->signa_id = intval($value['signaId']);
                $resepSigna->resep_id = $resep->id;
                if (count($value['komponen']) > 1) {
                    $resepSigna->isRacikan = true;
                    $resepSigna->nama_racikan = $value['namaRacikan'];
                } else {
                    $resepSigna->isRacikan = false;
                }

                $resepSigna->save();
                $value['komponen'] = array_filter($value['komponen'], fn($v) => !is_null($v) && $v !== '');
                foreach ($value['komponen'] as $item) {
                    $obat = Obat::where('obatalkes_id', intval($item['obatId']))->first();
                    $obat->stok = $obat->stok - intval($item['jumlah']);
                    if ($obat->stok < 0) {
                        DB::rollBack();
                        return response()->json(['status' => 'error', 'message' => 'Jumlah Obat ' . $obat->obatalkes_nama . ' yang ditambahkan melebihi stok Saat ini. Hapus dan tambah kembali obat tersebut.'], 500);
                    }
                    $obat->save();
                    $obatSigna = new ObatResepSigna();

                    $obatSigna->resep_signa_id = $resepSigna->id;
                    $obatSigna->obat_id = intval($item['obatId']);
                    $obatSigna->jumlah = intval($item['jumlah']);
                    $obatSigna->save();
                }
            }
        } catch (\Exception$e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e], 500);
        }
        DB::commit();

        // dd();
        return response()->json(['status' => 'success', 'message' => 'Resep Berhasil Dibuat'], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resep $resep)
    {

        $resep->delete();
        return response()->json(['message', 'deleted success']);
    }

    public function getObat(Request $request)
    {
        $obat = Obat::where('obatalkes_nama', 'like', '%' . $request->q . '%')->get();
        return response()->json($obat);
    }
    public function getObatId($id)
    {
        $obat = Obat::where('obatalkes_id', $id)->first();
        return response()->json($obat);
    }
    public function getSigna(Request $request)
    {
        $obat = Signa::where('signa_nama', 'like', '%' . $request->q . '%')->get();
        return response()->json($obat);
    }

    public function detailResep(Request $request, $id)
    {
        $items = ResepSigna::where('resep_id', $id)->whereHas('resep', function ($q) {
            $q->where('user_id', auth()->user()->id);
        })->latest()->with('signa')->get();
        return DataTables::of($items)
            ->addColumn('nama_obat', function ($item) {
                return "<small>" . ($item->isRacikan ? $item->nama_racikan : $item->obatResepSignas->first()->obat->obatalkes_nama) . "</small>";
            })
            ->addColumn('jenis_obat', function ($item) {
                return ($item->isRacikan ? 'Racikan' : 'Non Racikan');
            })
            ->addColumn('signa', function ($item) {
                return ($item->signa->signa_nama);
            })
            ->addColumn('keterangan', function ($item) {
                $txt = "<small>";
                foreach ($item->obatResepSignas as $value) {
                    $txt = $txt . " " . $value->obat->obatalkes_nama . " ( Qty : " . $value->jumlah . " ) <br>";
                }
                $txt = $txt . "<small>";

                return $txt;
            })
            ->removeColumn('id')
            ->addIndexColumn()
            ->rawColumns(['action', 'nama_obat', 'keterangan'])
            ->make(true);

    }

    public function cetakResep($id)
    {
        $items = ResepSigna::where('resep_id', $id)->whereHas('resep', function ($q) {
            $q->where('user_id', auth()->user()->id);
        })->latest()->with('signa')->get();

        $pdf = PDF::loadView('user.resep.pdf', compact('items'));
        return $pdf->download('resep.pdf');

    }
}
