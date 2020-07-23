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
         'identifier',
         'original quote',
         'edited quotes',
         'url link to photo',
         'content category 1',
         'content category 2',
         'content category 3',
         'tag 1',
         'tag 2',
         'tag 3',
         'tag 4',
         'sponsor',
         'create a slide'
     ];

     const BATCH_SOURCE_DATA = [
         'identifier',
         'interviewer',
         'interviewee',
         'interviewDate',
         'attribution',
         'subAttribution',
         'organization ',
         'topic of interview',
         'email',
         'telephone',
         'courseOrProject',
         'interviewType'
     ];
     public function tailRead($filepath, $lines, $endLine = PHP_INT_MAX, $startDate = false, $endDate = false) {
         // from error page
     }
     public function csvReadBatch($sourceFilePath, $quoteFilePath) {
         $columnNameErrors = [];
         $columnNameWarnings = [];
         $unpairedQuotes = [];
         $listOfQuotes = ["errors" => [], "warnings" => [], "notPaired" => []];
         // There will be errors/warnings on three levels: top level (column names), source level (relating to source info), quotes level (relating to quotes info)

         // NOTE: Skip the second row of the source file
         // first pass through source sheet, creating entry for each interview. Later we will add list of quotes for each interview
         if (($f = fopen($sourceFilePath, "r")) !== FALSE) {
           $columnOrder = []; // used to track column locations since we are going entirely by name instead of order
           $givenColumnNames = fgetcsv($f);
           foreach ($givenColumnNames as $column) {
               if(in_array(strtolower($column),self::BATCH_SOURCE_DATA)) {
                   array_push($columnOrder,strtolower($column));
               } else {
                   array_push($columnOrder, "unrecognized");
                   array_push($columnNameWarnings, "column " . $column . " is unrecognized.");
               }
               // code below if we decide to go by contained instead of by exact match
               /* $matchFound = false;

               foreach(self::BATCH_SOURCE_DATA as $field) {
                   if (str_contains(strtolower($column),strtolower($field))) {
                       $matchFound = true;
                       array_push($columnOrder,$field);
                       break;
                   }
               }
               if($matchFound == false) {
                   array_push($listOfQuotes["warnings"],"column " . $column . " is unrecognized.");
               } */
           }

           if(!in_array("attribution",$columnOrder)) $columnNameErrors["attributions"]=self::ERR_NO_ATTRIBUTIONS;
           if(!in_array("identifier",$columnOrder)) $columnNameErrors["identifiers"]=self::ERR_NO_IDENTIFIER;
           foreach (self::BATCH_SOURCE_DATA as $expected) {
               if (! array_key_exists($expected,$columnNameErrors) && !in_array($expected,$columnOrder))
                 array_push($columnNameWarnings,"column " . $expected . " is an expected column and could not be found.");
           }

           // These are both major errors. There is no need to parse the rest of the sheet as uploading will not be allowed if one of these errors occurs
           $sheetData = [];
           if (empty($columnNameErrors))  {
               while (($data = fgetcsv($f)) !== FALSE) {
                   $dataToAdd = ['errors' => []];
                   $identifier = false;
                   for ($i = 0; $i < count($columnOrder); $i++) {
                       $columnName = $columnOrder[$i];
                       $currentColumnData = $data[$i];
                       if($columnName != "unrecognized") {
                           if(strtolower($columnName)=="identifier") $identifier = $currentColumnData;
                           else {
                               // this is a minor error (missing attribution for entry) that the user can fix on the confirmation page
                               if (strtolower($columnName)=="attribution" && ! $currentColumnData) {
                                   array_push($dataToAdd['errors'],self::ERR_MISSING_ATTRIBUTION);
                               }
                               else $dataToAdd[$columnName] = $currentColumnData;
                           }
                       }
                   }
                   $sheetData[$identifier] = $dataToAdd;
                   $sheetData[$identifier]["quotes"] = []; // allows quotes related to source info
               }
           }

           fclose($f);
        }

         if (($f= fopen($quoteFilePath, "r")) !== FALSE) {
             $columnOrder = []; // used to track column locations since we are going entirely by name instead of order
             $givenColumnNames = fgetcsv($f);
             foreach ($givenColumnNames as $column) {
                 if(in_array(strtolower($column),self::BATCH_QUOTE_DATA)) {
                     array_push($columnOrder,strtolower($column));
                 } else {
                     array_push($columnNameWarnings,"column " . $column . " is unrecognized.");
                     array_push($columnOrder,"unrecognized");
                 }
                 // code below if we decide to go by contained instead of by exact match
                 /*
                 $matchFound = false;
                 foreach(self::BATCH_QUOTE_DATA as $field) {
                     if (str_contains(strtolower($column),strtolower($field))) {
                         $matchFound = true;
                         array_push($columnOrder,$field);
                         break;
                     }
                 }
                 if($matchFound == false) {
                     array_push($listOfQuotes["warnings"],"column " . $column . " is unrecognized.");
                 } */
             }

             if(!in_array("content category 1",$columnOrder)) $columnNameErrors["content categories"]=self::ERR_NO_CONTENT_CATEGORIES;
             if(!in_array("edited quotes",$columnOrder)) $columnNameErrors["quotes"]=self::ERR_NO_QUOTE;
             if(!in_array("identifier",$columnOrder)) $columnNameErrors["identifiers"]=self::ERR_NO_IDENTIFIER;
             // These are both major errors. There is no need to parse the rest of the sheet as uploading will not be allowed if one of these errors occurs

             foreach (self::BATCH_QUOTE_DATA as $expected) {
                 if (! array_key_exists($expected,$columnNameErrors) && !in_array($expected,$columnOrder))
                   array_push($columnNameWarnings,"column " . $expected . " is an expected column and could not be found.");
             }

             if (empty($columnNameErrors))  {
                 while (($data = fgetcsv($f)) !== FALSE) {
                     $dataToAdd = ['errors' => [], 'warnings' => []];
                     $identifier = false;
                     for ($i = 0; $i < count($columnOrder); $i++) {
                         $columnName = $columnOrder[$i];
                         $currentColumnData = $data[$i];
                         if($columnName != "unrecognized") {
                             if(strtolower($columnName)=="identifier" && array_key_exists($currentColumnData,$sheetData)) {
                                 $identifier = $currentColumnData; // if this identifier lines up with source information validly
                             }
                             else {
                                 if (strtolower($columnName)=="content category 1" && ! $currentColumnData) array_push($dataToAdd['errors'],self::ERR_MISSING_CONTENT_CATEGORY);
                                 else if (strtolower($columnName)=="edited quotes" && ! $currentColumnData) array_push($dataToAdd['warnings'],self::WARNING_EMPTY_QUOTE);
                                 else $dataToAdd[$columnName] = $currentColumnData;
                             }
                         }
                     }

                     if($identifier===false) {
                         array_push($unpairedQuotes,$dataToAdd);
                     } else {
                         array_push($sheetData[$identifier]["quotes"],$dataToAdd);
                     }
                 }
             }
         }
         return [$sheetData,$columnNameWarnings,$columnNameErrors,$unpairedQuotes];
     }
 }
