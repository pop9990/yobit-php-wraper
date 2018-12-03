# yobit-php-wraper
<h1>Php api wrapper for YoBit.net criptocurency exchange</h1>
This project is designed to help you make your own projects in php that interact with the YoBit API. This project include v3 public api and trade api.Every response is in array form. <br>
<h3>Getting started:</h3>

require 'api.php';<br>
$api = new API($apiKey,$apiSecret); <br><br>
<h2>Public api</h2>
From public endpoints you can stream :info,ticker,depth and trades.<br>
<h3>Get info:</h3>

$info = $api->info();<br><br>

<h3>Get ticker:</h3>
Function  provides statistic data for the last 24 hours.<br><br>
$ticker =$api->ticker($pair);<br><br>
// exemples of pair "ltc_btc"<br>
// you can also send multiple pairs separate with "-". For exemple "ltc_btc-eth_btc"<br>
<h3>Depth</h3>
Function returns information about lists of active orders for selected pairs.<br><br>
$depth = $api->depth($pair);<br>
<h3>Trades</h3>
Function returns information about lists of active orders for selected pairs.<br><br>
$trades = $api->trades($pair);<br><br>
<h2>Trade api</h2>
<h3>getInfo</h3>
Function  returns information about user's balances and priviledges of API-key as well as server time.<br><br>
$getInfo = getInfo($pairs);<br>
<h3>Trade</h3>
<h4>Buy</h4>
$buy=$api->buy($pair, $amount, $rate);
<h4>Sell</h4>
$sell $api->sell($pair, $amount, $rate);
<h3>Open orders</h3>
Function returns list of user's active orders.<br><br>
$openOrders = $api->openOrders($pair);
<h3>OrderInfo</h3>
Informations apout order<br><br>
$orderInfo =$api->orderInfo($order_id);
<h3>CancelOrder</h3>
Cancells the chosen order<br><br>
$cancel = $api->cancelOrder($order_id);
<h3>TradeHistory</h3>
Returns transaction history.<br><br>
$tradeHistory =$api->tradeHistory($pair);
<h3>GetDepositAddress</h3>
Returns deposit address.<br><br>
$getDepositAdress = $api->getDepositAddress($coin);<br><br>
If you need new adress second element is number 1<br><br>
$getDepositAdress = $api->getDepositAddress($coin,1);
<h3>WithdrawCoinsToAddress</h3>
Creates withdrawal request.<br><br>
$withdrawal = $api->withdraw($coin, $amount, $adress);<br><br>
That is all.I hope I was helpful:)

		





.
