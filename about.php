 <!-- Masthead-->
        <header class="masthead">
            <div class="container h-100">
                <div class="row h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-10 align-self-end mb-4" style="background: #0000002e;">
                    	 <h1 class="text-uppercase text-white font-weight-bold">About Us</h1>
                        <hr class="divider my-4" />
                    </div>
                    
                </div>
            </div>
        </header>

    <section class="page-section">
        <div class="container">
    <?php echo html_entity_decode($_SESSION['system']['about_content']) ?>        
            
        </div>
        
        </section>
        <div>
        <style>
         .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 10000;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        #paymentBtn{
            align-item:center;
            padding:15px 35px;
            margin-left:553px;
            font-size:24px;     
            border: 1px solid black;      
            
        }
        #paymentBtn:hover{
            background-color:lightblue;
        }
        </style>
        
        <button id="paymentBtn">Make a Payment</button>
    <div class="overlay" id="overlay"></div>
    <div class="popup" id="popup">
        <!-- This is where your PHP file content will be loaded -->
    </div>

    <script>
        document.getElementById("paymentBtn").addEventListener("click", function() {
            document.getElementById("overlay").style.display = "block";
            document.getElementById("popup").style.display = "block";

            // Load PHP file content into the popup
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("popup").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "payment_form.php", true);
            xmlhttp.send();
        });

        document.getElementById("overlay").addEventListener("click", function() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("popup").style.display = "none";
        });
        document.getElementById("overlay").addEventListener("click", function() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("popup").style.display = "none";
        });
    </script>
</div>