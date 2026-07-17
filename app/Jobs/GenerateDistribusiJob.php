<?php

namespace App\Jobs;

use App\Services\GeneticAlgorithm;
use App\Models\Distribusi;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateDistribusiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $dosens;
    private $mataKuliahs;
    private $kelasList;
    private $prodiId;

    public function __construct($dosens, $mataKuliahs, $kelasList, $prodiId)
    {
        $this->dosens = $dosens;
        $this->mataKuliahs = $mataKuliahs;
        $this->kelasList = $kelasList;
        $this->prodiId = $prodiId;
    }

    public function handle()
    {
        $ag = new GeneticAlgorithm(
            $this->dosens,
            $this->mataKuliahs,
            $this->kelasList
        );

        $hasil = $ag->run();

        // simpan progress final
        Cache::put("ga_status_{$this->prodiId}", 'done');

        Distribusi::where('prodi_id', $this->prodiId)
            ->where('sumber', 'ag')
            ->delete();

        foreach ($hasil['kromosom'] as $gen) {
            Distribusi::create([
                'dosen_id' => $gen['dosen_id'],
                'prodi_id' => $gen['prodi_id'],
                'mata_kuliah_id' => $gen['matkul_id'],
                'kelas_id' => $gen['kelas_id'],
                'sks' => $gen['sks'],
                'jam' => $gen['jam'],
                'sumber' => 'ag',
            ]);
        }
    }
}