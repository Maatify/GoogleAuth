<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2023-03-21
 * Time: 9:48 AM
 */

namespace Maatify\GoogleAuth;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeEnlarge;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeNone;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeShrink;
use Endroid\QrCode\Writer\ConsoleWriter;
use Endroid\QrCode\Writer\GifWriter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Writer\WebPWriter;
use Exception;
use Vectorface\GoogleAuthenticator;

class GoogleAuth extends GoogleAuthenticator
{
    private static self $instance;
    public static function obj(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @throws Exception
     */
    public function GenerateSecret(): string
    {
        return parent::createSecret();
    }

    public function CheckCode(string $secret, string $code): bool
    {
        return parent::verifyCode($secret, $code, 2);
    }

    /**
     * @throws Exception
     */
    public function GetCode(string $secret, int $timeSlice = null): string
    {
        return parent::getCode($secret);
    }

    /**
     * @throws Exception
     */
    public function GetImg(string $username,
        string $secret,
        string $issuer = '',
        int $size = 260,
        string $logo_path = '',
        $foregroundColor = null,
        $backgroundColor = null,
        $logoResizeToHeight = null,
        $logoResizeToWidth = null,
    ): string
    {
        $uri = "otpauth://totp/$username?secret=$secret";

        if (! empty($issuer)) {
            $uri .= "&issuer=$issuer";
        }

        $res = Builder::create()
            ->data($uri)
            ->writer(new PngWriter)
//            ->writer(new SvgWriter())
//            ->writer(new WebPWriter())
//            ->writer(new GifWriter())
//            ->writer(new ConsoleWriter())
            ->size($size)
            ->margin(10)
            ->encoding(new Encoding('UTF-8'));

        $is_logo = false;

        if (! empty($logo_path) && file_exists($logo_path)) {
            $res->logoPath($logo_path);
            $is_logo = true;
        }

        if (! empty($foregroundColor)) {
            $res->foregroundColor($foregroundColor);
        }

        if (! empty($backgroundColor)) {
            $res->backgroundColor($backgroundColor);
        }

        if (! empty($logoResizeToHeight) && $is_logo) {
            $res->logoResizeToHeight($logoResizeToHeight);
        }

        if (! empty($logoResizeToWidth) && $is_logo) {
            $res->logoResizeToWidth($logoResizeToWidth);
        }

//        if($is_logo) {
//            $res->logoPunchoutBackground(false);
//        }

//        $res->roundBlockSizeMode(new RoundBlockSizeModeEnlarge());
        $res->roundBlockSizeMode(new RoundBlockSizeModeMargin());
//        $res->roundBlockSizeMode(new RoundBlockSizeModeNone());
//        $res->roundBlockSizeMode(new RoundBlockSizeModeShrink());

        $result = $res->build();

        //            $result->getMimeType();
        $image = $result->getDataUri();

        return base64_encode(file_get_contents($image));
    }
}