<?php

 namespace CommunityVoices\App\Api\Component;
 use Symfony\Component\HttpFoundation;

 class FileProcessor {
     const ERR_NO_ATTRIBUTIONS = 'The source table must provide an attribution column';
     const ERR_NO_CONTENT_CATEGORIES = 'The quotes table must provide a content category column';
     const ERR_NO_IDENTIFIER = 'You are missing an identifier column';
     const ERR_NO_QUOTE = 'You are missing a quote (edited text) column';
     const ERR_MISSING_ATTRIBUTION = 'Quotes must have an attribution.';
     const ERR_MISSING_CONTENT_CATEGORY = 'Must provide a potential content category.';
     const ERR_WRONG_IDENTIFIER = 'This identifier does not match any quote identifiers.';
     const WARNING_EMPTY_QUOTE = "Warning! You have empty quotes. Do you want to procede?";

     const BATCH_QUOTE_DATA = [
         'Identifier',
         'Original Quote',
         'Edited Quotes',
         'Url link to Photo',
         'Content Category 1',
         'Content Category 2',
         'Content Category 3',
         'Tag 1',
         'Tag 2',
         'Tag 3',
         'Tag 4',
         'Sponsor',
         'Create a Slide'
     ];

     const BATCH_SOURCE_DATA = [
         'Identifier',
         'Interviewer',
         'Interviewee',
         'Interview Date',
         'Attribution',
         'Sub-Attribution',
         'Organization ',
         'Topic of Interview',
         'Email',
         'Telephone',
         'Course or Project',
         'Type of Interview'
     ];
     public function tailRead($filepath, $lines, $endLine = PHP_INT_MAX, $startDate = false, $endDate = false) {
         // from error page
     }
     public function csvReadBatch($sourceFilePath, $quoteFilePath) {
         $columnNameErrors = [];
         $columnNameWarnings = ["unrecognized" => [], "expected" => []];
         $unpairedQuotes = [];
         $formattedSourceNames = array_map(array($this,'cleanString'),self::BATCH_SOURCE_DATA);
         $formattedQuoteNames = array_map(array($this,'cleanString'),self::BATCH_QUOTE_DATA);
         // There will be errors/warnings on three levels: top level (column names), source level (relating to source info), quotes level (relating to quotes info)
         // any errors on the top level will require a re upload

         // NOTE: Skip the second row of the source file due to format given by John
         // first pass through source sheet, creating entry for each interview. Later we will add list of quotes for each interview
         if (($f = fopen($sourceFilePath, "r")) !== FALSE) {
           $columnOrder = []; // used to track column locations since we are going entirely by name instead of order
           $givenColumnNames = fgetcsv($f);
           foreach ($givenColumnNames as $column) {
               $formattedColumn = $this->cleanString($column);
               if(in_array($formattedColumn,$formattedSourceNames)) {
                   array_push($columnOrder,$formattedColumn);
               } else {
                   array_push($columnOrder, "unrecognized");
                   array_push($columnNameWarnings["unrecognized"],["item" => [$formattedColumn]]); // NOTE: use this format with "item" to make it easier to call card.xslt (unless you want to change that)
               }
           }

           if(!in_array("attribution",$columnOrder)) array_push($columnNameErrors,["item" => [self::ERR_NO_ATTRIBUTIONS]]);
           if(!in_array("identifier",$columnOrder)) array_push($columnNameErrors,["item" => [self::ERR_NO_IDENTIFIER]]);

           foreach ($formattedSourceNames as $expected) {
               if (!in_array($expected,$columnOrder))
                 array_push($columnNameWarnings["expected"],["item" => [ $expected]]);
           }

           // These are both major errors. There is no need to parse the rest of the sheet as uploading will not be allowed if one of these errors occurs
           $sheetData = [];
           if (empty($columnNameErrors))  {
               while (($data = fgetcsv($f)) !== FALSE) {
                   $dataToAdd = ['errors' => []];
                   $identifier = false;
                   for ($i = 0; $i < count($columnOrder); $i++) {
                       $columnName = $columnOrder[$i]; // XML requires no spaces
                       $currentColumnData = $data[$i];
                       if($columnName != "unrecognized") {
                           $originalName = self::BATCH_SOURCE_DATA[$i];
                           if($columnName=="identifier") $identifier = $this->cleanString($currentColumnData); // XML requirements
                           else {
                               // this is a minor error (missing attribution for entry) that the user can fix on the confirmation page
                               if ($columnName=="attribution" && ! $currentColumnData) {
                                   $dataToAdd['errors']['attribution'] = self::ERR_MISSING_ATTRIBUTION; // NOTE: Unlike for top level errors, we don't need to use "item" as a seperator since card.xslt will not be called
                               }
                               else array_push($dataToAdd,['item' => ['originalName' => $originalName,'columnData' => $currentColumnData]]);
                           }
                       }
                   }
                   if($identifier) {
                       $sheetData[$identifier] = $dataToAdd;
                       $sheetData[$identifier]["quotes"] = []; // allows quotes related to source info
                   }
               }
           }

           fclose($f);
        }

         if (($f= fopen($quoteFilePath, "r")) !== FALSE) {
             $columnOrder = []; // used to track column locations since we are going entirely by name instead of order
             $givenColumnNames = fgetcsv($f);
             foreach ($givenColumnNames as $column) {
                 $formattedColumn = $this->cleanString($column);
                 if(in_array($formattedColumn,$formattedQuoteNames)) {
                     array_push($columnOrder,$formattedColumn);
                 } else {
                     array_push($columnOrder,"unrecognized");
                     array_push($columnNameWarnings["unrecognized"],["item" => [$formattedColumn]]);
                 }
             }


             if(!in_array("contentcategory1",$columnOrder)) array_push($columnNameErrors,["item" => [self::ERR_NO_CONTENT_CATEGORIES]]);
             if(!in_array("editedquotes",$columnOrder)) array_push($columnNameErrors,["item" => [self::ERR_NO_QUOTE]]);
             if(!in_array("identifier",$columnOrder)) array_push($columnNameErrors,["item" => [self::ERR_NO_IDENTIFIER]]);
             // These are both major errors. There is no need to parse the rest of the sheet as uploading will not be allowed if one of these errors occurs

             foreach ($formattedQuoteNames as $expected) {
                 if (!in_array($expected,$columnOrder))
                   array_push($columnNameWarnings["expected"],["item" => [ $expected]]);
             }

             if (empty($columnNameErrors))  {
                 while (($data = fgetcsv($f)) !== FALSE) {
                     $dataToAdd = ['errors' => [], 'warnings' => []];
                     $identifier = false;
                     for ($i = 0; $i < count($columnOrder); $i++) {
                         $columnName = str_replace(" ","",$columnOrder[$i]); // XML requires no spaces
                         $currentColumnData = $data[$i];
                         if($columnName != "unrecognized") {
                             $originalName = self::BATCH_QUOTE_DATA[$i];
                             if($columnName=="identifier" && array_key_exists($this->cleanString($currentColumnData),$sheetData)) { // if valid identifier
                                $identifier = $this->cleanString($currentColumnData);
                             }
                             else {
                                 if ($columnName=="contentcategory1" && ! $currentColumnData) $dataToAdd['errors']['contentCat'] = self::ERR_MISSING_CONTENT_CATEGORY;
                                 else if ($columnName=="editedquotes" && ! $currentColumnData) $dataToAdd['warnings']['emptyQuote'] = self::WARNING_EMPTY_QUOTE;
                                 else array_push($dataToAdd,['originalName' => $originalName,'columnData' => $currentColumnData]);
                             }
                         }
                     }

                     if($identifier===false) {
                         array_push($unpairedQuotes,['item'=>$dataToAdd]);
                     } else {
                         array_push($sheetData[$identifier]["quotes"],['item'=>$dataToAdd]);
                     }
                 }
             }
         }
         return [$sheetData,$columnNameWarnings,$columnNameErrors,$unpairedQuotes];
     }
     private function cleanString($s) {
         return strtolower(preg_replace(["/[^a-zA-Z0-9]/","/\s/"], "", $s));
     }
 }
