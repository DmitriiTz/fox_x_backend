<form method='get' class="freekassa" action='http://www.free-kassa.ru/merchant/cash.php'>
    <input type='hidden' name='m' value='{{ $merchantId }}'>
    <input type='hidden' name='oa' value='{{ $price }}'>
    <input type='hidden' name='o' value='{{ $orderId }}'>
    <input type='hidden' name='s' value='{{ $sign }}'>
    <input type='hidden' name='i' value='{{ $codeCurrency }}'>
    <input type='hidden' name='lang' value='ru'>
    <input type='hidden' name='us_id' value='{{ auth()->user()->id }}'>
    <input type='submit' name='pay' value='Оплатить'>
</form>