<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Alireza Zarei URL Shortner</title>
    <style>
        main {
            background-color: #264653;
            height: 100vh;
            font-family: "Avenir", sans-serif;
        }

        #header-div {
            width: 60%;
            margin: auto;
            padding-top: 100px;
            text-align: center;
            font-family: "Josefin Sans", sans-serif;
            color: #e9c46a;
            font-size: 40px;
        }

        #content-div {
            display: flex;
            flex-direction: column;
            width: 80%;
            margin: auto;
        }

        #input-div {
            width: 80%;
            margin: auto;
            margin-bottom: 25px;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        #output-div {
            display: flex;
            flex-direction: row;
            width: 80%;
            justify-content: space-between;
            background-color: #e9c46a;
            margin: auto;
            padding: 5px;
            border: 3px solid #e9c46a;
            border-radius: 10px;
            align-items: center;
            margin-bottom: 100px;
        }

        #error-div {
            width: 80%;
            margin: auto;
            color: #e76f51;
            font-style: italic;
            font-size: 18px;
            text-align: center;
        }

        .content-row {
            display: flex;
        }

        input[type=text] {
            width: 70%;
            font-size: 18px;
            padding: 10px;
            border: 1px solid white;
            border-radius: 10px;
        }


        .button {
            background-color: #2a9d8f;
            color: white;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            border: 1px solid #2a9d8f;
            border-radius: 10px;
            padding: 10px;
            margin-left: 5px;
            display: flex;
            transition-duration: 0.2s;
        }

        .button:hover {
            background-color: #297c74;
        }

        #new-url {
            display: flex;
            color: black;
            padding: 5px;
            font-size: 24px;
        }

        #new-url-label {
            font-size: 18px;
            display: flex;

        }

    </style>
</head>
<body>
<main>
    <!-- front end from https://codepen.io/annecburke/pen/OJWYLMW -->
    <div id="header-div" class="">
        <h1 class="">URL Shortener</h1>
    </div>
    <!-- link input and shorten button -->
    <div id="content-div">
        <div id="input-div">
            <input type="text" class="text-field content-row" placeholder="Enter URL here . . ." id="input-field" required/>
            <button id="shorten" type="button" class="content-row button">Shorten URL</button>
            <button type="button" id="clear-btn" class="content-row button">Clear</button>
        </div>
        <!-- Output and copy -->
        <div id="output-div">
            <div class="content-row" id="new-url-label">Your short URL:</div>
            <div id="new-url" class="content-row"></div>
            <button type="button" id="copy-btn" data-clipboard-target="#new-url" class="content-row button">Copy</button>
        </div>
        <div class="" id="error-div">
            <p class="content-row" id="error-text"></p>
        </div>
    </div>

</main>
<!--App script-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.10/clipboard.min.js"></script>

<script defer>
    const button = document.querySelector("#shorten");
    const input = document.querySelector("#input-field");
    const longUrl = document.querySelector("#input-url");
    const shortUrl = document.querySelector("#new-url");
    const resultDiv = document.querySelector("#output-div")
    const errorDiv = document.querySelector("#error-div");
    const errorMessage = document.querySelector("#error-text");
    const clearButton = document.querySelector("#clear-btn");
    const copyButton = document.querySelector("#copy-btn");

    /* button action */
    button.addEventListener("click", (event) => {
        event.preventDefault();
        if(input.value) {
            shorten(input.value);
        } else {
            showError();
            hideResult();
        }
    })

    /* function to handle errors */
    const handleError = (response) => {
        console.log(response);
        if(!response.ok) {
            errorMessage.textContent = "Please enter a valid URL."
            showError();
            hideResult();
        } else {
            hideError();
            return response;
        }
    }

    /* function to get shortened url with input "url" with fetch and deal with error */
    const shorten = (input) => {
        fetch("URLHERE", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({"url": input})
        })
            .then(handleError)
            .then(response => response.json())
            .then((json) => {
                shortUrl.innerHTML = json.link;
                showResult();
            })
            .catch(error => {
                console.log(error);
            })
    }


    /* Clipboard functions */

    const clipboard = new ClipboardJS("#copy-btn");

    clipboard.on('success', function(e) {
        console.info('Action:', e.action);
        console.info('Text:', e.text);
        console.info('Trigger:', e.trigger);

        e.clearSelection();
    });

    clipboard.on('error', function(e) {
        console.error('Action:', e.action);
        console.error('Trigger:', e.trigger);
    });

    /* Clear fields */
    const clearFields = () => {
        input.value = '';
        hideResult();
        hideError();
    }

    clearButton.addEventListener("click", (event) => {
        event.preventDefault();
        clearFields();
    })


    /* display/hide results and errors */
    const showResult = () => {
        shortUrl.style.display = "flex";
    }

    const hideResult = () => {
        shortUrl.style.display = "none";
    }

    const showError = () => {
        errorDiv.style.display = "block";
    }

    const hideError = () => {
        errorDiv.style.display = "none";
    }
</script>

</body>
</html>
