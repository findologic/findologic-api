<?php

namespace FINDOLOGIC\Tests\Objects;

use FINDOLOGIC\Definitions\BlockType;
use FINDOLOGIC\Objects\JsonResponse;
use PHPUnit\Framework\TestCase;

class JsonResponseTest extends TestCase
{
    /**
     * Will use a real response that could come from a request. It returns the Object.
     *
     * @param string $filename
     * @return JsonResponse
     */
    public function getRealResponseData($filename = 'demoResponseSuggest.json')
    {
        // Get contents from a real response locally.
        $realResponseData = file_get_contents(__DIR__ . '/../../Mockdata/' . $filename);
        return new JsonResponse($realResponseData);
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

        foreach ($response->getSuggestions() as $key => $suggestion) {
            $this->assertEquals($expectedLabels[$key], $suggestion->getLabel());
        }
    }

    public function testResponseWillReturnExpectedBlocks()
    {
        $expectedBlocks = [
            BlockType::SUGGEST_BLOCK,
            BlockType::SUGGEST_BLOCK,
            BlockType::VENDOR_BLOCK,
            BlockType::VENDOR_BLOCK,
            BlockType::CAT_BLOCK,
            BlockType::CAT_BLOCK,
            BlockType::PRODUCT_BLOCK,
            BlockType::PRODUCT_BLOCK,
            BlockType::PRODUCT_BLOCK,
            BlockType::PRODUCT_BLOCK
        ];
        $response = $this->getRealResponseData();

        foreach ($response->getSuggestions() as $key => $suggestion) {
            $this->assertEquals($expectedBlocks[$key], $suggestion->getBlock());
        }
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

        foreach ($response->getSuggestions() as $key => $suggestion) {
            $this->assertEquals($expectedFrequencies[$key], $suggestion->getFrequency());
        }
    }

    public function testResponseWillReturnExpectedImageUrls()
    {
        $expectedImageUrls = [
            '',
            '',
            'https://www.blubbergurken.io/images/gallery/Findologic/vendor/schulthess.png',
            'https://www.blubbergurken.io/images/gallery/Findologic/vendor/siemens.png',
            '',
            '',
            'https://www.blubbergurken.io/m4dlk92njtak/item/images/10248/full/20000122510-000-00-20000122510.jpg',
            'https://www.blubbergurken.io/m4dlk92njtak/item/images/10598/full/MCSA01744491-G5678-SPV40E40EU-1182799-def-0.jpg',
            'https://www.blubbergurken.io/m4dlk92njtak/item/images/11563/full/MCSA01757436-G6327-SBA88TD16E-1194162-def-0.jpg',
            'https://www.blubbergurken.io/m4dlk92njtak/item/images/11564/full/MCSA01732370-G5009-SBE46MX03E-1173959-def-0.jpg',
        ];
        $response = $this->getRealResponseData();

        foreach ($response->getSuggestions() as $key => $suggestion) {
            $this->assertEquals($expectedImageUrls[$key], $suggestion->getImageUrl());
        }
    }

    public function testResponseWillReturnExpectedPrice()
    {
        $expectedPrice = [
            0,
            0,
            0,
            0,
            0,
            0,
            2305.75,
            678.00,
            1355.65,
            866.00,
        ];
        $response = $this->getRealResponseData();

        foreach ($response->getSuggestions() as $key => $suggestion) {
            $this->assertEquals($expectedPrice[$key], $suggestion->getPrice());
        }
    }

    public function testResponseWillReturnExpectedIdentifier()
    {
        $expectedIdentifier = [
            '',
            '',
            '',
            '',
            '',
            '',
            '10248',
            '10598',
            '11563',
            '11564',
        ];
        $response = $this->getRealResponseData();

        foreach ($response->getSuggestions() as $key => $suggestion) {
            $this->assertEquals($expectedIdentifier[$key], $suggestion->getIdentifier());
        }
    }

    public function testResponseWillReturnExpectedBasePrice()
    {
        $expectedBasePrice = [
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            1355.65,
            0,
        ];
        $response = $this->getRealResponseData();

        foreach ($response->getSuggestions() as $key => $suggestion) {
            $this->assertEquals($expectedBasePrice[$key], $suggestion->getBasePrice());
        }
    }

    public function testResponseWillReturnExpectedBasePriceUnit()
    {
        $expectedBasePriceUnit = [
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '€',
            '',
        ];
        $response = $this->getRealResponseData();

        foreach ($response->getSuggestions() as $key => $suggestion) {
            $this->assertEquals($expectedBasePriceUnit[$key], $suggestion->getBasePriceUnit());
        }
    }

    public function testResponseWillReturnExpectedUrl()
    {
        $expectedUrl = [
            '',
            '',
            '',
            '',
            '',
            '',
            'http://www.blubbergurken.io/de/kochen-und-backen/miele-dampfgarer-mit-mikrowelle-dgm-6600-edelstahl/a-10248/',
            'http://www.blubbergurken.io/de/bosch-geschirrspueler-spv40e40eu/a-10598/',
            'http://www.blubbergurken.io/de/bosch-geschirrspueler-sba88td16e/a-11563/',
            'http://www.blubbergurken.io/de/bosch-geschirrspueler-sbe46mx03e/a-11564/',
        ];
        $response = $this->getRealResponseData();

        foreach ($response->getSuggestions() as $key => $suggestion) {
            $this->assertEquals($expectedUrl[$key], $suggestion->getUrl());
        }
    }

    public function testResponseWillReturnFilteredSuggestions()
    {
        $expectedBlockType = [
            BlockType::SUGGEST_BLOCK,
            BlockType::SUGGEST_BLOCK,
            BlockType::CAT_BLOCK,
            BlockType::CAT_BLOCK
        ];

        $response = $this->getRealResponseData();

        $blockFilter = [BlockType::SUGGEST_BLOCK, BlockType::CAT_BLOCK];

        foreach ($response->getFilteredSuggestions($blockFilter) as $key => $suggestion) {
            $this->assertEquals($expectedBlockType[$key], $suggestion->getBlock());
        }
    }
}
