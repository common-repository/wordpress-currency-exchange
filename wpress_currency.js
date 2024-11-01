

function CDownloadUrlSync(method, url, func) {

   var httpObj;
   var browser = navigator.appName;
   if(browser.indexOf("Microsoft") > -1)
      httpObj = new ActiveXObject("Microsoft.XMLHTTP");
   else
      httpObj = new XMLHttpRequest();
      
   httpObj.open(method, url, false); 
   httpObj.send(null);
   if(httpObj.status == 200)
   {
  	  var contenttype = httpObj.getResponseHeader('Content-Type');
      if (contenttype.indexOf('xml')>-1) 
          func(httpObj.responseXML);
       else 
         func(httpObj.responseText);
   };

}

function CDownloadUrl(method, url, func) {
   var httpObj;
   var browser = navigator.appName;
   if(browser.indexOf("Microsoft") > -1)
      httpObj = new ActiveXObject("Microsoft.XMLHTTP");
   else
      httpObj = new XMLHttpRequest();
 
   httpObj.open(method, url, true);
   httpObj.onreadystatechange = function() {
      if(httpObj.readyState == 4){
         if (httpObj.status == 200) {
            var contenttype = httpObj.getResponseHeader('Content-Type');
            if (contenttype.indexOf('xml')>-1) {
               func(httpObj.responseXML);
            } else {
               func(httpObj.responseText);
            }
         } else {
            func('Error: '+httpObj.status);
         }
      }
   };
   httpObj.send(null);

}


function parseXML(data) 
{ 
   
  var xmlDoc;  
  // code for IE 
  if (window.ActiveXObject) 
  { 
  	 xmlDoc=new ActiveXObject("Microsoft.XMLDOM"); 
   	 xmlDoc.async=false; 
     xmlDoc.loadXML(data); 
  } 
  // code for Mozilla, Firefox, Opera, etc. 
  else 
  { 
     var parser = new DOMParser(); 
     xmlDoc = parser.parseFromString(data,"text/xml"); 
   } 
   return xmlDoc;
 } 
 
 function wpcur_makeExchange(theUrl,curFrom, curTo, curAmount)
		{	
			var urlCurrencies = 
				theUrl + "?CURFROM=" + curFrom + "&CURTO=" + curTo + "&CURAMOUNT=" + curAmount;
			
			
			// alert(urlCurrencies);							   
			var resultAmount = "";		  		   
			CDownloadUrlSync("get",urlCurrencies, function(data) {
       			
       			var xml      = parseXML(data);
       			var result   = xml.documentElement.getElementsByTagName("resultexchange");
       			resultAmount = result[0].getAttribute("amount");
       			
       			// alert(resultAmount);
			});
			
			return resultAmount;
		};	