<?php

function get_data($station, $element) {
  $url = 'http://www.data.jma.go.jp/gmd/risk/obsdl/show/table';

  //http://www.data.jma.go.jp/gmd/risk/obsdl/ にアクセスして、セッションIDを取得
  $sessionid = '71e1g9vjdm3tq2ea2juv9osmj0';

  $data = array(
    //大阪:s47772, つくば:s47646
    'stationNumList' => "[\"$station\"]",

    //1:日別値, 9:時別値
    'aggrgPeriod' => 9,

    //201:気温, 101:降水量, 401:日照時間, 301:風向・風速, 610:全天日射量, 601:相対湿度 
    'elementNumList' => "[[\"$element\", \"\"]]",

    'interAnnualFlag' => 2,

    //開始年/終了年/開始月/終了月/開始日/終了日
    'ymdList' => "[\"2016\", \"2016\", \"1\", \"12\", \"1\", \"31\"]",
    'optionNumList' => [],
    'downloadFlag' => true,
    'rmkFlag' => 1,
    'disconnectFlag' => 1,
    'youbiFlag' => 0,
    'fukenFlag' => 1,
    'kijiFlag' => 0,
    'huukouFlag' => 0,
    'csvFlag' => 1,
    'jikantaiFlag' => 0,
    'jikantaiList' => [1,24],
    'ymdLiteral' => 1,
    'PHPSESSID' => $sessionid,
  );
  $data = http_build_query($data, "", "&");

  $opts = array(
      'http' => array(
          'method'=>"POST",
          'header'=>"Origin: http://www.data.jma.go.jp\r\n" .
                    "Content-Type: application/x-www-form-urlencoded\r\n",
                    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\r\n",
                    "Accept-Encoding: gzip, deflate\r\n",
                    "Accept-Language: ja,en-US;q=0.8,en;q=0.6\r\n",
                    "Upgrade-Insecure-Requests: 1\r\n",
                    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36\r\n",
                    "Cache-Control: max-age=0\r\n",
                    "Referer: http://www.data.jma.go.jp/gmd/risk/obsdl/\r\n",
                    "Connection: keep-alive\r\n",
          'content'=>"$data"
      )
  );

  $context = stream_context_create($opts);
  $file = file_get_contents($url, false, $context);
  file_put_contents("${station}_${element}.csv", $file);  
}

$elements = [201, 610, 601];
foreach($elements as $element){
  get_data("s47772", $element);
}
