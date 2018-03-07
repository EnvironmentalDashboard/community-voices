<?php

namespace CommunityVoices\App\Api\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class QuoteTest extends TestCase
{
    public function test_Post_Quote_Upload()
    {
        $text = 'I always close my eyes when I ...';
        $attribution = 'Lars Dreith';
        $subAttribution = 'Oberlin College 2020';
        $dateRecorded = 'January 24th, 2018';
        $publicDocumentLink = '';
        $sourceDocumentLink = '';

        $request = new Request($query = [
              'text' => $text,
              'attribution' => $attribution,
              'subAttribution' => $subAttribution,
              'dateRecorded' => $dateRecorded,
              'publicDocumentLink' => $publicDocumentLink,
              'sourceDocumentLink' => $sourceDocumentLink
          ]);

        $quoteUpload = $this->createMock(Service\QuoteUpload::class);

        $quoteUpload
          ->expects($this->once())
          ->method('newQuote')
          ->with(
              $this->equalTo($text),
                  $this->equalTo($attribution),
                  $this->equalTo($subAttribution),
                  $this->equalTo($dateRecorded),
                  $this->equalTo($publicDocumentLink),
                  $this->equalTo($sourceDocumentLink)
          );

        $quoteController = new Quote($quoteUpload);

        $quoteController->postQuote($request);
    }

    public function test_Get_All_Quote()
    {
        $creatorIDs = [1, 3];

        $request = new Request($query = ['creatorIDs' => $creatorIDs]);

        $getAllQuote = $this->createMock(Service\QuoteLookup::class);

        $getAllQuote
          ->expects($this->once())
          ->method('findAll')
          ->with($this->equalTo($creatorIDs));

        $quoteController = new Quote($getAllQuote);

        $quoteController->getAllQuote($request);
    }
}
