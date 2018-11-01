
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Test and Debug your APNS notifications online.">
    <meta name="author" content="Sergio Cirasa">
    <link rel="icon" href="images/fav.png">

    <title>Test APNS push notifications</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">


  </head>

  <body>     

    <div class="container">

      <div class="form-apns">
        <h2 class="form-signin-heading">Test APNS push notifications</h2>

        <div id="form-group-certificate" class="form-group">
          <label for="certificate">PEM Certificate </label>
          <a href="#" data-toggle="modal" data-target="#pemModal">
             <span class="glyphicon glyphicon glyphicon-info-sign" aria-hidden="true"></span>
          </a>
          <input type="file" id="certificate" required>
        </div>

        <div id="form-group-server" class="form-group">
          <label for="exampleInputFile">Server</label>
          <div class="radio">
            <label>
              <input type="radio" name="optionsServer" id="server" value="0" checked>
              Sandbox
            </label>
          </div>
          <div class="radio">
            <label>
              <input type="radio" name="optionsServer" id="server" value="1">
              Production
            </label>
          </div>          
        </div>
        <div id="form-group-tokens" class="form-group">
          <label for="tokens">Device token(s)</label>
          <textarea type="text" class="form-control" id="tokens" placeholder="Enter a comma separated list of device tokens" rows="3" required></textarea>
        </div>
        <div id="form-group-json" class="form-group">
          <label for="json">JSON</label>
          <a href="#" data-toggle="modal" data-target="#jsonModal">
             <span class="glyphicon glyphicon glyphicon-info-sign" aria-hidden="true"></span>
          </a>
          <textarea type="text" class="form-control" id="json" placeholder="Please enter the notification json." rows="10"></textarea>
        </div>
        <div class="form-group">
          <button id="send" type="submit" class="btn btn-lg btn-primary btn-block">Submit</button>
        </div>
      </div>

    </div> <!-- /container -->


    <!-- Modal -->
    <div class="modal fade" id="pemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h3 class="modal-title" id="myModalLabel">Hot to create a .pem file</h3>
          </div>
          <div class="modal-body">
            <h4>Entry files:</h4> 
            <ul>
              <li>aps_development.cer (Get it from Dev Center)</li>
              <li>development.p12 (Get it from keychain)</li>
            </ul>
            <hr class="">

            <h4>Step 1: Create the .pem file for the certificate:</h4> 
            <div class="zero-clipboard" id="copyBtn" data-clipboard-demo="" data-clipboard-target="#step1"><span class="btn-clipboard">Copy</span></div>
              <figure class="highlight">
                <pre><code class="language-js" data-lang="js" id="step1">openssl x509 -in aps_development.cer -inform der -out aps_developerCer.pem</code></pre></figure>
            <hr class="">
            <h4>Step 2: Create the .pem file for the P12:</h4> 
            <div class="zero-clipboard" id="copyBtn" data-clipboard-demo="" data-clipboard-target="#step2"><span class="btn-clipboard">Copy</span></div>
              <figure class="highlight">
                <pre><code class="language-js" data-lang="js" id="step2">openssl pkcs12 -nocerts -out apns-dev-key.pem -in development.p12</code></pre></figure>         

            <hr class="">
            <h4>Step 3: Decrypt the .pem file of P12:</h4> 
            <div class="zero-clipboard" id="copyBtn" data-clipboard-demo="" data-clipboard-target="#step3"><span class="btn-clipboard">Copy</span></div>
              <figure class="highlight">
                <pre><code class="language-js" data-lang="js" id="step3">openssl rsa -in apns-dev-key.pem -out apns-dev-key-noenc.pem</code></pre></figure>      
            <hr class="">
            <h4>Step 4: Concatenate files:</h4> 
            <div class="zero-clipboard" id="copyBtn" data-clipboard-demo="" data-clipboard-target="#step4"><span class="btn-clipboard">Copy</span></div>
              <figure class="highlight">
                <pre><code class="language-js" data-lang="js" id="step4">cat aps_developerCer.pem apns-dev-key-noenc.pem > apns-dev.pem</code></pre></figure>    
          
        </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="jsonModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h3 class="modal-title" id="myModalLabel">Payload example:</h3>
          </div>
          <div class="modal-body">
            <div class="zero-clipboard" id="copyBtn" data-clipboard-demo="" data-clipboard-target="#json-sample"><span id="copyJson" class="btn-clipboard">Copy</span></div>
              <figure class="highlight">
                <pre><code class="language-js" data-lang="js" id="json-sample">{
  "aps": {
    "alert": {
      "title": "Acme title",
      "body": "Acme description"
    },
    "badge": 1,
    "sound": "default"
  }
}</code></pre></figure>        
For more info see <a href="https://developer.apple.com/library/content/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/PayloadKeyReference.html#//apple_ref/doc/uid/TP40008194-CH17-SW1" class="tooltip-test" title="" data-original-title="Tooltip">Payload Key Reference</a>  
        </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.blockUI.js"></script>    
    <script src="js/clipboard.min.js"></script>
    <script src="js/helper.js"></script>
  <script>
    var clipboard = new Clipboard("#copyBtn");
  
    clipboard.on('success', function(e) {
        $(".btn-clipboard").attr('class', 'btn-clipboard');
        if(e.trigger.childNodes[0]!=null){
          e.trigger.childNodes[0].className = "btn-clipboard btn-clipboard-hover";        
          
          if(e.trigger.childNodes[0].id == "copyJson"){
          	if($("#json").val()==""){
	          	$("#json").val($("#json-sample").text());
          	}
          }
        }
    });

    clipboard.on('error', function(e) {
        console.log(e);
    });
  </script>
  </body>
</html>
