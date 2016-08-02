![logo](https://raw.githubusercontent.com/hmtinc/visJS-Data-Manipulation/master/Source/logo.png)
# Diamond - visJS Data Manipulation
visJS Data Manipulation scripts allow for a user to post data to a server and have it displayed for all users 
through the visJS Graph2D library.

# Why? 
visJS is an excellent graphing library for creating 2D timeline graphs, unfortunately it does provide methods
for pulling data from an external source and allowing users to submit/modify data. This set of scripts attempts
to address this need by allow for visJS to import data from a CSV file on a server and allow for users to submit data 
to the remote CSV file.

#Usage : Posting Data 
A major feature of Diamond, is that user can submit new data or modify existing data entries. To achieve this function, a PHP script (submit.php) is used to enter new data or modify existing data. The script will determine if a entry exists or not and they update the values accordingly.

To use this function, you must design your site to POST data to the PHP script. All values must be posted under the name "val" and the path of the CSV must be posted under the name "path". "val" should be an array of values with the first 3 values being Year, Month, Day followed by all the data values (refer to table below) .

Val Index | Value 
------------ | -------------
val[0] | Year
val[1] | Month
val[2]  | Day
val[3]  | First Value

In addition, ensure the CSV file on the server is formatted to match the table below. 

date | ValueName | ValueName2 
------------ | ------------- | -------------
2016-03-01 | Value | Value
2016-03-01 | Value | Value

Please note that dates posted do not have to be submitted with dashes (-) or reformatted to be 4/2 digits long.

#Usage : Embedding the Graph
Integrating the Graph into a web page is fairy straight forward, but there are few steps to ensure the graph can be display data from a server.

First, ensure line 10 of graph.js is set to your desired ID name, as this what ID your div container must have. By Default the ID is 
"visualization-op".
```javascript 
var container = document.getElementById('visualization-op');
```

Second, Replace add_goes_here in the toCSV() function with the location of the remote data file. 
```javascript
function toCSV() {
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
          var csvdat = xhttp.responseText;
          console.log(csvdat);
          toArr(csvdat);
        }
      };
      xhttp.open("GET", "add_goes_here", false);
      xhttp.send();
  }
```
Next, Include the graph.js file in your web page
```html
<script type="text/javascript" src="graph.js"></script>
```

Lastly, 
Include a div on your web page with an ID that matches your desired ID.
```html
<div id="visualization-op"></div>
```

#Required Libraries
- JQuery : https://github.com/jquery/jquery
- vis.JS : https://github.com/almende/vis

#Goals
The long term goal for Diamond (visJS Data Manipulation) is to provide support for other storage methods and more
complicated data entry modification methods. 



