<?php
namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Models\Auction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class CloseExpiredAuctions extends Command
{
protected $signature = 'auctions:close-expired';
protected $description = 'Close auctions whose end_at has passed and notify winners';


public function handle()
{
$now = Carbon::now();
$auctions = Auction::whereIn('status',['live','scheduled'])->where('end_at','<=',$now)->get();


foreach($auctions as $auction){
DB::beginTransaction();
try{
$highest = $auction->bids()->orderBy('amount','desc')->first();
if($highest){
$auction->status = 'sold';
$auction->winner_id = $highest->user_id;
$auction->final_price = $highest->amount;
$auction->save();


// mark car listing sold
$auction->car()->update(['status' => 'sold']);


// send notifications (database & email)
$winner = $highest->user;
$seller = $auction->car->user;


//$winner->notify(new \App\Notifications\AuctionWonNotification($auction));
//$seller->notify(new \App\Notifications\AuctionSoldNotification($auction));


} else {
// no bids, mark closed
$auction->status = 'closed';
$auction->save();
}
DB::commit();
} catch(\Exception $e){
DB::rollBack();
$this->error('Error closing auction id '.$auction->id.': '.$e->getMessage());
}
}


$this->info('Finished closing expired auctions.');
}
}