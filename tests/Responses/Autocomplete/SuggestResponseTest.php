<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Tests\Responses\Autocomplete;

use FINDOLOGIC\Api\Definitions\BlockType;
use FINDOLOGIC\Api\Responses\Autocomplete\Properties\Suggestion;
use FINDOLOGIC\Api\Responses\Autocomplete\SuggestResponse;
use PHPUnit\Framework\TestCase;

class SuggestResponseTest extends TestCase
{
    /**
     * Will use a real response that could come from a request. It returns the Object.
     *
     * @param string $filename
     * @return SuggestResponse
     */
    public function getRealResponseData($filename = 'demoResponseSuggest.json')
    {
        // Get contents from a real response locally.
        $realResponseData = file_get_contents(__DIR__ . '/../../Mockdata/Autocomplete/' . $filename);
        return new SuggestResponse($realResponseData);
    }

    public function testResponseWillReturnExpectedLabels()
    {
        $expectedLabels = [
            'siemens',
            'sama',
            'Schulthess',
            'Siemens',
            'Sale%',
            'Staubsauger',
            'Miele Dampfgarer mit Mikrowelle DGM 6600, 60 cm, Edelstahl, SensorTronic, MultiSteam, 1000 W',
            'Bosch Geschirrspüler SPV40E40EU, Vollintegrierbar, 45 cm, 9 Massgedecke, A+',
            'Bosch Geschirrspüler SBA88TD16E PerfectDry XXL Geschirrspüler 60cm mit Home Connect Vollintegrierbar',
            'Bosch Geschirrspüler SBE46MX03E SuperSilence XXL Geschirrspüler 60cm Vollintegrierbar mit VarioScharnier',
        ];
        $response = $this->getRealResponseData();

        $actualLabels = array_map(function ($suggestion) {
            /** @var Suggestion $suggestion */
            return $suggestion->getLabel();
        }, $response->getSuggestions());
        $this->assertSame($expectedLabels, $actualLabels);
    }

    public function testResponseWillReturnExpectedBlocks()
    {
        $expectedBlocks = [
            'suggest',
            'suggest',
            'vendor',
            'vendor',
            'cat',
            'cat',
            'product',
            'product',
            'product',
            'product',
        ];
        $response = $this->getRealResponseData();

        $actualBlocks = array_map(function ($suggestion) {
            /** @var Suggestion $suggestion */
            return $suggestion->getBlock();
        }, $response->getSuggestions());
        $this->assertSame($expectedBlocks, $actualBlocks);
    }

    public function testResponseWillReturnExpectedFrequencies()
    {
        $expectedFrequencies = [
            '617',
            '552',
            '0',
            '0',
            '0',
            '0',
            '1',
            '1',
            '1',
            '1',
        ];
        $response = $this->getRealResponseData();

        $actualFrequencies = array_map(function ($suggestion) {
            /** @var Suggestion $suggestion */
            return $suggestion->getFrequency();
        }, $response->getSuggestions());
        $this->assertSame($expectedFrequencies, $actualFrequencies);
    }

    public function testResponseWillReturnExpectedImageUrls()
    {
        $expectedImageUrls = [
            null,
            null,
            'https://www.blubbergurken.io/images/gallery/Findologic/vendor/schulthess.png',
            'https://www.blubbergurken.io/images/gallery/Findologic/vendor/siemens.png',
            null,
            null,
            'https://www.blubbergurken.io/m4dlk92njtak/item/images/10248/full/20000122510-000-00-20000122510.jpg',
            'https://www.blubbergurken.io/m4dlk92njtak/item/images/10598/full/MCSA01744491-G5678-SPV40E40EU-1182799-def-0.jpg',
            'https://www.blubbergurken.io/m4dlk92njtak/item/images/11563/full/MCSA01757436-G6327-SBA88TD16E-1194162-def-0.jpg',
            'https://www.blubbergurken.io/m4dlk92njtak/item/images/11564/full/MCSA01732370-G5009-SBE46MX03E-1173959-def-0.jpg',
        ];
        $response = $this->getRealResponseData();

        $actualImageUrls = array_map(function ($suggestion) {
            /** @var Suggestion $suggestion */
            return $suggestion->getImageUrl();
        }, $response->getSuggestions());
        $this->assertSame($expectedImageUrls, $actualImageUrls);
    }

    public function testResponseWillReturnExpectedPrice()
    {
        $expectedPrice = [
            null,
            null,
            null,
            null,
            null,
            null,
            2305.75,
            678.00,
            1355.65,
            866.00,
        ];
        $response = $this->getRealResponseData();

        $actualPrice = array_map(function ($suggestion) {
            /** @var Suggestion $suggestion */
            return $suggestion->getPrice();
        }, $response->getSuggestions());
        $this->assertSame($expectedPrice, $actualPrice);
    }

    public function testResponseWillReturnExpectedIdentifier()
    {
        $expectedIdentifier = [
            null,
            null,
            null,
            null,
            null,
            null,
            '10248',
            '10598',
            '11563',
            '11564',
        ];
        $response = $this->getRealResponseData();

        $actualIdentifier = array_map(function ($suggestion) {
            /** @var Suggestion $suggestion */
            return $suggestion->getIdentifier();
        }, $response->getSuggestions());
        $this->assertSame($expectedIdentifier, $actualIdentifier);
    }

    public function testResponseWillReturnExpectedBasePrice()
    {
        $expectedBasePrice = [
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            1355.65,
            null,
        ];
        $response = $this->getRealResponseData();

        $actualBasePrice = array_map(function ($suggestion) {
            /** @var Suggestion $suggestion */
            return $suggestion->getBasePrice();
        }, $response->getSuggestions());
        $this->assertSame($expectedBasePrice, $actualBasePrice);
    }

    public function testResponseWillReturnExpectedBasePriceUnit()
    {
        $expectedBasePriceUnit = [
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            '€',
            null,
        ];
        $response = $this->getRealResponseData();

        $actualBasePriceUnit = array_map(function ($suggestion) {
            /** @var Suggestion $suggestion */
            return $suggestion->getBasePriceUnit();
        }, $response->getSuggestions());
        $this->assertSame($expectedBasePriceUnit, $actualBasePriceUnit);
    }

    public function testResponseWillReturnExpectedUrl()
    {
        $expectedUrl = [
            null,
            null,
            null,
            null,
            null,
            null,
            'http://www.blubbergurken.io/de/kochen-und-backen/miele-dampfgarer-mit-mikrowelle-dgm-6600-edelstahl/a-10248/',
            'http://www.blubbergurken.io/de/bosch-geschirrspueler-spv40e40eu/a-10598/',
            'http://www.blubbergurken.io/de/bosch-geschirrspueler-sba88td16e/a-11563/',
            'http://www.blubbergurken.io/de/bosch-geschirrspueler-sbe46mx03e/a-11564/',
        ];
        $response = $this->getRealResponseData();

        $actualUrl = array_map(function ($suggestion) {
            /** @var Suggestion $suggestion */
            return $suggestion->getUrl();
        }, $response->getSuggestions());
        $this->assertSame($expectedUrl, $actualUrl);
    }

    public function testResponseWillReturnExpectedOrdernumber()
    {
        $expectedOrdernumber = [
            null,
            null,
            null,
            null,
            null,
            null,
            'MDR-007',
            null,
            null,
            null
        ];
        $response = $this->getRealResponseData();

        $actualOrdernumber = array_map(function ($suggestion) {
            /** @var Suggestion $suggestion */
            return $suggestion->getOrdernumber();
        }, $response->getSuggestions());
        $this->assertSame($expectedOrdernumber, $actualOrdernumber);
    }

    public function testResponseWillReturnFilteredSuggestions()
    {
        $expectedBlockType = [
            'suggest',
            'suggest',
            'cat',
            'cat'
        ];

        $response = $this->getRealResponseData();

        $blockFilter = [BlockType::SUGGEST_BLOCK, BlockType::CAT_BLOCK];

        $actualBlockType = array_map(function ($suggestion) {
            /** @var Suggestion $suggestion */
            return $suggestion->getBlock();
        }, $response->getFilteredSuggestions($blockFilter));
        $this->assertSame($expectedBlockType, $actualBlockType);
    }
}
