<?php

namespace App\Console\Commands;

use App\Collections\PhotoCollection;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SeedPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photo:seed {--days=60 : Number of days BEFORE today to seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create fake photos (for demo purpose)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PhotoCollection $photos)
    {
        parent::__construct();

        $this->photos = $photos;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (env('APP_ENV') !== 'demo') {
            $this->error('APP_ENV must be set to "demo" for the seeding to run.');
            return;
        }

        $this->getPeriod()
            ->filter([$this, 'keepDate'])
            ->diff($this->existingDates())
            ->map([$this, 'generateFilename'])
            ->tap([$this, 'startProgressbar'])
            ->each([$this, 'downloadSource'])
            ->tap([$this, 'stopProgressbar']);
    }

    public function getPeriod() : Collection
    {
        $start = Carbon::today()->subDays($this->option('days'));
        $end = Carbon::today();

        return collect(
            CarbonPeriod::since($start)->until($end)
        );
    }

    public function keepDate(Carbon $date) : bool
    {
        $schema = decbin($date->weekOfYear);
        $position = $date->dayOfWeek % strlen($schema);
        $keep = $schema[$position];

        return boolval($keep);
    }

    public function existingDates() : Collection
    {
        return $this->photos->pluck('date');
    }

    public function generateFilename(Carbon $date) : string
    {
        return Str::finish(env('PHOTOS_ROOT'), '/') . $date->toDateString() . '.jpg';
    }

    public function startProgressbar(Collection $filenames) : void
    {
        if ($filenames->isEmpty()) {
            $this->info('No photo to download.');
            return;
        }

        $interval = CarbonInterval::seconds($filenames->count() * 11);
        $duration = $interval->cascade()->forHumans();

        $this->info($filenames->count() . ' photos to download in ' . $duration . '.');

        $this->bar = $this->output->createProgressBar($filenames->count());
    }

    public function downloadSource(string $filename) : void
    {
        file_put_contents($filename, fopen("https://source.unsplash.com/random/?people", 'r'));

        $this->bar->advance();
        sleep(10);
    }

    public function stopProgressbar(Collection $filenames) : void
    {
        if ($filenames->isEmpty()) {
            return;
        }

        $this->bar->finish();
        $this->line('');
    }
}
