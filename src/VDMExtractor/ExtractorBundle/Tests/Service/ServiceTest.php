<?php

namespace VDMExtractor\ExtractorBundle\Tests\Service;

use EVDMExtractor\ExtractorBundle\Service\ExtractorService;

use PHPUnit_Framework_TestCase;

use DateTime;

/**
 * This class will test the service behaviour
 */
class ServiceTest extends PHPUnit_Framework_TestCase
{
    protected $sampleNodeText = "Aujourd'hui, en plein cours de Zumba, le coach a dû refaire son lacet. Devinez qui a cru que ça faisait partie de la chorégraphie ? VDM #8318830 101 commentaires je valide, c'est une VDM (3316) - tu l'as bien mérité (10207) Le 19/07/2014 à 22:32 - inclassable - par Const59 (femme)";

    /**
     * Test: Extract author name
     */
    public function testExtractAuthor()
    {
        $collectorService = new ExtractorService();

        $actualAuthor    = 'Const59';
        $extractedAuthor = $collectorService->extractAuthor($this->sampleNodeText);

        $this->assertEquals($actualAuthor, $extractedAuthor);
    }

    /**
     * Test: Extract date
     */
    public function testExtractDate()
    {
        $collectorService = new ExtractorService();

        $actualDate    = '19/07/2014';
        $extractedDate = $collectorService->extractDate($this->sampleNodeText);

        $this->assertEquals($actualDate, $extractedDate);
    }

    /**
     * Test: Extract time
     */
    public function testExtractTime()
    {
        $collectorService = new ExtractorService();

        $actualTime    = '22:32';
        $extractedTime = $collectorService->extractTime($this->sampleNodeText);

        $this->assertEquals($actualTime, $extractedTime);
    }

    /**
     * Test: Extract content
     */
    public function testExtractContent()
    {
        $collectorService = new ExtractorService();

        $actualContent    = "Aujourd'hui, en plein cours de Zumba, le coach a dû refaire son lacet. Devinez qui a cru que ça faisait partie de la chorégraphie ?";
        $extractedContent = $collectorService->extractContent($this->sampleNodeText);

        $this->assertEquals($actualContent, $extractedContent);
    }
}
