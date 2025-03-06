protected $fillable = [
    'customer_id',
    'type',
    'amount',
    'description',
    'price_id',
    'quantity'
];

public function price()
{
    return $this->belongsTo(PriceList::class);
} 