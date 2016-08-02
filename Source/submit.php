
    //Posted Values 
    $val = $_POST['val'];
    $path = $_POST['path'];

 
    //Convert CSV to ARRAY 
    function csv_to_array($filename, $delimiter){
        $rows = array_map('str_getcsv', file($filename));
        $header = array_shift($rows);
        $csv = array();
        foreach ($rows as $row) {
           $csv[] = array_combine($header, $row);
         }
        return $csv;
    }
    
    //Determine status of data entry 
    function update_or_new($array, $date, $dataArray, $path){
        $update = False;
        $arrLen = count($array);
        $count  = 0;
        $index = 0;
      
        foreach($array as $entry ){
            if ($entry['date'] == $date){
               $update = True;
               
               $index = $count;
               break;
            }
            
            $count++;
        }
        
        //Make a new entry 
        if ($update == False){
            new_entry($array, $date, $dataArray ,$path);
        }
        else {
            update_entry($array, $date, $dataArray, $path, $index);
        }
    }
          
    //Update an entry
    function update_entry($array, $date, $dataArray, $path, $index){
        
       $count = -1;
       $erase = False;
       $eCount = 0;
    
       //Iterate over each Cell and update the values
       foreach ($array[$index] as &$cell){
           if($count == 0) {
               $count = 3;
           }
           
           if ($count > 0 && $cell != "" ) {
              
               $cellNum = intval($cell);
               $cellNum += $dataArray[$count];
               $cell = strval($cellNum);
               
           }
           else if ($count > 0){
               $cell = strval($dataArray[$count]);
           }
           
           $count++;
       }
        
       //Determine if the entry should be deleted. 
       foreach ($array[$index] as $cell){
           if ($ecount == 0){
               $erase = False;
           }
           else if ($cell != ""){
               $erase = False;
               break;
           }
           
           $ecount++;
           $erase = True;
       }
        
       //Delete entry if Erase is true
       if($erase == True){
           unset($array[$index]);
       }    
    
        //Save Array to CSV
        to_csv($array, $path);
    }
    
    //Make a new entry
    function new_entry($array, $date, $dataArray, $path){
        
        $count = 0;
        $start = 3;
        $arrlen = count($array);
        
        //Get Template
        $template = $array[0];
        $tempLen = count($template);
        
        //Push Template to bottom of array
        array_push ($array, $template);
        
        //Copy over DataArray Values
        foreach ($array[$arrlen] as &$cell){
            
            if ($count == 0){
                $cell = $date;
            }
            elseif ($count == 1){
                $count = $start;
                $cell = $dataArray[$count];
            }
            else {
                $cell = $dataArray[$count];
            }
            
            $count++;
        }
        
        //Save Array to CSV
        to_csv($array, $path);
    }
    
    //Array to CSV
    function to_csv($array, $path){
       
       //erase zeros
       foreach ($array as &$entry){
           foreach ($entry as &$cell){
               if ($cell == "0"){
                   $cell = "";
               }
           }
       }
        
       // Get CSV String
       $csvStr = str_putcsv($array);
        
       // Write CSV String
       file_put_contents($path, $csvStr);
    
    }
    
    //Convert Array to CSV string. 
    function str_putcsv($data) {
        $fh = fopen('php://temp', 'rw'); 
        fputcsv($fh, array_keys(current($data)));
        foreach ( $data as $row ) {
                fputcsv($fh, $row);
        }
        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);
        return $csv;
    }
    

    //Get and Format Date
    $dash = "-";
    $year = strval($val[0]);
    $month = sprintf("%02d", $val[1]);
    $day = sprintf("%02d", $val[2]);
    $date = $year . $dash . $month . $dash . $day; 
    
    //Convert CSV to array 
    $csv = csv_to_array($path, ',');
    
    //Apply Required Steps 
    update_or_new($csv, $date, $val, $path);
        
    //Message
    echo "Values have been submited to server. Please check dashboard to ensure values have been correctly entered."
