(function jvxPixelWrapper(){

    var isDebug = false;
    var existingData = [];
    var existingGroups = {};
    var pixelParams = {"px":"85c627dfcceab0"};
    var addnlPixelData = function getRetargetingData() {
   // refer to help link for more options
 return ["1"];
 };
    var partnerSyncPixels = {};

    function getParamValue(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    function firePixel(pixelId, pixelValArr) {
        var pixelURL = "//pxl.jivox.com/tags/re/pxrc.php?c=1&px=" + pixelId + ""; // basic URL
        pixelURL += "&cData=" + pixelValArr.join(); // add derived params to the basic URL
        pixelURL += "&r=" + Math.random(); // add a random no so that we are not cached

        var appendNode = document.body ? document.body : document.getElementsByTagName("head")[0];
        var scriptEl = document.createElement("script");
        scriptEl.src = pixelURL;
        appendNode.appendChild(scriptEl);
    }

    function fireMultiPixel(pixelValObj) {
        var uniqueId = Math.floor(Math.random() * (10000)),
            pixelObj = JSON.stringify(pixelValObj);

        /*********** iFrame Form POST *****************/
        if (pixelParams.pxType == null || pixelParams.pxType == "1") {
            var jvxFrame = document.createElement('iframe');
            jvxFrame.style.width = '0px';
            jvxFrame.style.height= '0px';
            jvxFrame.style.display = 'none';
            jvxFrame.id = "jvxFrame" + uniqueId;

            var jvxForm = document.createElement('form');
            jvxForm.id = "jvxForm" + uniqueId;
            jvxForm.method = "post";
            jvxForm.action = "//pxl.jivox.com/tags/re/pxrc.php";

            var jvxFormField1 = document.createElement('input');
            jvxFormField1.name = "c" ;
            jvxFormField1.type = 'text';
            jvxFormField1.value = "1";
            jvxForm.appendChild(jvxFormField1);

            var jvxFormField4 = document.createElement('input');
            jvxFormField4.name = "px" ;
            jvxFormField4.type = 'text';
            jvxFormField4.value = pixelParams.px;
            jvxForm.appendChild(jvxFormField4);


            var jvxFormField2 = document.createElement('input');
            jvxFormField2.name = "cMultiData" ;
            jvxFormField2.type = 'text';
            jvxFormField2.value = pixelObj;
            jvxFormField2.type = 'text';
            jvxForm.appendChild(jvxFormField2);

            var jvxFormField3 = document.createElement("input");
            jvxFormField3.type = 'submit';
            jvxForm.appendChild(jvxFormField3);

            var appendNode = document.body ? document.body : document.getElementsByTagName("head")[0];
            appendNode.appendChild(jvxFrame);

            var jvxHtml = "<html><head></head><body></body></html>";
            jvxFrame.contentWindow.document.open('text/html', 'replace');
            jvxFrame.contentWindow.document.write(jvxHtml);
            jvxFrame.contentWindow.document.close();

            jvxFrame.contentWindow.document.body.appendChild(jvxForm);

            /***************************** End ***************************/
            jvxForm.submit();
        } else if (pixelParams.pxType == "2") {

            /* COM: Since iFrame form post is adding new entry to browser history, Ajax call is being used to stop it. */
            /* These changes has to be param driven and by default form post approach has to be executed. */

            /************* Ajax call *************/
            var fData = "c=1&px=" + pixelParams.px + "&cMultiData="+pixelObj;
            var xmlhttp = new XMLHttpRequest();
            if ("withCredentials" in xmlhttp) {
                // for Chrome, Firefox, Opera
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4) {
                        if (xmlhttp.status == 200 || xmlhttp.status == 304) {
                            debugLog("post successfull");
                        } else {
                            debugLog("post failed!");
                        }
                    }
                }

                xmlhttp.open("POST", "//pxl.jivox.com/tags/re/pxrc.php", true);
                xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xmlhttp.withCredentials = true;
                xmlhttp.send(fData);
            } else {
                // for IE < 10
                var xdr = new XDomainRequest();
                xdr.onerror = function(){debugLog("IE get failed!"); };
                xdr.ontimeout = function(){debugLog("IE get failed!");};
                xdr.onload = function() {
                    debugLog("IE get successfull!");
                };

                xdr.open("GET", "//pxl.jivox.com/tags/re/pxrc.php"+"?"+fData);
                xdr.send();
            }

            /************* End *****************/
        }
    }

    function firePartnerSyncCookiePixel(){
        for (var key in partnerSyncPixels){
            debugLog("PartnerSyncCookiePixel : "+key+" :"+ partnerSyncPixels[key]);
            var imgEl = document.createElement('img');
            var URL = partnerSyncPixels[key];
            if(URL.indexOf("?") == -1){
                URL += "?r=" + Math.random();
            }else{
                URL += "&r=" + Math.random();
            }
            imgEl.src = URL;
        }
    }

    function debugLog(val) {
        if (isDebug) {
            console.log(val);
        }
    }

    if(window.Prototype) {
        var _json_stringify = JSON.stringify;
        JSON.stringify = function(value) {
            var _array_tojson = Array.prototype.toJSON;
            delete Array.prototype.toJSON;
            var r=_json_stringify(value);
            Array.prototype.toJSON = _array_tojson;
            return r;
        };
    }
    var data = addnlPixelData();
    if (Object.prototype.toString.call(data) === '[object Array]' ) {
        firePixel("85c627dfcceab0", data);
    } else	if (Object.prototype.toString.call(data) === '[object Object]' ) {
        fireMultiPixel(data);
    }
    if(Object.prototype.toString.call(partnerSyncPixels) === '[object Object]'){
        firePartnerSyncCookiePixel();
    }

})();
