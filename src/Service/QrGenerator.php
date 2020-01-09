<?php


namespace App\Service;


use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;

class QrGenerator
{
    private $QrGenerator;

    /**
     * QrGenerator constructor.
     * @param QrCode $QrGenerator
     */
    public function __construct(QrCode $QrGenerator)
    {
        $this->QrGenerator = $QrGenerator;
    }


    /**
     * @param $code
     * @return QrCode
     */
    public function generateQr($code)
    {
        $qrCode = new QrCode($code);
        $qrCode->setSize(300);
        $qrCode->setWriterByName('png');
        $qrCode->setMargin(10);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        $qrCode->setLogoSize(150, 200);
        $qrCode->setRoundBlockSize(true);
        $qrCode->setValidateResult(false);
        $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
        return $this->QrGenerator;
        // Save it to a file
        //$qrCode->writeFile(__DIR__ . '/qrcode.png');
        //dump($qrCode->writeString());
        //dd($reservation);
    }
}