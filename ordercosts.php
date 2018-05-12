<?php
$asosCustomerId = ''; // Enter your ASOS customer ID here (can be obtained from ASOS API call)

// Fetch the API
function makeOrderAPICall($url = '') {
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer << ENTER VALID BEARER AUTH TOKEN HERE >>",
        "Accept: application/json, text/javascript, */*; q=0.01",
        "Accept-Encoding: gzip, deflate, br",
        "Accept-Language: en-US,en;q=0.9,nl;q=0.8",
        "Cache-Control: no-cache",
        "Host: my.asos.com",
        "Origin: http://m.asos.com",
        "User-Agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Mobile Safari/537.36",
        "asos-c-ismobile: true",
        "asos-c-istablet: false",
        "asos-c-name: Asos.Customer.MyAccount.Web.Ui",
        "asos-c-plat: Web",
        "asos-c-ver: 1.1.1508.0"
    ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
    die("cURL Error #:" . $err);
    } else {
    return json_decode($response, 1);
    }
}

// Get all orders
$orders = makeOrderAPICall('https://my.asos.com/api/commerce/myaccount/v1/customers/'.$asosCustomerId.'/orders?limit=100&store=ROE&lang=en-GB');

// For each order, fetch costs
foreach($orders['orderSummaries'] as $i => $orderSummary) {
    $orderDetailed = makeOrderAPICall('https://my.asos.com/api/commerce/myaccount/v1/customers/'.$asosCustomerId.'/orders/'.$orderSummary['orderReference'].'?store=ROE&lang=en-GB');
    $orderTotalCosts[] = $orderDetailed['orderTotal']['total']['value'];
}

// List all order costs
$asosSpent = array_sum($orderTotalCosts);
print_r('You\'ve spent <strong>'.$asosSpent.'</strong> on ASOS');
?>