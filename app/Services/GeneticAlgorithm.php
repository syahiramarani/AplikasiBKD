<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;


class GeneticAlgorithm
{
    private int $populasiSize = 100;
    private int $maxGenerasi = 150;
    private float $crossoverRate = 0.80;
    private float $mutationRate = 0.05;
    private float $targetFitness = 0.95;
    private int $tournamentK = 3;

    private array $dosens = [];
    private array $mataKuliahs = [];
    private array $kelasList = [];

    private array $populasi = [];
    private array $fitnessHistory = [];
    private array $bestKromosom = [];
    private float $bestFitness = 0.0;

    /*
     * Cache/index untuk mempercepat akses tanpa mengubah logika
     */
    private array $dosenIndex = [];
    private array $mkIndex = [];
    private array $kelasByProdi = [];
    private array $kandidatByMk = [];

    // ─────────────────────────────
    // CONSTRUCTOR
    // ─────────────────────────────
    public function __construct(array $dosens, array $mataKuliahs, array $kelasList)
    {
        $this->dosens = $dosens;
        $this->mataKuliahs = $mataKuliahs;
        $this->kelasList = $kelasList;

        $this->buildIndexes();
    }

    // ─────────────────────────────
    // SET PARAMETER
    // ─────────────────────────────
    public function setParameter(
        int $populasiSize = 100,
        int $maxGenerasi = 150,
        float $crossoverRate = 0.80,
        float $mutationRate = 0.05,
        float $targetFitness = 0.95,
        int $tournamentK = 3
    ): void {
        $this->populasiSize = $populasiSize;
        $this->maxGenerasi = $maxGenerasi;
        $this->crossoverRate = $crossoverRate;
        $this->mutationRate = $mutationRate;
        $this->targetFitness = $targetFitness;
        $this->tournamentK = $tournamentK;
    }

    // ════════════════════════════════════════════════════════════════
    //  ENTRY POINT
    // ════════════════════════════════════════════════════════════════
    public function run(): array
    {
        $this->resetRunState();

        Log::info('[GA] RUN START', [
            'populasiSize' => $this->populasiSize,
            'maxGenerasi' => $this->maxGenerasi,
            'crossoverRate' => $this->crossoverRate,
            'mutationRate' => $this->mutationRate,
            'targetFitness' => $this->targetFitness,
            'tournamentK' => $this->tournamentK,
            'jumlah_dosen' => count($this->dosens),
            'jumlah_mk' => count($this->mataKuliahs),
            'jumlah_kelas' => count($this->kelasList),
        ]);

        $this->populasi = $this->inisialisasiPopulasi();

        Log::info('[GA] POPULASI INIT', [
            'jumlah_populasi_terbentuk' => count($this->populasi),
        ]);

        if (empty($this->populasi)) {
            Log::warning('[GA] POPULASI KOSONG - RUN DIHENTIKAN');
            return [
                'kromosom' => [],
                'fitness' => 0.0,
                'generasi' => 0,
                'history' => [],
            ];
        }

        for ($gen = 1; $gen <= $this->maxGenerasi; $gen++) {
            $fitnessValues = [];
            $bestIdxThisGen = null;
            $bestFitThisGen = -INF;

            foreach ($this->populasi as $index => $kromosom) {
                $fit = $this->hitungFitness($kromosom);
                $fitnessValues[$index] = $fit;

                if ($fit > $bestFitThisGen) {
                    $bestFitThisGen = $fit;
                    $bestIdxThisGen = $index;
                }
            }

            if ($bestIdxThisGen !== null && $bestFitThisGen > $this->bestFitness) {
                $this->bestFitness = $bestFitThisGen;
                $this->bestKromosom = $this->populasi[$bestIdxThisGen];
            }

            $this->fitnessHistory[] = round($this->bestFitness, 4);
            if ($gen % 10 === 0 || $gen === 1) {
                Log::info('[GA] PROGRESS', [
                    'gen' => $gen,
                    'bestFitness' => round($this->bestFitness, 4),
                ]);
            }

            if ($this->bestFitness >= $this->targetFitness) {
                Log::info('[GA] STOP - TARGET FITNESS TERCAPAI', [
                    'gen' => $gen,
                    'bestFitness' => round($this->bestFitness, 4),
                ]);
                break;
            }

            arsort($fitnessValues);
            $eliteIndexes = array_slice(array_keys($fitnessValues), 0, 2);

            $generasiBaru = [];

            foreach ($eliteIndexes as $idx) {
                if (isset($this->populasi[$idx])) {
                    $generasiBaru[] = $this->populasi[$idx];
                }
            }

            while (count($generasiBaru) < $this->populasiSize) {
                $parent1 = $this->tournamentSelection($fitnessValues);
                $parent2 = $this->tournamentSelection($fitnessValues);

                [$child1, $child2] = $this->crossover($parent1, $parent2);

                $child1 = $this->mutasi($child1);
                $child2 = $this->mutasi($child2);

                if ($this->validasiSks($child1)) {
                    $generasiBaru[] = $child1;
                }

                if (count($generasiBaru) < $this->populasiSize && $this->validasiSks($child2)) {
                    $generasiBaru[] = $child2;
                }
            }

            $this->populasi = $generasiBaru;
        }
        Log::info('[GA] RUN END', [
            'bestFitness' => round($this->bestFitness, 4),
            'generasi' => count($this->fitnessHistory),
            'jumlah_gen_best' => count($this->bestKromosom),
        ]);


        return [
            'kromosom' => $this->bestKromosom,
            'fitness' => $this->bestFitness,
            'generasi' => count($this->fitnessHistory),
            'history' => $this->fitnessHistory,
        ];
    }

    // ════════════════════════════════════════════════════════════════
    //  INISIALISASI
    // ════════════════════════════════════════════════════════════════
    private function inisialisasiPopulasi(): array
    {
        $populasi = [];

        for ($i = 0; $i < $this->populasiSize; $i++) {
            $kromosom = $this->buatKromosomAcak();
            $coba = 0;

            while (!$this->validasiSks($kromosom) && $coba < 30) {
                $kromosom = $this->buatKromosomAcak();
                $coba++;
            }

            if (!empty($kromosom)) {
                $populasi[] = $kromosom;
            }
        }

        return $populasi;
    }

    private function buatKromosomAcak(): array
    {
        $kromosom = [];

        foreach ($this->mataKuliahs as $mk) {
            $kelasUntukMk = $this->kelasByProdi[$mk['prodi_id']] ?? [];

            if (empty($kelasUntukMk)) {
                continue;
            }

            $kandidat = $this->getKandidatDosenByMk($mk);

            if (empty($kandidat)) {
                continue;
            }

            foreach ($kelasUntukMk as $kelas) {
                $dosenPilih = $kandidat[array_rand($kandidat)];

                $kromosom[] = [
                    'dosen_id' => $dosenPilih['id'],
                    'matkul_id' => $mk['id'],
                    'kelas_id' => $kelas['id'],
                    'prodi_id' => $mk['prodi_id'],
                    'sks' => $mk['sks'],
                    'jam' => $mk['jam'],
                    'bidang_id' => $mk['bidang_id'] ?? null,
                ];
            }
        }

        return $kromosom;
    }

    /**
     * Pencocokan 3 lapis.
     * Dosen prodi sendiri diurutkan duluan, dosen lintas prodi tetap masuk daftar di belakang.
     */
    public function cariKandidatDosen(array $mk): array
    {
        $kandidat = array_filter(
            $this->dosens,
            function ($d) use ($mk) {
                if (!isset($mk['bidang_id'])) {
                    return false;
                }

                return in_array($mk['bidang_id'], $d['bidang_ids'] ?? [], true);
            }
        );

        $kandidat = array_values($kandidat);

        usort($kandidat, function ($a, $b) use ($mk) {
            $aHome = ($a['prodi_id'] === $mk['prodi_id']) ? 0 : 1;
            $bHome = ($b['prodi_id'] === $mk['prodi_id']) ? 0 : 1;

            return $aHome <=> $bHome;
        });

        return $kandidat;
    }

    // ════════════════════════════════════════════════════════════════
    //  FITNESS
    // ════════════════════════════════════════════════════════════════
    public function hitungFitness(array $kromosom): float
    {
        if (!$this->validasiSks($kromosom)) {
            return 0.0;
        }

        $f1 = $this->fitnessKecocokan($kromosom);
        $f2 = $this->fitnessProdiSendiri($kromosom);
        $f3 = $this->fitnessKeseimbangan($kromosom);
        $f4 = $this->fitnessBentrok($kromosom);

        return round(
            (0.35 * $f1) +
            (0.25 * $f2) +
            (0.25 * $f3) +
            (0.15 * $f4),
            4
        );
    }

    private function fitnessKecocokan(array $kromosom): float
    {
        $cocok = 0;
        $total = count($kromosom);

        if ($total === 0) {
            return 0;
        }

        foreach ($kromosom as $gen) {
            $dosen = $this->findDosenById($gen['dosen_id']);
            $mk = $this->findMkById($gen['matkul_id']);

            if (!$dosen || !$mk) {
                continue;
            }

            $bidangCocok = in_array($mk['bidang_id'], $dosen['bidang_ids'] ?? [], true);

            if ($bidangCocok) {
                $cocok++;
            }
        }

        return $cocok / $total;
    }

    private function fitnessProdiSendiri(array $kromosom): float
    {
        $home = 0;
        $total = count($kromosom);

        if ($total === 0) {
            return 0;
        }

        foreach ($kromosom as $gen) {
            $dosen = $this->findDosenById($gen['dosen_id']);

            if ($dosen && $dosen['prodi_id'] === $gen['prodi_id']) {
                $home++;
            }
        }

        return $home / $total;
    }

    private function fitnessKeseimbangan(array $kromosom): float
    {
        $sksPerDosen = [];

        foreach ($kromosom as $gen) {
            $did = $gen['dosen_id'];
            $sksPerDosen[$did] = ($sksPerDosen[$did] ?? 0) + $gen['sks'];
        }

        if (empty($sksPerDosen)) {
            return 0;
        }

        $rata = array_sum($sksPerDosen) / count($sksPerDosen);

        $dev = 0;
        foreach ($sksPerDosen as $sks) {
            $dev += abs($sks - $rata);
        }

        $normDev = $dev / (count($sksPerDosen) * max($rata, 1));

        return max(0, 1 - $normDev);
    }

    private function fitnessBentrok(array $kromosom): float
    {
        $jadwal = [];
        $bentrok = 0;
        $total = count($kromosom);

        if ($total === 0) {
            return 0;
        }

        foreach ($kromosom as $gen) {
            $key = $gen['dosen_id'] . '_' . $gen['kelas_id'];

            if (isset($jadwal[$key])) {
                $bentrok++;
            }

            $jadwal[$key] = true;
        }

        return max(0, 1 - ($bentrok / $total));
    }

    // ════════════════════════════════════════════════════════════════
    //  SELEKSI, CROSSOVER, MUTASI
    // ════════════════════════════════════════════════════════════════
    private function tournamentSelection(array $fitnessValues): array
    {
        $jumlahPopulasi = count($this->populasi);

        if ($jumlahPopulasi === 0) {
            return [];
        }

        if ($jumlahPopulasi === 1) {
            return $this->populasi[0];
        }

        $k = min($this->tournamentK, $jumlahPopulasi);
        $pool = array_rand($this->populasi, $k);

        if (!is_array($pool)) {
            $pool = [$pool];
        }

        $best = null;
        $bestFit = -INF;

        foreach ($pool as $idx) {
            $fit = $fitnessValues[$idx] ?? 0;

            if ($fit > $bestFit) {
                $bestFit = $fit;
                $best = $this->populasi[$idx];
            }
        }

        return $best ?? $this->populasi[array_rand($this->populasi)];
    }

    private function crossover(array $parent1, array $parent2): array
    {
        if ((mt_rand() / mt_getrandmax()) > $this->crossoverRate) {
            return [$parent1, $parent2];
        }

        $panjang = min(count($parent1), count($parent2));

        if ($panjang < 2) {
            return [$parent1, $parent2];
        }

        $titikPotong = mt_rand(1, $panjang - 1);

        $anak1 = array_merge(
            array_slice($parent1, 0, $titikPotong),
            array_slice($parent2, $titikPotong)
        );

        $anak2 = array_merge(
            array_slice($parent2, 0, $titikPotong),
            array_slice($parent1, $titikPotong)
        );

        return [
            array_values($anak1),
            array_values($anak2),
        ];
    }

    private function mutasi(array $kromosom): array
    {
        foreach ($kromosom as &$gen) {
            if ((mt_rand() / mt_getrandmax()) <= $this->mutationRate) {
                $mk = $this->findMkById($gen['matkul_id']);

                if (!$mk) {
                    continue;
                }

                $kandidat = $this->getKandidatDosenByMk($mk);

                if (!empty($kandidat)) {
                    $dosenBaru = $kandidat[array_rand($kandidat)];
                    $gen['dosen_id'] = $dosenBaru['id'];
                }
            }
        }

        unset($gen);

        return $kromosom;
    }

    // ════════════════════════════════════════════════════════════════
    //  VALIDASI SKS
    // ════════════════════════════════════════════════════════════════
    private function validasiSks(array $kromosom): bool
    {
        $map = [];

        foreach ($kromosom as $g) {
            $dosenId = $g['dosen_id'] ?? null;
            $sks = $g['sks'] ?? 0;

            if ($dosenId === null) {
                return false;
            }

            $map[$dosenId] = ($map[$dosenId] ?? 0) + $sks;
        }

        foreach ($map as $dosenId => $sksBaru) {
            $d = $this->findDosenById((int) $dosenId);

            if (!$d) {
                return false;
            }

            $total = ($d['sks_aktif'] ?? 0) + $sksBaru;

            if ($total > ($d['max_sks'] ?? 0)) {
                return false;
            }
        }

        return true;
    }

    // ════════════════════════════════════════════════════════════════
    //  HELPER
    // ════════════════════════════════════════════════════════════════
    private function buildIndexes(): void
    {
        $this->dosenIndex = [];
        foreach ($this->dosens as $d) {
            if (isset($d['id'])) {
                $this->dosenIndex[$d['id']] = $d;
            }
        }

        $this->mkIndex = [];
        foreach ($this->mataKuliahs as $m) {
            if (isset($m['id'])) {
                $this->mkIndex[$m['id']] = $m;
            }
        }

        $this->kelasByProdi = [];
        foreach ($this->kelasList as $k) {
            if (isset($k['prodi_id'])) {
                $this->kelasByProdi[$k['prodi_id']][] = $k;
            }
        }
    }

    private function resetRunState(): void
    {
        $this->populasi = [];
        $this->fitnessHistory = [];
        $this->bestKromosom = [];
        $this->bestFitness = 0.0;
    }

    private function getKandidatDosenByMk(array $mk): array
    {
        $mkId = $mk['id'] ?? null;

        if ($mkId === null) {
            return [];
        }

        if (!array_key_exists($mkId, $this->kandidatByMk)) {
            $this->kandidatByMk[$mkId] = $this->cariKandidatDosen($mk);
        }

        return $this->kandidatByMk[$mkId];
    }

    private function findDosenById(int $id): ?array
    {
        return $this->dosenIndex[$id] ?? null;
    }

    private function findMkById(int $id): ?array
    {
        return $this->mkIndex[$id] ?? null;
    }

    public function getBestKromosom(): array
    {
        return $this->bestKromosom;
    }

    public function getBestFitness(): float
    {
        return $this->bestFitness;
    }

    public function getFitnessHistory(): array
    {
        return $this->fitnessHistory;
    }
}