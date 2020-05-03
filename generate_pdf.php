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

$data['Timestamp'] = "Timestamp";

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

$template = file_get_contents("templates/$template/content.html");

foreach ($data as $attribute => $value) {
  $template = str_replace("{{{$attribute}}}", $value, $template);
}

$mpdf->WriteHTML($template);

$mpdf->Output();