<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Jemaat; // Tambahkan pemanggilan model Jemaat
use Illuminate\Http\Request;
use Carbon\Carbon;

class KalenderController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        $currentDate = Carbon::createFromDate($year, $month, 1);
        $prevMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();

        // 1. Data Agenda Gereja (Dikelompokkan per tanggal)
        $agendas = Agenda::whereMonth('tanggal_mulai', $month)
                         ->whereYear('tanggal_mulai', $year)
                         ->get()
                         ->groupBy(function($item) {
                             return Carbon::parse($item->tanggal_mulai)->format('Y-m-d');
                         });

        // 2. Data Agenda Terdekat (Khusus Agenda Gereja)
        $agendaTerdekat = Agenda::where('tanggal_mulai', '>=', date('Y-m-d'))
                                ->orderBy('tanggal_mulai', 'asc')
                                ->orderBy('waktu', 'asc')
                                ->take(3)
                                ->get();

        // 3. Data Ulang Tahun Jemaat (Khusus Bulan Ini)
        $ultahJemaat = Jemaat::whereMonth('tgl_lahir', $month)
                            ->where('tampilkan_ultah', true)
                            ->where('status_keanggotaan', 'Aktif')
                            ->get()
                            ->groupBy(function($item) use ($year, $month) {
                                // Pindahkan tahun lahir ke tahun kalender saat ini agar masuk ke grid
                                $day = Carbon::parse($item->tgl_lahir)->format('d');
                                return $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . $day;
                            });

        // 4. Data Libur Nasional Indonesia (Statis Terintegrasi)
        $holidays = $this->getIndonesianHolidays($year);
        $hariLibur = [];
        foreach($holidays as $date => $name) {
            if(Carbon::parse($date)->format('m') == $month) {
                $hariLibur[$date] = $name;
            }
        }

        return view('kalender.index', compact('currentDate', 'prevMonth', 'nextMonth', 'agendas', 'agendaTerdekat', 'ultahJemaat', 'hariLibur'));
    }

    public function store(Request $request)
    {
        Agenda::create($request->all());
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $agenda = Agenda::findOrFail($id);
        $agenda->update($request->all());
        return redirect()->back();
    }

    public function destroy($id)
    {
        $agenda = Agenda::findOrFail($id);
        $agenda->delete();
        return redirect()->back();
    }

    // Fungsi Pembantu: Data Hari Libur Nasional Indonesia
    private function getIndonesianHolidays($year) {
        return [
            "$year-01-01" => "Tahun Baru Masehi",
            "$year-05-01" => "Hari Buruh Internasional",
            "$year-06-01" => "Hari Lahir Pancasila",
            "$year-08-17" => "Hari Kemerdekaan RI",
            "$year-12-25" => "Hari Raya Natal",
            // Estimasi kalender dinamis (misal di tahun 2026)
            "$year-02-18" => "Isra Mikraj",
            "$year-03-03" => "Hari Suci Nyepi",
            "$year-03-20" => "Hari Raya Idul Fitri",
            "$year-04-03" => "Wafat Yesus Kristus",
            "$year-04-05" => "Hari Paskah",
            "$year-05-14" => "Kenaikan Yesus Kristus",
            "$year-05-24" => "Idul Adha",
            "$year-06-14" => "Tahun Baru Islam",
        ];
    }
}