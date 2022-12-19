<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Game;

class StartNewGameCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:start {word}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a new game';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $word = Str::of($this->argument('word'))->upper();

        if ($word->length() !== 5) {
            $this->error('Word must be 5 characters');

            return self::FAILURE;
        }

        DB::transaction(function () use ($word) {
            Game::all()->each->delete();

            Game::create([
                'word' => (string) $word
            ]);
        });

        $this->info("New game with word $word started.");

        return self::SUCCESS;
    }
}
