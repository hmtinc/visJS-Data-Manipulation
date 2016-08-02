/**
 * Diamond - visJS Data Manipulation (By Harsh Mistry)
 *
 * This Script Requires : Jquery-CSV and vis.JS
 *
 */ 

  // create a dataSet with groups
  var groups = new vis.DataSet();
  var container = document.getElementById('visualization-op');
  var dataset = new vis.DataSet();

  //Set Group Values
  function toGroup(val){
      var length = val[0].length;
      
      for (i = 1; i < length; i++) { 
        groups.add({
        id: i,
        content: val[0][i],
        options: {
            drawPoints: {
                style: 'circle' // square, circle
            },
        }});
      }
  }

  //Add CSV Values to Graph
  function toGraph(val){
      var length = val.length;
      var sublength = 0;
      
      for (i = 1; i < length; i++) { 
          sublength = val[i].length;
          for (j = 1 ; j < sublength; j++){
            var num = parseInt(val[i][j]);
            if (isNaN(num)){
                console.log("Ignoring NaN")
            }
            else{
                dataset.add([{x: val[i][0] , y: num, group: j}]);
            }
          }
      }
      
      toGroup(val);
  }

  //Convert CSV to Array
  function toArr(csvStr){
      csv = $.csv.toArrays(csvStr);
      console.log(csv);  
      toGraph(csv);
  }

  //Download CSV from server 
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

  toCSV();

  var options = {
      defaultGroup: 'ungrouped',
      legend: true,
      start: '2016-01-01',
      end: '2016-12-29'
  };
  var graph2d = new vis.Graph2d(container, dataset, groups, options);
