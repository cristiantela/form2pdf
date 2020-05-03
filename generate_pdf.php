<?php

$data = [];

$template = $_POST['template'];

$settings = json_decode(file_get_contents("templates/$template/index.json"), true);

$attributes = array_map(function ($item) {
  return $item['name'];
}, $settings['form']);

foreach ($attributes as $attribute) {
  $data[$attribute] = $_POST[str_replace(' ', '_', $attribute)];
}

$data['Timestamp'] = date("d/m/Y");

require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
  'tempDir' => __DIR__ . '/temp',
  'setAutoTopMargin' => 'stretch',
  'setAutoBottomMargin' => 'stretch',
]);

$headerFileName = "templates/$template/header.html";
$footerFileName = "templates/$template/footer.html";

if (file_exists($headerFileName)) {
  $mpdf->SetHTMLHeader(file_get_contents($headerFileName));
}

if (file_exists($footerFileName)) {
  $mpdf->SetHTMLFooter(file_get_contents($footerFileName));
}

$content = file_get_contents("templates/$template/content.html");
$outputFilename = isset($settings["output"]) ? $settings["output"] : "";

foreach ($data as $attribute => $value) {
  $content = str_replace("{{{$attribute}}}", $value, $content);
  $outputFilename = str_replace("{{{$attribute}}}", $value, $outputFilename);
}

$mpdf->WriteHTML($content);

if ($outputFilename !== "") {
  if (!file_exists("output/$template/")) {
    mkdir("output/$template/");
  }

  $outputFilename = "output/$template/$outputFilename.pdf";

  $mpdf->Output($outputFilename, \Mpdf\Output\Destination::FILE);

  echo json_encode([ 'filename' => $outputFilename, ]);
} else {
  $mpdf->Output();
}