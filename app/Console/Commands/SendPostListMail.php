<?php

namespace App\Console\Commands;

use App\Models\Posts;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Throwable;

class SendPostListMail extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'mail:send {mail* : list of mail to send post list}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sending post list mail';

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
		$this->newline();
		$this->line('<fg=blue>=====Start======');
		$this->newline();
		$this->line('mail list to send post list mail');
		$this->newline();
		//validate each of the input gmail
		foreach ($this->argument('mail') as $key => $mail) {
			$this->line($key . ' => ' . $mail);
			$validator = Validator::make(['mail'=>$mail], [
				'mail'=> 'email',
			]);
			if ($validator->fails()) {
				$this->error($mail . ' is not a email');
				return;
			}
		}
		$this->newline();
		$this->line('Creating post list csv file');
        try{
            $filepath = $this->createCsv();
        }catch(Throwable $th){
            $this->error("Cannot create csv file");
            Log::error($th);
            return;
        }
		
		$this->info('Post list csv file created');
		$this->newLine();

		foreach ($this->argument('mail') as $mail) {
			$this->line('Sending mail to ' . $mail);
			try {
				Mail::raw('Csv file send', function ($message) use ($filepath, $mail) {
					$message->from('noreply@gmail.com', 'Bulletin Board');
					$message->sender('scm.phyoezawaung@gmail.com', 'Phyoe Zaw Aung');
					$message->to($mail);
					$message->subject('Post List');
					$message->priority(3);
					$message->attach($filepath);
				});
			} catch(Throwable $th) {
				$this->error('Some error occur during sending. Please try again');
                Log::error($th);
				return;
			}

			$this->info('Mail successfully send to' . $mail);
			$this->newLine();
		}
		$this->line('<bg=blue;fg=white>Done');
		return 0;
	}

	private function createCsv()
	{
		// get the path of file DownloadCsv
		$storedpath = Storage::path('DownloadCsv');
		// create file name with user name and unique id
		$filename = uniqid() . '.' . 'csv';
		// get the file path that need to store in storage
		$filepath = $storedpath . '/' . $filename;

		$posts = Posts::where('status', 1)->get();

		// fopen create the file and open it
		$file = fopen($filepath, 'w');
		// puts csv title on the created file

		fputcsv($file, [
			'id',
			'title',
			'description',
			'status',
			'create_user_id',
			'updated_user_id',
			'deleted_user_id',
			'deleted-at',
			'updated_at',
			'created_at'
		], ',');

		// loop through all the file and store in file
		foreach ($posts as $post) {
			fputcsv($file, [
				$post->id,
				$post->title,
				$post->description,
				$post->status,
				$post->create_user_id,
				$post->updated_user_id,
				$post->deleted_user_id,
				$this->formatDate($post->deleted_at),
				$this->formatDate($post->updated_at),
				$this->formatDate($post->created_at)
			], ',');
		}
		// close the file
		fclose($file);
		// retrun
		//return Storage::download('DownloadCsv/' . $filename);
		return 'public/DownloadCsv/' . $filename;
	}

	/**
	 * Change the date fomat to (y/m/d)
	 * @param Date $date
	 * @return Date $date
	 *
	 */
	private function formatDate($date)
	{
		if ($date == null) {
			return $date;
		}
		return Carbon::create($date)->format('Y/m/d');
	}
}
